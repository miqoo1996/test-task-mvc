<?php

namespace App\Services\Notification;

interface Notifiable
{
    /**
     * @return EmailNotificationParams|SmsNotificationParams
     */
    public function getNotificationParams();

    public function send() : bool;
}