<?php


namespace App\Response;


use App\Exception\MalformedJsonException;
use Lukasoppermann\Httpstatus\Httpstatuscodes;

class BaseResponse
{

    const KEY_CODE           = "code";
    const KEY_MESSAGE        = "message";
    const KEY_SUCCESS        = "success";
    const KEY_INVALID_FIELDS = "invalidFields";

    const DEFAULT_CODE    = Httpstatuscodes::HTTP_BAD_REQUEST;
    const DEFAULT_MESSAGE = "Bad request";

    const MESSAGE_BRIDGE_ERROR = "Internal Bridge Error";

    /**
     * @var int $code
     */
    private int $code = self::DEFAULT_CODE;

    /**
     * @var string $message
     */
    private string $message = "";

    /**
     * @var bool $success
     */
    private bool $success = false;

    /**
     * @var array $invalidFields
     */
    private array $invalidFields = [];

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @param bool $success
     */
    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    /**
     * @return array
     */
    public function getInvalidFields(): array
    {
        return $this->invalidFields;
    }

    /**
     * @param array $invalidFields
     */
    public function setInvalidFields(array $invalidFields): void
    {
        $this->invalidFields = $invalidFields;
    }

    /**
     * Will search for given key in array, if nothing is found then default value is returned
     *
     * @param string $searchedKey
     * @param array $arrayToSearchIn
     * @param null $defaultReturnedValue
     * @return mixed|null
     */
    public function getArrayValueByKey(string $searchedKey, array $arrayToSearchIn, $defaultReturnedValue = null)
    {
        if( array_key_exists($searchedKey, $arrayToSearchIn) ){
            return $arrayToSearchIn[$searchedKey];
        }

        return $defaultReturnedValue;
    }

    /**
     * Will prefill the base fields based on the data array from response
     *
     * @param string $json
     * @return BaseResponse
     * @throws MalformedJsonException
     */
    public function prefillBaseFieldsFromJsonString(string $json): self
    {
        $dataArray = $this->jsonToArray($json);

        $code          = $this->getArrayValueByKey(self::KEY_CODE, $dataArray, self::DEFAULT_CODE);
        $message       = $this->getArrayValueByKey(self::KEY_MESSAGE, $dataArray, self::DEFAULT_MESSAGE);
        $success       = $this->getArrayValueByKey(self::KEY_SUCCESS, $dataArray, false);
        $invalidFields = $this->getArrayValueByKey(self::KEY_INVALID_FIELDS, $dataArray, []);

        if( is_string($invalidFields) ){
            $invalidFields = json_decode($invalidFields, true);
        }

        $this->setCode($code);
        $this->setMessage($message);
        $this->setSuccess($success);
        $this->setInvalidFields($invalidFields);

        return $this;
    }

    /**
     * Will build the bridge internal error response
     *
     * @return BaseResponse
     */
    public function prefillInternalBridgeError(): self
    {
        $this->setSuccess(false);
        $this->setCode(Httpstatuscodes::HTTP_INTERNAL_SERVER_ERROR);
        $this->setMessage(self::MESSAGE_BRIDGE_ERROR);

        return $this;
    }

    /**
     * Will transform the json string to corresponding array
     *
     * @param string $json
     * @return mixed
     * @throws MalformedJsonException
     */
    public function jsonToArray(string $json)
    {
        $dataArray     = json_decode($json, true);
        $jsonLastError = json_last_error();

        if( JSON_ERROR_NONE !== $jsonLastError ){
            throw new MalformedJsonException("Provided json is incorrect, reason: " . json_last_error_msg());
        }

        return $dataArray;
    }

    /**
     * Returns the dto in form of json
     *
     * @return string
     */
    public function toJson(): string
    {
        $dataArray = $this->toArray();
        return json_encode($dataArray);
    }

    /**
     * Returns the dto in form of array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::KEY_CODE           => $this->getCode(),
            self::KEY_MESSAGE        => $this->getMessage(),
            self::KEY_SUCCESS        => $this->isSuccess(),
            self::KEY_INVALID_FIELDS => $this->getInvalidFields(),
        ];
    }
}