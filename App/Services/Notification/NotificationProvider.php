<?php

namespace App\Services\Notification;

use App\Core\Exceptions\CoreException;

class NotificationProvider
{
    public function make(string $providerName) : Notifiable
    {
        switch ($providerName) {
            case 'email':
                return new EmailService(new EmailNotificationParams());
            case 'sms':
                return new SmsService(new SmsNotificationParams());
            default:
                throw new CoreException("Notification service $providerName not found.");
        }
    }
}