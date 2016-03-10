<?php

namespace Clockodo;

use Symfony\Component\Yaml\Yaml;
use GuzzleHttp\Exception\RequestException;

class ConfigFactory
{
    protected $rootDir;

    /**
     * @var ClientConfig
     */
    protected $config;

    public function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    /**
     * @return bool
     */
    public function hasConfig()
    {
        $this->detectConfig();

        return null !== $this->config;
    }

    public function getConfig()
    {
        $this->detectConfig();

        return $this->config;
    }

    /**
     * Detect client config credentials.
     */
    protected function detectConfig()
    {
        if (null !== $this->config) {
            return;
        }

        $filename = $this->getFilename();
        if (!file_exists($filename)) {
            return;
        }

        $content = Yaml::parse(file_get_contents($filename));
        if (!is_array($content)) {
            return;
        }
        if (!isset($content['api_user'])) {
            return;
        }
        if (!isset($content['api_key'])) {
            return;
        }

        $this->config = new ClientConfig($content['api_user'], $content['api_key']);
    }

    /**
     * Test the given config values by trying to access the API.
     *
     * @param string $apiUser
     * @param string $apiKey
     *
     * @return bool
     */
    public function testConfig($apiUser, $apiKey)
    {
        $config = new ClientConfig($apiUser, $apiKey);
        $client = new Client($config);

        try {
            $result = $client->getResource('clock');
        } catch (RequestException $e) {
            return false;
        }

        return true;
    }

    /**
     * Create and store client config.
     *
     * @param string $apiUser
     * @param string $apiKey
     *
     * @return ClientConfig
     */
    public function createConfig($apiUser, $apiKey)
    {
        $this->config = new ClientConfig($apiUser, $apiKey);

        $data = [
            'api_user' => $apiUser,
            'api_key' => $apiKey,
        ];

        file_put_contents($this->getFilename(), Yaml::dump($data));

        return $this->config;
    }

    /**
     * Get config filename.
     *
     * @return string
     */
    protected function getFilename()
    {
        return $this->rootDir.'/clockodo.yml';
    }
}
