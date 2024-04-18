<?php

namespace TestMonitor\DoneDone;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\ResponseInterface;
use TestMonitor\DoneDone\Exceptions\Exception;
use TestMonitor\DoneDone\Exceptions\NotFoundException;
use TestMonitor\DoneDone\Exceptions\ValidationException;
use TestMonitor\DoneDone\Exceptions\FailedActionException;
use TestMonitor\DoneDone\Exceptions\UnauthorizedException;

class Client
{
    use Actions\ManagesAccounts,
        Actions\ManagesPriorities,
        Actions\ManagesProjects,
        Actions\ManagesStatuses,
        Actions\ManagesTasks;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Create a new client instance.
     *
     * @param string $username
     * @param string $token
     */
    public function __construct(string $username, string $token)
    {
        $this->username = $username;
        $this->token = $token;
    }

    /**
     * Returns an DoneDone client instance.
     *
     * @return \GuzzleHttp\Client
     */
    protected function client()
    {
        return $this->client ?? new GuzzleClient([
            'auth' => [
                $this->username,
                $this->token,
            ],
            'http_errors' => false,
            'base_uri' => 'https://2.donedone.com/public-api/',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json; charset=utf-8',
            ],
        ]);
    }

    /**
     * @param \GuzzleHttp\Client $client
     */
    public function setClient(GuzzleClient $client)
    {
        $this->client = $client;
    }

    /**
     * Make a GET request to DoneDone servers and return the response.
     *
     * @param string $uri
     * @param array $payload
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \TestMonitor\DoneDone\Exceptions\FailedActionException
     * @throws \TestMonitor\DoneDone\Exceptions\NotFoundException
     * @throws \TestMonitor\DoneDone\Exceptions\ValidationException
     *
     * @return mixed
     */
    protected function get($uri, array $payload = [])
    {
        return $this->request('GET', $uri, $payload);
    }

    /**
     * Make a POST request to DoneDone servers and return the response.
     *
     * @param string $uri
     * @param array $payload
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \TestMonitor\DoneDone\Exceptions\FailedActionException
     * @throws \TestMonitor\DoneDone\Exceptions\NotFoundException
     * @throws \TestMonitor\DoneDone\Exceptions\ValidationException
     *
     * @return mixed
     */
    protected function post($uri, array $payload = [])
    {
        return $this->request('POST', $uri, $payload);
    }

    /**
     * Make request to DoneDone servers and return the response.
     *
     * @param string $verb
     * @param string $uri
     * @param array $payload
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \TestMonitor\DoneDone\Exceptions\FailedActionException
     * @throws \TestMonitor\DoneDone\Exceptions\NotFoundException
     * @throws \TestMonitor\DoneDone\Exceptions\ValidationException
     *
     * @return mixed
     */
    protected function request($verb, $uri, array $payload = [])
    {
        $response = $this->client()->request(
            $verb,
            $uri,
            $payload
        );

        if (! in_array($response->getStatusCode(), [200, 201, 204, 206])) {
            return $this->handleRequestError($response);
        }

        $responseBody = (string) $response->getBody();

        return json_decode($responseBody, true) ?: $responseBody;
    }

    /**
     * @param  \Psr\Http\Message\ResponseInterface $response
     *
     * @throws \TestMonitor\DoneDone\Exceptions\ValidationException
     * @throws \TestMonitor\DoneDone\Exceptions\NotFoundException
     * @throws \TestMonitor\DoneDone\Exceptions\FailedActionException
     * @throws \Exception
     *
     * @return void
     */
    protected function handleRequestError(ResponseInterface $response)
    {
        if ($response->getStatusCode() == 422) {
            throw new ValidationException(json_decode((string) $response->getBody(), true));
        }

        if ($response->getStatusCode() == 404) {
            throw new NotFoundException();
        }

        if (in_array($response->getStatusCode(), [401, 402, 403])) {
            throw new UnauthorizedException((string) $response->getBody());
        }

        if ($response->getStatusCode() == 400) {
            throw new FailedActionException((string) $response->getBody());
        }

        throw new Exception((string) $response->getStatusCode());
    }
}
