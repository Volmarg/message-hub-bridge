<?php

namespace App;

use App\DTO\Mail\MailDTO;
use App\Request\BaseRequest;
use App\Request\Discord\InsertDiscordMessageRequest;
use App\Request\Mail\DirectSendMailRequest;
use App\Request\Mail\GetMailStatusRequest;
use App\Request\Mail\InsertMailRequest;
use App\Response\BaseResponse;
use App\Response\Discord\InsertDiscordMessageResponse;
use App\Response\Mail\DirectSendMailResponse;
use App\Response\Mail\GetMailStatusResponse;
use App\Response\Mail\InsertMailResponse;
use App\Service\GuzzleHttpService;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use App\Service\Jwt\JwtTokenService;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;
use LogicException;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Throwable;
use TypeError;

class MessageHubBridge
{
    private const TOKEN_QUERY_NAME = "token";

    const WEBHOOK_NAME_ALL_NOTIFICATIONS = "allNotifications";
    const SOURCE_PMS                     = "PMS";
    const SOURCE_CIR                     = "CIR";

    /**
     * @var GuzzleHttpService $guzzleHttpService
     */
    private GuzzleHttpService $guzzleHttpService;

    /**
     * @var string $baseUrl
     */
    private string $baseUrl;

    /**
     * @var Logger $logger
     */
    private Logger $logger;

    public function __construct(
        private readonly JwtTokenService $jwtTokenService,
        string                           $logFilePath,
        string                           $loggerName,
        string                           $baseUrl
    )
    {
        $this->baseUrl           = $baseUrl;
        $this->guzzleHttpService = new GuzzleHttpService();
        $this->logger            = new Logger($loggerName);
        $this->logger->pushHandler(new RotatingFileHandler($logFilePath, 5, Logger::DEBUG));
    }

    /**
     * Will call the MH to insert the discord message
     *
     * @param InsertDiscordMessageRequest $request
     * @return InsertDiscordMessageResponse
     * @throws GuzzleException
     */
    public function insertDiscordMessage(InsertDiscordMessageRequest $request): InsertDiscordMessageResponse
    {
        $response = new InsertDiscordMessageResponse();
        try{
            $this->logCalledApiMethod($request);
            {
                $absoluteCalledUrl = $this->buildAbsoluteCalledUrlForRequest($request);
                $guzzleResponse    = $this->guzzleHttpService->sendPostRequest($absoluteCalledUrl, $request->toArray());

                $response->prefillBaseFieldsFromJsonString($guzzleResponse);
            }
            $this->logResponse($response);
        }catch(Exception | TypeError $e){
            $this->logThrowable($e);
            return $response->prefillInternalBridgeError();
        }

        return $response;
    }

    /**
     * Will call the MH to insert the mail
     *
     * @param InsertMailRequest $request
     * @return InsertMailResponse
     * @throws GuzzleException
     */
    public function insertMail(InsertMailRequest $request): InsertMailResponse
    {
        $response = new InsertMailResponse();
        try{
            $attachedFilesNames = [];
            foreach($request->getMailDto()->getAttachments() as $fileName => $fileContent){
                if( in_array($fileName, $attachedFilesNames) ){
                    throw new LogicException("Attachments names must be unique. This one is not unique: {$fileName}");
                }
                $attachedFilesNames[] = $fileName;
            }

            /**
             * The project is able to handle multiple `to` emails, but it will take way too much time to handle
             * the "state links" handling for all the emails etc. So this is only added to reduce to work needed for now.
             */
            if (!$request->getMailDto()->assertMaxToEmails()) {
                $message = $request->getMailDto()::MAX_TO_EMAILS . " `to` E-mail/s allowed, got: " . $request->getMailDto()->countToEmails() . " E-mail/s.";
                throw new LogicException($message);
            }

            if( !in_array($request->getMailDto()->getEmailType(), MailDTO::ALLOWED_TYPES) ){
                throw new LogicException("Provided E-Mail type is not supported: {$request->getMailDto()->getEmailType()}. Allowed are:" . json_encode(MailDTO::ALLOWED_TYPES));
            }

            $this->logCalledApiMethod($request);
            {
                $absoluteCalledUrl = $this->buildAbsoluteCalledUrlForRequest($request);
                $guzzleResponse    = $this->guzzleHttpService->sendPostRequest($absoluteCalledUrl, $request->toArray());

                $response->prefillBaseFieldsFromJsonString($guzzleResponse);
            }
            $this->logResponse($response);
        }catch(Exception | TypeError $e){
            $this->logThrowable($e);
            return $response->prefillInternalBridgeError();
        }

        return $response;
    }

