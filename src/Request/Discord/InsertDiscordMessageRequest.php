<?php


namespace App\Request\Discord;


use App\DTO\Discord\DiscordMessageDTO;
use App\Request\BaseRequest;

/**
 * Class InsertDiscordMessageRequest
 * @package App\Request\Discord
 */
class InsertDiscordMessageRequest extends BaseRequest
{
    const REQUEST_URI = "api/external/discord/insert-message";

    /**
     * @var DiscordMessageDTO $discordMessageDto
     */
    private DiscordMessageDTO $discordMessageDto;

    /**
     * @return DiscordMessageDTO
     */
    public function getDiscordMessageDto(): DiscordMessageDTO
    {
        return $this->discordMessageDto;
    }

    /**
     * @param DiscordMessageDTO $discordMessageDto
     */
    public function setDiscordMessageDto(DiscordMessageDTO $discordMessageDto): void
    {
        $this->discordMessageDto = $discordMessageDto;
    }

    /**
     * Returns the array string representation of current request
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->discordMessageDto->toArray();
    }

    /**
     * Returns the relative url to be called on external service
     *
     * @return string
     */
    public function getRequestUri(): string
    {
        return self::REQUEST_URI;
    }
}