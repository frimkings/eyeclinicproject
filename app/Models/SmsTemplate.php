<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsTemplate extends Model
{
    protected $fillable = ['key', 'label', 'message', 'placeholders'];

    protected $casts = ['placeholders' => 'array'];

    /** Render a template by key, replacing placeholders with given values. */
    public static function render(string $key, array $replacements): string
    {
        $tpl = static::where('key', $key)->first();
        if (!$tpl) return '';

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $tpl->message
        );
    }
}
