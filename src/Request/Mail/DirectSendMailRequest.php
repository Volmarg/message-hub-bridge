<?php


namespace App\Request\Mail;


use App\DTO\Mail\MailDTO;
use App\Request\BaseRequest;

/**
 * Class DirectSendMailRequest
 * @package App\Request\Mail
 */
class DirectSendMailRequest extends BaseRequest
{

    const REQUEST_URI = "api/external/mailing/direct-send";

    /**
     * @var MailDTO $mailDto
     */
    private MailDTO $mailDto;

    /**
     * @return MailDTO
     */
    public function getMailDto(): MailDTO
    {
        return $this->mailDto;
    }

    /**
     * @param MailDTO $mailDto
     */
    public function setMailDto(MailDTO $mailDto): void
    {
        $this->mailDto = $mailDto;
    }

    /**
     * Returns the array string representation of current request
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->mailDto->toArray();
    }

    /**
     * @return string
     */
    public function getRequestUri(): string
    {
        return self::REQUEST_URI;
    }
}