    /**
     * Will call the MH to direct-send the mail
     *
     * @param DirectSendMailRequest $request
     *
     * @return DirectSendMailResponse
     * @throws GuzzleException
     */
    public function directSendMail(DirectSendMailRequest $request): DirectSendMailResponse
    {
        $response = new DirectSendMailResponse();
        try{
            $attachedFilesNames = [];
            foreach ($request->getMailDto()->getAttachments() as $fileName => $fileContent) {
                if( in_array($fileName, $attachedFilesNames) ){
                    throw new LogicException("Attachments names must be unique. This one is not unique: {$fileName}");
                }
                $attachedFilesNames[] = $fileName;
            }

            /**
             * The project is able to handle multiple `to` emails, but it will take way too much time to handle
             * the "state links" handling for all the emails etc. So this is only added to reduce to work needed for now.
             */
            if (!$request->getMailDto()->assertMaxToEmails()) {
                $message = $request->getMailDto()::MAX_TO_EMAILS . " `to` E-mail/s allowed, got: " . $request->getMailDto()->countToEmails() . " E-mail/s.";
                throw new LogicException($message);
            }

            if( !in_array($request->getMailDto()->getEmailType(), MailDTO::ALLOWED_TYPES) ){
                throw new LogicException("Provided E-Mail type is not supported: {$request->getMailDto()->getEmailType()}. Allowed are:" . json_encode(MailDTO::ALLOWED_TYPES));
            }

            $this->logCalledApiMethod($request);
            {
                $absoluteCalledUrl = $this->buildAbsoluteCalledUrlForRequest($request);
                $guzzleResponse    = $this->guzzleHttpService->sendPostRequest($absoluteCalledUrl, $request->toArray());

                $response->prefillBaseFieldsFromJsonString($guzzleResponse);
            }
            $this->logResponse($response);
        }catch(Exception | TypeError $e){
            $this->logThrowable($e);
            return $response->prefillInternalBridgeError();
        }

        return $response;
    }

    /**
     * Will call the MH to get mail status
     *
     * @param GetMailStatusRequest $request
     * @return GetMailStatusResponse
     * @throws GuzzleException
     */
    public function getMailStatus(GetMailStatusRequest $request): GetMailStatusResponse
    {
        $response = new GetMailStatusResponse();
        try{
            $this->logCalledApiMethod($request);
            {
                $absoluteCalledUrl = $this->buildAbsoluteCalledUrlForRequest($request);
                $guzzleResponse    = $this->guzzleHttpService->sendGetRequest($absoluteCalledUrl);

                $response->prefillBaseFieldsFromJsonString($guzzleResponse);
            }
            $this->logResponse($response);
        }catch(Exception | TypeError $e){
            $this->logThrowable($e);
            return $response->prefillInternalBridgeError();
        }

        return $response;
    }

    /**
     * Will return the absolute url to be called by guzzle
     *
     * @param BaseRequest $request
     *
     * @return string
     *
     * @throws JWTEncodeFailureException
     */
    public function buildAbsoluteCalledUrlForRequest(BaseRequest $request): string
    {
        $jwtToken = $this->jwtTokenService->encode();

        $outputUrl = $this->baseUrl;
        if (!str_ends_with($outputUrl, DIRECTORY_SEPARATOR)) {
            $outputUrl .= DIRECTORY_SEPARATOR;
        }

        return $outputUrl . $request->getRequestUri() . "?" . self::TOKEN_QUERY_NAME . "=" . $jwtToken;
    }

    /**
     * @param Throwable $e
     */
    private function logThrowable(Throwable $e): void
    {

        $this->logger->critical("Exception was thrown", [
            "message" => $e->getMessage(),
            "code"    => $e->getCode(),
            "trace"   => $e->getTraceAsString(),
        ]);
    }

    /**
     * Will log information about current api call
     *
     * @param BaseRequest $request
     */
    private function logCalledApiMethod(BaseRequest $request): void
    {
        $this->logger->info("Now calling api: ", [
            "calledMethod"  => debug_backtrace()[1]['function'], // need to use backtrace to get the correct calling method
            "baseUrl"       => $this->baseUrl,
            "requestUri"    => $request->getRequestUri(),
            "dataBag"       => "Not logged, is to big, will bloat the log",
        ]);
    }

    /**
     * Will log the response data
     *
     * @param BaseResponse $response
     */
    private function logResponse(BaseResponse $response): void
    {
        $this->logger->info("Got response from called endpoint", [
            "response" => $response->toJson(),
        ]);
    }

}