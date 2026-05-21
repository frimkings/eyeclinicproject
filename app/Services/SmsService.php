<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\SmsLog;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Send an SMS via EazismsPro GET API.
     *
     * API format: GET {base_url}?action=send-sms&api_key=KEY&to=PHONE[&from=SENDERID]&sms=MESSAGE
     *
     * The `from` (sender ID) parameter is only included when a sender ID is configured.
     * EazismsPro requires sender IDs to be pre-registered in the dashboard before use.
     *
     * Returns ['success' => true,  'response' => [...]]
     *      or ['success' => false, 'error' => '...']
     */
    public function send(string $to, string $message, ?int $patientId = null, ?string $templateKey = null): array
    {
        $s = Setting::getSettings();

        if (isset($s->sms_enabled) && !$s->sms_enabled) {
            return ['success' => false, 'error' => 'SMS notifications are currently paused.'];
        }

        if (empty($s->sms_api_url) || empty($s->sms_api_key)) {
            return ['success' => false, 'error' => 'SMS not configured. Add credentials in Settings → SMS Settings.'];
        }

        try {
            $apiKey = Crypt::decryptString($s->sms_api_key);
        } catch (\Exception $e) {
            Log::error('SmsService: failed to decrypt API key. Re-save credentials in Settings → SMS Settings.', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => 'SMS credentials are corrupted. Please re-save them in Settings → SMS Settings.'];
        }

        $phone = $this->normalizePhone($to);

        $query = [
            'action'  => 'send-sms',
            'api_key' => $apiKey,
            'to'      => $phone,
            'sms'     => $message,
        ];

        // Only include `from` if a sender ID is configured — omitting it
        // lets EazismsPro use the account's default sender.
        if (!empty(trim($s->sms_sender_id ?? ''))) {
            $query['from'] = trim($s->sms_sender_id);
        }

        try {
            $client   = new Client(['timeout' => 15]);
            $response = $client->get($s->sms_api_url, ['query' => $query]);
            $raw      = (string) $response->getBody();
            $body     = json_decode($raw, true) ?? [];

            Log::info('EazismsPro send response', ['phone' => $phone, 'body' => $body]);

            // EazismsPro returns {"status":"success",...} on success
            $ok = isset($body['status']) && strtolower($body['status']) === 'success';

            $providerMsg = $ok ? null : ($body['message'] ?? $body['error'] ?? $raw);

            SmsLog::create([
                'patient_id'   => $patientId,
                'template_key' => $templateKey,
                'recipient'    => $phone,
                'message'      => $message,
                'success'      => $ok,
                'error'        => $providerMsg,
            ]);

            if ($ok) {
                return ['success' => true, 'response' => $body];
            }

            return ['success' => false, 'error' => $providerMsg];

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $raw  = (string) $e->getResponse()->getBody();
            $body = json_decode($raw, true);
            $msg  = ($body['message'] ?? $body['error'] ?? null) ?: $raw;
            SmsLog::create([
                'patient_id'   => $patientId,
                'template_key' => $templateKey,
                'recipient'    => $phone,
                'message'      => $message,
                'success'      => false,
                'error'        => $msg,
            ]);
            return ['success' => false, 'error' => $msg];
        } catch (\Exception $e) {
            SmsLog::create([
                'patient_id'   => $patientId,
                'template_key' => $templateKey,
                'recipient'    => $phone,
                'message'      => $message,
                'success'      => false,
                'error'        => $e->getMessage(),
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Check the SMS account balance.
     *
     * API format: GET {base_url}?action=check-balance&api_key=KEY&response=json
     */
    public function checkBalance(): array
    {
        $s = Setting::getSettings();

        if (empty($s->sms_api_url) || empty($s->sms_api_key)) {
            return ['success' => false, 'error' => 'SMS not configured.'];
        }

        try {
            $apiKey = Crypt::decryptString($s->sms_api_key);
        } catch (\Exception $e) {
            $apiKey = $s->sms_api_key;
        }

        try {
            $client   = new Client(['timeout' => 10]);
            $response = $client->get($s->sms_api_url, [
                'query' => [
                    'action'   => 'check-balance',
                    'api_key'  => $apiKey,
                    'response' => 'json',
                ],
            ]);

            $body = json_decode($response->getBody(), true) ?? [];
            Log::info('EazismsPro balance response', $body);
            return ['success' => true, 'response' => $body];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D/', '', $phone);

        // Ghana: leading 0 + 9 digits → 233xxxxxxxxx
        if (str_starts_with($digits, '0') && strlen($digits) === 10) {
            $digits = '233' . substr($digits, 1);
        }

        // EazismsPro expects plain digits — no leading +
        return ltrim($digits, '+');
    }
}
