<?php

namespace App\Notifications;

use App\Models\Setting;
use Illuminate\Notifications\Notifiable;

class ClinicBackupNotifiable
{
    use Notifiable;

    public function routeNotificationForMail(): string
    {
        return Setting::getSettings()->clinic_email
            ?? config('mail.from.address', 'admin@clinic.com');
    }
}
