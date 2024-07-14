<?php

namespace App\Request\Mail;

use App\Request\BaseRequest;

/**
 * Class GetMailStatusRequest
 * @package App\Request\Mail
 */
class GetMailStatusRequest extends BaseRequest
{
    private const REQUEST_URI  = "api/external/mailing/get-mail-status/{id}";
    private const URI_PARAM_ID = "{id}";

    /**
     * @var string $requestUri
     */
    private string $requestUri;

    /**
     * @param int $emailId
     */
    public function __construct(int $emailId)
    {
        $this->buildRequestUr($emailId);
    }

    /**
     * Returns the array string representation of current request
     *
     * @return array
     */
    public function toArray(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function getRequestUri(): string
    {
        return $this->requestUri;
    }

    /**
     * Will set the request uri
     *
     * @param int $emailId
     */
    public function buildRequestUr(int $emailId): void
    {
        $this->requestUri = str_replace(self::URI_PARAM_ID, $emailId, self::REQUEST_URI);
    }
}