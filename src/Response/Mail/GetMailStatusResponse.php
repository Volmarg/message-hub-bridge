<?php

namespace App\Response\Mail;

use App\Exception\MalformedJsonException;
use App\Exception\MalformedResponseException;
use App\Response\BaseResponse;

/**
 * Class GetMailStatusResponse
 * @package App\Response\Mail
 */
class GetMailStatusResponse extends BaseResponse
{
    private const STATUS_SENT    = "SENT";
    private const STATUS_PENDING = "PENDING";
    private const STATUS_ERROR   = "ERROR";

    private const KEY_STATUS = "status";

    /**
     * @var string $status
     */
    private string $status;

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return bool
     */
    public function isSent(): bool
    {
        return $this->getStatus() === self::STATUS_SENT;
    }

    /**
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->getStatus() === self::STATUS_PENDING;
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {
        return $this->getStatus() === self::STATUS_ERROR;
    }

    /**
     * Will prefill the base fields based on the data array from response
     *
     * @param string $json
     *
     * @return GetMailStatusResponse
     * @throws MalformedJsonException
     * @throws MalformedResponseException
     */
    public function prefillBaseFieldsFromJsonString(string $json): self
    {
        $dataArray    = $this->jsonToArray($json);
        $prefilledDto = parent::prefillBaseFieldsFromJsonString($json);

        $status = $this->getArrayValueByKey(self::KEY_STATUS, $dataArray);
        if( empty($status) ){
            throw new MalformedResponseException("E-Mail sending status is missing in response");
        }
        $prefilledDto->setStatus($status);

        return $prefilledDto;
    }
}