<?php

namespace App\Utility\Services;

use Request;
use Auth;
use Symfony\Component\HttpFoundation\Response;

class SlackService
{
    protected $client;

    public function __construct(\Maknz\Slack\Client $client = NULL)
    {
        $this->setClient($client);
    }

    public function report($exception)
    {
        $msg = $this->genMsg($exception);

        return $this->isIgnoreException($exception) ? NULL : $this->client->send($this->formatMsg($msg));
    }

    public function isOn()
    {
        return !is_null(env('SLACK_WEB_HOOK', NULL));
    }

    /**
     * Gets the value of client.
     *
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Sets the value of client.
     *
     * @param mixed $client the client
     *
     * @return self
     */
    protected function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    protected function formatMsg($msg)
    {
        return str_replace('\/', '/', urldecode(json_encode($msg, JSON_PRETTY_PRINT)));
    }

    protected function genMsg($exception)
    {
        return [
            'statusCode' => $exception->getStatusCode(),
            'ip'       => Request::ip(),
            'username' => urlencode(Auth::check() ? Auth::user()->username : 'guest'),
            'from'     => env('APP_ENV'),
            'url'      => Request::url(),
            'file'     => $exception->getFile() . ':' . $exception->getLine(),
            'type'     => get_class($exception),
            'message'  => $exception->getMessage()
        ];
    }

    protected function isIgnoreException($exception)
    {
        return in_array($exception->getStatusCode(), [Response::HTTP_FORBIDDEN, Response::HTTP_NOT_FOUND]);
    }
}