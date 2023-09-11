<?php

namespace TestMonitor\DoneDone\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use TestMonitor\DoneDone\Client;
use TestMonitor\DoneDone\Resources\Account;
use TestMonitor\DoneDone\Exceptions\Exception;
use TestMonitor\DoneDone\Exceptions\NotFoundException;
use TestMonitor\DoneDone\Exceptions\ValidationException;
use TestMonitor\DoneDone\Exceptions\FailedActionException;
use TestMonitor\DoneDone\Exceptions\UnauthorizedException;

class AccountsTest extends TestCase
{
    protected $account;

    protected function setUp(): void
    {
        parent::setUp();

        $this->account = [
            'id' => '1',
            'name' => 'Account',
        ];
    }

    public function tearDown(): void
    {
        Mockery::close();
    }

    /** @test */
    public function it_should_return_a_list_of_accounts()
    {
        // Given
        $donedone = new Client('email', 'token');

        $donedone->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getBody')->andReturn(\GuzzleHttp\Psr7\Utils::streamFor(json_encode([$this->account])));

        $service->shouldReceive('request')->once()->andReturn($response);

        // When
        $accounts = $donedone->accounts();

        // Then
        $this->assertIsArray($accounts);
        $this->assertCount(1, $accounts);
        $this->assertInstanceOf(Account::class, $accounts[0]);
        $this->assertEquals($this->account['id'], $accounts[0]->id);
        $this->assertIsArray($accounts[0]->toArray());
    }

    /** @test */
    public function it_should_throw_an_failed_action_exception_when_client_receives_bad_request_while_getting_a_list_of_accounts()
    {
        // Given
        $donedone = new Client('email', 'token');

        $donedone->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(400);
        $response->shouldReceive('getBody')->andReturn(\GuzzleHttp\Psr7\Utils::streamFor());

        $this->expectException(FailedActionException::class);

        // When
        $donedone->accounts();
    }

    /** @test */
    public function it_should_throw_a_notfound_exception_when_client_receives_not_found_while_getting_a_list_of_accounts()
    {
        // Given
        $donedone = new Client('email', 'token');

        $donedone->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(404);
        $response->shouldReceive('getBody')->andReturn(\GuzzleHttp\Psr7\Utils::streamFor());

        $this->expectException(NotFoundException::class);

        // When
        $donedone->accounts();
    }

    /** @test */
    public function it_should_throw_a_unauthorized_exception_when_client_lacks_authorization_for_getting_a_list_of_accounts()
    {
        // Given
        $donedone = new Client('email', 'token');

        $donedone->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(401);
        $response->shouldReceive('getBody')->andReturn(\GuzzleHttp\Psr7\Utils::streamFor());

        $this->expectException(UnauthorizedException::class);

        // When
        $donedone->accounts();
    }

    /** @test */
    public function it_should_throw_a_validation_exception_when_client_provides_invalid_data_while_a_getting_list_of_accounts()
    {
        // Given
        $donedone = new Client('email', 'token');

        $donedone->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(422);
        $response->shouldReceive('getBody')->andReturn(\GuzzleHttp\Psr7\Utils::streamFor(json_encode(['message' => 'invalid'])));

        $this->expectException(ValidationException::class);

        // When
        $donedone->accounts();
    }

    /** @test */
    public function it_should_return_an_error_message_when_client_provides_invalid_data_while_a_getting_list_of_accounts()
    {
        // Given
        $donedone = new Client('email', 'token');

        $donedone->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(422);
        $response->shouldReceive('getBody')->andReturn(\GuzzleHttp\Psr7\Utils::streamFor(json_encode(['errors' => ['invalid']])));

        // When
        try {
            $donedone->accounts();
        } catch (ValidationException $exception) {
            // Then
            $this->assertIsArray($exception->errors());
            $this->assertEquals('invalid', $exception->errors()['errors'][0]);
        }
    }

    /** @test */
    public function it_should_throw_a_generic_exception_when_client_suddenly_becomes_a_teapot_while_a_getting_list_of_accounts()
    {
        // Given
        $donedone = new Client('email', 'token');

        $donedone->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(418);
        $response->shouldReceive('getBody')->andReturn(json_encode(['rooibos' => 'anyone?']));

        $this->expectException(Exception::class);

        // When
        $donedone->accounts();
    }
}
