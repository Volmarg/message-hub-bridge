<?php

namespace App\Exception;

/**
 * Indicates that the response from the tool has been received but something went wrong in it, and it delivered
 * some strange, malformed response
 */
class MalformedResponseException extends \Exception
{

}