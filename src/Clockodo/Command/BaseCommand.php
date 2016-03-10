<?php

namespace Clockodo\Command;

use Clockodo\Application\Application;
use Clockodo\Client;
use Clockodo\ClientConfig;
use Clockodo\ConfigFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Clockodo\Api;

/**
 * This command provides an API client for sub commands, asking for credentials if required.
 *
 * @method Application getApplication()
 */
abstract class BaseCommand extends Command
{
    /**
     * @var ClientConfig
     */
    protected $config;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Api
     */
    protected $api;

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $this->detectClientConfig($input, $output);
    }

    protected function detectClientConfig(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);

        $rootDir = $this->getApplication()->getRootDir();
        $configFactory = new ConfigFactory($rootDir);

        if ($configFactory->hasConfig()) {
            $this->config = $configFactory->getConfig();

            return;
        }

        $output->writeln("<info>Could not detect API client config. Please provide your username/email address and API key.</info>");

        $callback = function($value) {
            $value = trim($value);
            if (0 == mb_strlen($value)) {
                throw new \InvalidArgumentException('Invalid value');
            }

            return $value;
        };

        /* @var $helper QuestionHelper */
        $helper = $this->getHelper('question');

        $question = new Question('API user / email address: ');
        $question->setValidator($callback);
        $apiUser = $helper->ask($input, $output, $question);

        $question = new Question('API key: ');
        $question->setValidator($callback);
        $apiKey = $helper->ask($input, $output, $question);

        if ($configFactory->testConfig($apiUser, $apiKey)) {
            $this->config = $configFactory->createConfig($apiUser, $apiKey);
            $output->writeln('<info>Config created successfully</info>');
        } else {
            throw new \RuntimeException('Could not connect to clockodo API using the information you provided. Please try again');
        }
    }

    /**
     * @return Client
     */
    protected function getClient()
    {
        if (null === $this->client) {
            $this->client = new Client($this->config);
        }

        return $this->client;
    }

    /**
     * @return Api
     */
    protected function getApi()
    {
        if (null === $this->api) {
            $this->api = new Api($this->getClient());
        }

        return $this->api;
    }
}
