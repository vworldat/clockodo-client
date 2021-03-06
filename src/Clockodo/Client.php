<?php

namespace Clockodo;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class Client
{
    const API_BASEURI = 'https://my.clockodo.com/api/';

    /**
     * @var ClientConfig
     */
    protected $config;

    /**
     * Create client instance.
     *
     * @param ClientConfig $config
     */
    public function __construct(ClientConfig $config)
    {
        $this->config = $config;
    }

    /**
     *
     * @param string $method
     * @param string $type
     * @param string $id
     *
     * @return array
     */
    public function callResource($method, $type, $id = null, array $data = [])
    {
        if ('GET' === $method) {
            $uri = $this->buildUri($type, $id, $data);
            $data = [];
        } else {
            $uri = $this->buildUri($type, $id);
        }

        $request = new Request($method, $uri, [
            'X-ClockodoApiUser' => $this->config->getApiUser(),
            'X-ClockodoApiKey' => $this->config->getApiKey(),
        ]);

        try {
            /* @var $response Response */
            $response = $this->getGuzzleClient()->send($request, [
                'form_params' => $data,
            ]);
        } catch (RequestException $e) {
            // TODO: better exception handling
            throw $e;
        }

        $content = $response->getBody();

        return json_decode($content, true);
    }

    /**
     * Issue "GET" HTTP call to the given resource.
     *
     * @param string $type
     * @param string $id
     * @param array  $data
     *
     * @return array JSON data
     */
    public function getResource($type, $id = null, array $data = [])
    {
        return $this->callResource('GET', $type, $id, $data);
    }

    /**
     * Issue "POST" HTTP call to the given resource.
     *
     * @param string $type
     * @param string $id
     * @param array  $data
     *
     * @return array JSON data
     */
    public function postResource($type, $id = null, array $data)
    {
        return $this->callResource('POST', $type, $id, $data);
    }

    /**
     * Issue "DELETE" HTTP call to the given resource.
     *
     * @param string $type
     * @param string $id
     *
     * @return array JSON data
     */
    public function deleteResource($type, $id = null)
    {
        return $this->callResource('DELETE', $type, $id);
    }

    /**
     * Build API URI for the given resource and optional id.
     *
     * @param string $type
     * @param string $id
     * @param array  $params
     *
     * @return string
     */
    protected function buildUri($type, $id = null, array $params = [])
    {
        $uri = static::API_BASEURI.$type;
        if (null !== $id) {
            $uri .= '/'.$id;
        }

        $queryString = http_build_query($params);
        if ($queryString) {
            $uri .= '?'.$queryString;
        }

        return $uri;
    }

    /**
     * @return GuzzleClient
     */
    protected function getGuzzleClient()
    {
        $guzzle = new GuzzleClient();

        return $guzzle;
    }
}
