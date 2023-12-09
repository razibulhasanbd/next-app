<?php

namespace App\Services\Notification\OneSignal;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request as Psr7Request;
use GuzzleHttp\Psr7\Response as Psr7Response;

class OneSignalBase
{
    const API_URL = "";

    const ENDPOINT_NOTIFICATIONS = "/notifications";
    const ENDPOINT_PLAYERS = "/players";
    const ENDPOINT_APPS = "/apps";

    protected $client;
    protected $headers;
    protected $appId;
    protected $restApiKey;
    protected $additionalParams;
    public $apiUrl;

    /**
     * @var bool
     */
    public $requestAsync = false;

    /**
     * @var int
     */
    public $maxRetries = 2;

    /**
     * @var int
     */
    public $retryDelay = 500;

    /**
     * @var Callable
     */
    public $requestCallback;

    /**
     * Turn on, turn off async requests
     *
     * @param bool $on
     * @return $this
     */
    public function async($on = true)
    {
        $this->requestAsync = $on;
        return $this;
    }

    /**
     * Callback to execute after OneSignal returns the response
     * @param Callable $requestCallback
     * @return $this
     */
    public function callback(Callable $requestCallback)
    {
        $this->requestCallback = $requestCallback;
        return $this;
    }

    /**
     * @param $appId
     * @param $restApiKey
     * @param $userAuthKey
     * @param int $guzzleClientTimeout
     */
    public function __construct()
    {


        $this->apiUrl = config('onesignal.api_url') ."/api/v1";
        $this->appId = config('onesignal.app_id');
        $this->restApiKey = config('onesignal.api_key');
        $guzzleClientTimeout = config('onesignal.guzzle_client_timeout');

        $this->client = new Client([
            'handler' => $this->createGuzzleHandler(),
            'timeout' => $guzzleClientTimeout,
        ]);
        $this->headers = ['headers' => []];
        $this->additionalParams = [];
        $this->requiresAuth();
        $this->usesJSON();
    }

    public function createGuzzleHandler() {
        return tap(HandlerStack::create(new CurlHandler()), function (HandlerStack $handlerStack) {
            $handlerStack->push(Middleware::retry(function ($retries, Psr7Request $request, Psr7Response $response = null, RequestException $exception = null) {
                if ($retries >= $this->maxRetries) {
                    return false;
                }

                if ($exception instanceof ConnectException) {
                    return true;
                }

                if ($response && $response->getStatusCode() >= 500) {
                    return true;
                }

                return false;
            }), $this->retryDelay);
        });
    }

    public function requiresAuth() {
        $this->headers['headers']['Authorization'] = 'Basic '.$this->restApiKey;
    }


    public function usesJSON() {
        $this->headers['headers']['Content-Type'] = 'application/json';
    }

    public function addParams($params = [])
    {
        $this->additionalParams = $params;

        return $this;
    }

    public function setParam($key, $value)
    {
        $this->additionalParams[$key] = $value;

        return $this;
    }

    /**
     * @return string[]
     */
    public function successResponse($msg = '', $data = []): array
    {
        return [
          'status' => 'success',
          'message' => $msg ?? 'Successfully Sent',
          'data' => $data ?? [],
        ];
    }

    /**
     * @return string[]
     */
    public function errorResponse($msg = '') : array
    {
        return [
            'status' => 'error',
            'message' => $msg ?? 'Sending Failed',
        ];
    }
}
