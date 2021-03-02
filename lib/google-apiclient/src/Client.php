<?php

namespace PulkitJalan\Google;

use Google_Client;
use Illuminate\Support\Arr;
use PulkitJalan\Google\Exceptions\UnknownServiceException;

class Client
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var \Google_Client
     */
    protected $client;

    /**
     * @param array $config
     * @param string $userEmail
     */
    public function __construct(array $config, $userEmail = '')
    {
        $this->config = $config;

        // create an instance of the google client for OAuth2
        $this->client = new Google_Client(Arr::get($config, 'config', []));

        // set application name
        $this->client->setApplicationName(Arr::get($config, 'application_name', ''));

        // set oauth2 configs
        $this->client->setClientId(Arr::get($config, 'client_id', ''));
        $this->client->setClientSecret(Arr::get($config, 'client_secret', ''));
        $this->client->setRedirectUri(Arr::get($config, 'redirect_uri', ''));
        $this->client->setScopes(Arr::get($config, 'scopes', []));
        $this->client->setAccessType(Arr::get($config, 'access_type', 'online'));
        $this->client->setApprovalPrompt(Arr::get($config, 'approval_prompt', 'auto'));

        // set developer key
        $this->client->setDeveloperKey(Arr::get($config, 'developer_key', ''));

        // auth for service account
        if (Arr::get($config, 'service.enable', false)) {
            $this->auth($userEmail);
        }
    }

    /**
     * Getter for the google client.
     *
     * @return \Google_Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Setter for the google client.
     *
     * @param string $client
     *
     * @return self
     */
    public function setClient(Google_Client $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Getter for the google service.
     *
     * @param string $service
     *
     * @throws \Exception
     *
     * @return \Google_Service
     */
    public function make($service)
    {
        $service = 'Google_Service_'.ucfirst($service);

        if (class_exists($service)) {
            $class = new \ReflectionClass($service);

            return $class->newInstance($this->client);
        }

        throw new UnknownServiceException($service);
    }

    /**
     * Setup correct auth method based on type.
     *
     * @param $userEmail
     * @return void
     */
    protected function auth($userEmail = '')
    {
        // see (and use) if user has set Credentials
        if ($this->useAssertCredentials($userEmail)) {
            return;
        }

        // fallback to compute engine or app engine
        $this->client->useApplicationDefaultCredentials();
    }

    /**
     * Determine and use credentials if user has set them.
     * @param $userEmail
     * @return bool used or not
     */
    protected function useAssertCredentials($userEmail = '')
    {
        $serviceJsonUrl = Arr::get($this->config, 'service.file', '');

        if (empty($serviceJsonUrl)) {
            return false;
        }

        $this->client->setAuthConfig($serviceJsonUrl);

        if (! empty($userEmail)) {
            $this->client->setSubject($userEmail);
        }

        return true;
    }

    /**
     * Magic call method.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @throws \BadMethodCallException
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (method_exists($this->client, $method)) {
            return call_user_func_array([$this->client, $method], array_values($parameters));
        }

        throw new \BadMethodCallException(sprintf('Method [%s] does not exist.', $method));
    }
}
