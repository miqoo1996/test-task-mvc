<?php

namespace App\Services\Notification;

class EmailNotificationParams
{
    protected ?string $subject = null;
    protected ?string $from = null;
    protected ?string $to = null;
    protected ?string $replyTo = null;
    protected ?bool $isHtml = null;
    protected ?string $body = null;

    public function setFrom(string $from): self
    {
        $this->from = $from;
        return $this;
    }

    public function setTo(string $to): self
    {
        $this->to = $to;
        return $this;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;
        return $this;
    }

    public function setReplyTo(string $replyTo): self
    {
        $this->replyTo = $replyTo;
        return $this;
    }

    public function setIsHtml(bool $isHtml): self
    {
        $this->isHtml = $isHtml;
        return $this;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;
        return $this;
    }

    public function toArray() : array
    {
        return get_object_vars($this);
    }
}