<?php

namespace App\Services\Notification;

class SmsService implements Notifiable
{
    private SmsNotificationParams $notificationParams;


    public function getNotificationParams(): SmsNotificationParams
    {
        return $this->notificationParams;
    }

    public function __construct(SmsNotificationParams $notificationParams)
    {
        $this->notificationParams = $notificationParams;
    }

    public function send(): bool
    {
        $params = $this->notificationParams->toArray();

        // Sms send logic isn't implemented yet.

        return false;
    }
}