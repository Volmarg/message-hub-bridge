<?php

namespace App\DTO\Discord;

/**
 * Class DiscordMessageDTO
 * @package App\DTO\Discord
 */
class DiscordMessageDTO
{
    const KEY_WEBHOOK_NAME    = 'webhookName';
    const KEY_MESSAGE_CONTENT = 'messageContent';
    const KEY_MESSAGE_TITLE   = 'messageTitle';
    const KEY_SOURCE          = 'source';

    /**
     * @var string $webhookName
     */
    private string $webhookName = "";

    /**
     * @var string $messageContent
     */
    private string $messageContent = "";

    /**
     * @var string $messageTitle
     */
    private string $messageTitle = "";

    /**
     * @var string $source
     */
    private string $source = "";

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
     * @return string
     */
    public function getWebhookName(): string
    {
        return $this->webhookName;
    }

    /**
     * @param string $webhookName
     */
    public function setWebhookName(string $webhookName): void
    {
        $this->webhookName = $webhookName;
    }

    /**
     * @return string
     */
    public function getMessageTitle(): string
    {
        return $this->messageTitle;
    }

    /**
     * @param string $messageTitle
     */
    public function setMessageTitle(string $messageTitle): void
    {
        $this->messageTitle = $messageTitle;
    }

    /**
     * @return string
     */
    public function getMessageContent(): string
    {
        return $this->messageContent;
    }

    /**
     * @param string $messageContent
     */
    public function setMessageContent(string $messageContent): void
    {
        $this->messageContent = $messageContent;
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
     * Returns dto data in form of array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::KEY_WEBHOOK_NAME    => $this->getWebhookName(),
            self::KEY_MESSAGE_CONTENT => $this->getMessageContent(),
            self::KEY_MESSAGE_TITLE   => $this->getMessageTitle(),
            self::KEY_SOURCE          => $this->getSource(),
        ];
    }

}