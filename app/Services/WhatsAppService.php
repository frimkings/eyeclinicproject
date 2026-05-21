<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\SmsLog;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private const API_VERSION = 'v19.0';
    private const BASE_URL    = 'https://graph.facebook.com';

    /**
     * Send a pre-approved WhatsApp template message via Meta Cloud API.
     *
     * The template must be created and approved in Meta Business Manager first.
     * Body parameters are positional ({{1}}, {{2}}, ...) and must match the
     * approved template's parameter count in the same order.
     *
     * @param string      $to           Recipient phone number
     * @param string      $templateName Meta-approved template name
     * @param string      $languageCode Template language code (e.g. 'en', 'en_US')
     * @param array       $bodyParams   Ordered parameter values for body component
     * @param int|null    $patientId    For logging
     * @param string|null $templateKey  For logging
     */
    public function sendTemplate(
        string  $to,
        string  $templateName,
        string  $languageCode = 'en',
        array   $bodyParams   = [],
        ?int    $patientId    = null,
        ?string $templateKey  = null
    ): array {
        $s = Setting::getSettings();

        if (empty($s->whatsapp_enabled) || !$s->whatsapp_enabled) {
            return ['success' => false, 'error' => 'WhatsApp notifications are disabled.'];
        }

        if (empty($s->whatsapp_phone_number_id) || empty($s->whatsapp_access_token)) {
            return ['success' => false, 'error' => 'WhatsApp not configured. Add credentials in Settings → WhatsApp.'];
        }

        try {
            $accessToken = Crypt::decryptString($s->whatsapp_access_token);
        } catch (\Exception $e) {
            Log::error('WhatsAppService: failed to decrypt access token.', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => 'WhatsApp credentials are corrupted. Please re-save them in Settings → WhatsApp.'];
        }

        $phone      = $this->normalizePhone($to);
        $parameters = array_map(fn ($val) => ['type' => 'text', 'text' => (string) $val], $bodyParams);
        $logMessage = "WA template:{$templateName} → " . implode('|', $bodyParams);

        $payload = [
            'messaging_product' => 'whatsapp',
            'to'                => $phone,
            'type'              => 'template',
            'template'          => [
                'name'       => $templateName,
                'language'   => ['code' => $languageCode],
                'components' => empty($parameters) ? [] : [
                    ['type' => 'body', 'parameters' => $parameters],
                ],
            ],
        ];

        try {
            $client   = new Client(['timeout' => 15]);
            $response = $client->post(
                self::BASE_URL . '/' . self::API_VERSION . '/' . $s->whatsapp_phone_number_id . '/messages',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $accessToken,
                        'Content-Type'  => 'application/json',
                    ],
                    'json' => $payload,
                ]
            );

            $body      = json_decode((string) $response->getBody(), true) ?? [];
            $ok        = !empty($body['messages'][0]['id']);
            $messageId = $body['messages'][0]['id'] ?? null;

            Log::info('WhatsApp send', ['phone' => $phone, 'template' => $templateName, 'ok' => $ok]);

            SmsLog::create([
                'patient_id'   => $patientId,
                'template_key' => $templateKey,
                'channel'      => 'whatsapp',
                'recipient'    => $phone,
                'message'      => $logMessage,
                'success'      => $ok,
                'error'        => $ok ? null : json_encode($body),
            ]);

            return $ok
                ? ['success' => true, 'message_id' => $messageId, 'response' => $body]
                : ['success' => false, 'error' => json_encode($body)];

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $raw  = (string) $e->getResponse()->getBody();
            $body = json_decode($raw, true);
            $msg  = $body['error']['message'] ?? $raw;

            SmsLog::create([
                'patient_id'   => $patientId,
                'template_key' => $templateKey,
                'channel'      => 'whatsapp',
                'recipient'    => $phone,
                'message'      => $logMessage,
                'success'      => false,
                'error'        => $msg,
            ]);

            return ['success' => false, 'error' => $msg];

        } catch (\Exception $e) {
            SmsLog::create([
                'patient_id'   => $patientId,
                'template_key' => $templateKey,
                'channel'      => 'whatsapp',
                'recipient'    => $phone,
                'message'      => $logMessage,
                'success'      => false,
                'error'        => $e->getMessage(),
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Send a test WhatsApp text message (only valid within a 24-hour customer-initiated window).
     * Useful for verifying credentials; NOT suitable for proactive outbound reminders.
     */
    public function sendText(string $to, string $message, ?int $patientId = null): array
    {
        $s = Setting::getSettings();

        if (empty($s->whatsapp_enabled) || !$s->whatsapp_enabled) {
            return ['success' => false, 'error' => 'WhatsApp notifications are disabled.'];
        }

        if (empty($s->whatsapp_phone_number_id) || empty($s->whatsapp_access_token)) {
            return ['success' => false, 'error' => 'WhatsApp not configured.'];
        }

        try {
            $accessToken = Crypt::decryptString($s->whatsapp_access_token);
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'WhatsApp credentials are corrupted.'];
        }

        $phone = $this->normalizePhone($to);

        try {
            $client   = new Client(['timeout' => 15]);
            $response = $client->post(
                self::BASE_URL . '/' . self::API_VERSION . '/' . $s->whatsapp_phone_number_id . '/messages',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $accessToken,
                        'Content-Type'  => 'application/json',
                    ],
                    'json' => [
                        'messaging_product' => 'whatsapp',
                        'to'                => $phone,
                        'type'              => 'text',
                        'text'              => ['body' => $message],
                    ],
                ]
            );

            $body = json_decode((string) $response->getBody(), true) ?? [];
            $ok   = !empty($body['messages'][0]['id']);

            SmsLog::create([
                'patient_id'   => $patientId,
                'template_key' => 'test',
                'channel'      => 'whatsapp',
                'recipient'    => $phone,
                'message'      => $message,
                'success'      => $ok,
                'error'        => $ok ? null : json_encode($body),
            ]);

            return $ok ? ['success' => true, 'response' => $body] : ['success' => false, 'error' => json_encode($body)];

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $raw  = (string) $e->getResponse()->getBody();
            $body = json_decode($raw, true);
            $msg  = $body['error']['message'] ?? $raw;
            return ['success' => false, 'error' => $msg];

        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D/', '', $phone);

        if (str_starts_with($digits, '0') && strlen($digits) === 10) {
            $digits = '233' . substr($digits, 1);
        }

        return ltrim($digits, '+');
    }
}
