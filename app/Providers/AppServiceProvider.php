<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
  public function boot()
{
    // Global Date Directive
    Blade::directive('formatDate', function ($expression) {
        return "<?php echo {$expression} 
            ? htmlspecialchars_decode(\\Carbon\\Carbon::parse({$expression})->format('jS F, Y')) 
            : ''; 
        ?>";
    });

    // Share settings safely
    if (!app()->runningInConsole() && Schema::hasTable('settings')) {
        // Fetch settings, OR provide a temporary "empty" object so the app doesn't crash
        $settings = \App\Models\Setting::first() ?? new \App\Models\Setting([
            'clinic_name' => 'Eye Clinic System',
            'clinic_address' => '',
            'clinic_logo' => null
        ]);

        view()->share('appSettings', $settings);
    }

    // Apply SMTP config from database so mail works without touching .env
    try {
        if (Schema::hasTable('settings')) {
            $s = \App\Models\Setting::first();
            if ($s && $s->smtp_host && $s->smtp_username) {
                $password = '';
                if ($s->smtp_password) {
                    try {
                        $password = \Illuminate\Support\Facades\Crypt::decrypt($s->smtp_password);
                    } catch (\Throwable) {}
                }
                config([
                    'mail.default'                 => 'smtp',
                    'mail.mailers.smtp.host'       => $s->smtp_host,
                    'mail.mailers.smtp.port'       => (int) ($s->smtp_port ?? 587),
                    'mail.mailers.smtp.username'   => $s->smtp_username,
                    'mail.mailers.smtp.password'   => $password,
                    'mail.mailers.smtp.encryption' => $s->smtp_encryption,
                    'mail.from.address'            => $s->smtp_from_address ?? $s->clinic_email,
                    'mail.from.name'               => $s->smtp_from_name    ?? $s->clinic_name,
                ]);
            }
        }
    } catch (\Throwable) {}

    // Super Admin Gate
    Gate::before(function ($user, $ability) {
        return $user->hasRole('Super Admin') ? true : null;
    });
}
}
