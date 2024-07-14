<?php

namespace App\DTO\Mail;

use App\Exception\EmptyValueException;
use Exception;

/**
 * Class MailDTO
 * @package App\DTO\Mail
 */
class MailDTO
{
    public const MAX_TO_EMAILS = 1;

    /**
     * Sends symfony styled notification (Warning / error etc.)
     */
    public const TYPE_NOTIFICATION = "NOTIFICATION";

    /**
     * Sends plain E-Mail, meaning that nothing extra gets rendered, wrapped into etc.
     */
    public const TYPE_PLAIN = "PLAIN";

    public const ALLOWED_TYPES = [
        self::TYPE_NOTIFICATION,
        self::TYPE_PLAIN,
    ];

    const KEY_FROM_EMAIL       = 'fromEmail';
    const KEY_SUBJECT          = 'subject';
    const KEY_BODY             = 'body';
    const KEY_SOURCE           = 'source';
    const KEY_TO_EMAILS        = 'toEmails';
    const KEY_ATTACHMENTS      = 'attachments';
    const KEY_EMAIL_TYPE       = "emailType";
    const KEY_TRACK_OPEN_STATE = "trackOpenState";

    /**
     * @var string $fromEmail
     */
    private string $fromEmail = "";

    /**
     * @var string $emailType
     */
    private string $emailType = "";

    /**
     * @var string $subject
     */
    private string $subject = "";

    /**
     * @var string $body
     */
    private string $body = "";

    /**
     * @var string $source
     */
    private string $source = "";

    /**
     * @var array $toEmails
     */
    private array $toEmails = [];

    /**
     * Key is a file name, value is file_content {@see file_get_contents()}
     *
     * @var Array<string> $attachments
     */
    private array $attachments = [];

    /**
     * @var bool $trackOpenState
     */
    private bool $trackOpenState = false;

    /**
     * @return string
     */
    public function getFromEmail(): string
    {
        return $this->fromEmail;
    }

    /**
     * @param string $fromEmail
     */
    public function setFromEmail(string $fromEmail): void
    {
        $this->fromEmail = $fromEmail;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @param string $source
     */
    public function setSource(string $source): void
    {
        $this->source = $source;
    }

    /**
     * @return array
     */
    public function getToEmails(): array
    {
        return $this->toEmails;
    }

    /**
     * @param array $toEmails
     */
    public function setToEmails(array $toEmails): void
    {
        $this->toEmails = $toEmails;
    }

    /**
     * @return int
     */
    public function countToEmails(): int
    {
        return count($this->getToEmails());
    }

    /**
     * @return bool
     */
    public function assertMaxToEmails(): bool
    {
        return ($this->countToEmails() <= self::MAX_TO_EMAILS);
    }

    /**
     * @return string[]
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }

    /**
     * @param string[] $attachments
     */
    public function setAttachments(array $attachments): void
    {
        $this->attachments = $attachments;
    }

    /**
     * @return string
     */
    public function getEmailType(): string
    {
        return $this->emailType;
    }

    /**
     * @param string $emailType
     */
    public function setEmailType(string $emailType): void
    {
        $this->emailType = $emailType;
    }

    /**
     * Returns dto data in form of string
     *
     * @return string
     */
    public function toJson(): string
    {
        $dataArray = $this->toArray();
        return json_encode($dataArray);
    }

    /**
     * @return bool
     */
    public function isTrackOpenState(): bool
    {
        return $this->trackOpenState;
    }

    /**
     * @param bool $trackOpenState
     */
    public function setTrackOpenState(bool $trackOpenState): void
    {
        $this->trackOpenState = $trackOpenState;
    }

    /**
     * Validate the object, throws exception if something is wrong
     *
     * @throws EmptyValueException
     */
    public function validateSelf(): void
    {
        if (empty($this->getSubject())) {
            throw new EmptyValueException("Subject is empty!");
        }

        if (empty($this->getSource())) {
            throw new EmptyValueException("Source is empty");
        }

        if (empty($this->getBody())) {
            throw new EmptyValueException("Body is empty");
        }

        if (empty($this->getFromEmail())) {
            throw new EmptyValueException("From E-Mail is empty");
        }

        if (empty($this->getToEmails())) {
            throw new EmptyValueException("No recipients are set!");
        }
    }

    /**
     * Returns dto data in form of array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::KEY_FROM_EMAIL       => $this->getFromEmail(),
            self::KEY_SUBJECT          => $this->getSubject(),
            self::KEY_BODY             => $this->getBody(),
            self::KEY_SOURCE           => $this->getSource(),
            self::KEY_TO_EMAILS        => $this->getToEmails(),
            self::KEY_ATTACHMENTS      => $this->getAttachments(),
            self::KEY_EMAIL_TYPE       => $this->getEmailType(),
            self::KEY_TRACK_OPEN_STATE => $this->isTrackOpenState(),
        ];
    }
}