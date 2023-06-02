<?php

namespace App\Models\Text;

use App\Core\DI\DI;
use App\Core\Utils\Config;
use App\Services\Notification\NotificationProvider;

class TextManager
{
    public NotificationProvider $notificationProvider;

    public Text $text;

    public function __construct()
    {
        $this->notificationProvider = DI::make(NotificationProvider::class);

        $this->text = DI::make(Text::class);
    }

    public function lastTextData() : array
    {
        return (array) $this->text->getActiveRecord()->lastRow();
    }

    public function saveTextAndNotify(?int $id, string $uniqueKey, string $value) : bool
    {
        $result = $this->text->setAttribute('id', $id)->save(['unique_key' => $uniqueKey, 'value' => $value]);

        $emailConfig = (array) (Config::getInstance()->get('notification')['email'] ?? []);

        $smsConfig = (array) (Config::getInstance()->get('notification')['sms'] ?? []);

        if ($result->id) {
            $emailService = $this->notificationProvider->make('email');

            $smsService = $this->notificationProvider->make('sms');

            $emailService
                ->getNotificationParams()
                ->setFrom($emailConfig['fromEmail'] ?? '')
                ->setTo($emailConfig['adminEmail'] ?? '')
                ->setIsHtml(false)
                ->setSubject($emailConfig['defaultSubject'] ?? '')
                ->setBody(htmlentities($value));

            $smsService
                ->getNotificationParams()
                ->setFrom($smsConfig['fromPhone'] ?? '')
                ->setTo($smsConfig['adminPhone'] ?? '')
                ->setSubject($smsConfig['defaultSubject'] ?? '')
                ->setBody(htmlentities($value));

            $emailService->send();

            $smsService->send();

            return true;
        }

        return false;
    }
}