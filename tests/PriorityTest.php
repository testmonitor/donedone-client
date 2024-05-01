<?php

namespace TestMonitor\DoneDone\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use TestMonitor\DoneDone\Client;
use TestMonitor\DoneDone\Resources\Priority;
use TestMonitor\DoneDone\Exceptions\NotFoundException;
use TestMonitor\DoneDone\Exceptions\ValidationException;
use TestMonitor\DoneDone\Exceptions\FailedActionException;
use TestMonitor\DoneDone\Exceptions\UnauthorizedException;

class PriorityTest extends TestCase
{
    protected $account;

    protected $project;

    protected $priorities;

    protected function setUp(): void
    {
        parent::setUp();

        $this->account = ['id' => '1', 'name' => 'Account'];

        $this->project = ['id' => 1, 'name' => 'Project'];

        $this->priorities = [
            ['id' => 1, 'name' => 'Low'],
            ['id' => 2, 'name' => 'High'],
        ];
    }

    public function tearDown(): void
    {
        Mockery::close();
    }

    /** @test */
    public function it_should_return_a_list_of_priorities()
    {
        // Given
        $donedone = new Client('email', 'token');

        $donedone->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getBody')->andReturn(\GuzzleHttp\Psr7\Utils::streamFor(json_encode($this->priorities)));

        $service->shouldReceive('request')->once()->andReturn($response);

        // When
        $priorities = $donedone->priorities($this->account['id'], $this->project['id']);

        // Then
        $this->assertIsArray($priorities);
        $this->assertCount(2, $priorities);
        $this->assertInstanceOf(Priority::class, $priorities[0]);
        $this->assertEquals($this->priorities[0]['id'], $priorities[0]->id);
    }

    /** @test */
    public function it_should_throw_an_failed_action_exception_when_client_receives_bad_request_while_getting_a_list_of_tasks()
    {
        // Given
        $donedone = new Client('email', 'token');

        $donedone->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(400);
        $response->shouldReceive('getBody')->andReturn(\GuzzleHttp\Psr7\Utils::streamFor());

        $this->expectException(FailedActionException::class);

        // When
        $donedone->priorities($this->account['id'], $this->project['id']);
    }

    /** @test */
    public function it_should_throw_a_notfound_exception_when_client_receives_not_found_while_getting_a_list_of_tasks()
    {
        // Given
        $donedone = new Client('email', 'token');

        $donedone->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(404);
        $response->shouldReceive('getBody')->andReturn(\GuzzleHttp\Psr7\Utils::streamFor());

        $this->expectException(NotFoundException::class);

        // When
        $donedone->priorities($this->account['id'], $this->project['id']);
    }

    /** @test */
    public function it_should_throw_a_unauthorized_exception_when_client_lacks_authorization_for_getting_a_list_of_tasks()
    {
        // Given
        $donedone = new Client('email', 'token');

        $donedone->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(401);
        $response->shouldReceive('getBody')->andReturn(\GuzzleHttp\Psr7\Utils::streamFor());

        $this->expectException(UnauthorizedException::class);

        // When
        $donedone->priorities($this->account['id'], $this->project['id']);
    }

    /** @test */
    public function it_should_throw_a_validation_exception_when_client_provides_invalid_data_while_a_getting_list_of_tasks()
    {
        // Given
        $donedone = new Client('email', 'token');

        $donedone->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(422);
        $response->shouldReceive('getBody')->andReturn(\GuzzleHttp\Psr7\Utils::streamFor(json_encode(['message' => 'invalid'])));

        $this->expectException(ValidationException::class);

        // When
        $donedone->priorities($this->account['id'], $this->project['id']);
    }
}
