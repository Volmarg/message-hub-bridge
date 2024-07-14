<?php


namespace App\Request;

/**
 * Info: the logic for validation is not used at this point, was only prepared.
 *
 * Class BaseRequest
 * @package App\Request
 */
abstract class BaseRequest
{
    /**
     * @var string $login
     */
    private string $login = "";

    /**
     * @var string $password
     */
    private string $password = "";

    /**
     * Request Uri to be called
     *
     * @return string
     */
    public abstract function getRequestUri(): string;

    /**
     * @return array
     */
    public abstract function toArray(): array;

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

}