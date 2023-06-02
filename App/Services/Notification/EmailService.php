<?php

namespace App\Services\Notification;

class EmailService implements Notifiable
{
    private EmailNotificationParams $notificationParams;

    public function __construct(EmailNotificationParams $notificationParams)
    {
        $this->notificationParams = $notificationParams;
    }

    public function getNotificationParams(): EmailNotificationParams
    {
        return $this->notificationParams;
    }

    public function send(): bool
    {
        $params = $this->notificationParams->toArray();

        // Sms send logic isn't implemented yet.

        return false;
    }
}