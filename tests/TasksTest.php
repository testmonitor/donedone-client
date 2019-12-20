<?php

namespace TestMonitor\DoneDone\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use TestMonitor\DoneDone\Client;
use TestMonitor\DoneDone\Resources\Task;
use TestMonitor\DoneDone\Exceptions\NotFoundException;
use TestMonitor\DoneDone\Exceptions\ValidationException;
use TestMonitor\DoneDone\Exceptions\FailedActionException;
use TestMonitor\DoneDone\Exceptions\UnauthorizedException;

class TasksTest extends TestCase
{
    protected $account;

    protected $project;

    protected $task;

    protected function setUp(): void
    {
        parent::setUp();

        $this->account = ['id' => '1', 'name' => 'Account'];

        $this->project = ['id' => 1, 'name' => 'Project'];

        $this->task = [
            'id' => '1',
            'title' => 'Summary',
            'description' => 'Description',
            'status' => ['id' => '1', 'name' => 'Open'],
            'priority' => ['id' => '1', 'name' => 'Low'],
        ];
    }

    public function tearDown(): void
    {
        Mockery::close();
    }

    /** @test */
    public function it_should_return_a_list_of_tasks()
    {
        // Given
        $donedone = new Client('email', 'token');

        $donedone->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getBody')->andReturn(json_encode(['listTasks' => [$this->task]]));

        $service->shouldReceive('request')->once()->andReturn($response);

        // When
        $tasks = $donedone->tasks($this->account['id'], $this->project['id']);

        // Then
        $this->assertIsArray($tasks);
        $this->assertCount(1, $tasks);
        $this->assertInstanceOf(Task::class, $tasks[0]);
        $this->assertEquals($this->task['id'], $tasks[0]->id);
    }

    /** @test */
    public function it_should_throw_an_failed_action_exception_when_client_receives_bad_request_while_getting_a_list_of_tasks()
    {
        // Given
        $donedone = new Client('email', 'token');

        $donedone->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(400);
        $response->shouldReceive('getBody')->andReturnNull();

        $this->expectException(FailedActionException::class);

        // When
        $donedone->tasks($this->account['id'], $this->project['id']);
    }

    /** @test */
    public function it_should_throw_a_notfound_exception_when_client_receives_not_found_while_getting_a_list_of_tasks()
    {
        // Given
        $donedone = new Client('email', 'token');

        $donedone->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(404);
        $response->shouldReceive('getBody')->andReturnNull();

        $this->expectException(NotFoundException::class);

        // When
        $donedone->tasks($this->account['id'], $this->project['id']);
    }

    /** @test */
    public function it_should_throw_a_unauthorized_exception_when_client_lacks_authorization_for_getting_a_list_of_tasks()
    {
        // Given
        $donedone = new Client('email', 'token');

        $donedone->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(401);
        $response->shouldReceive('getBody')->andReturnNull();

        $this->expectException(UnauthorizedException::class);

        // When
        $donedone->tasks($this->account['id'], $this->project['id']);
    }

    /** @test */
    public function it_should_throw_a_validation_exception_when_client_provides_invalid_data_while_a_getting_list_of_tasks()
    {
        // Given
        $donedone = new Client('email', 'token');

        $donedone->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(422);
        $response->shouldReceive('getBody')->andReturn(json_encode(['message' => 'invalid']));

        $this->expectException(ValidationException::class);

        // When
        $donedone->tasks($this->account['id'], $this->project['id']);
    }

    /** @test */
    public function it_should_return_a_single_task()
    {
        // Given
        $donedone = new Client('email', 'token');

        $donedone->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getBody')->andReturn(json_encode($this->task));

        // When
        $task = $donedone->task($this->account['id'], $this->project['id'], $this->task['id']);

        // Then
        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals($this->task['id'], $task->id);
    }

    /** @test */
    public function it_should_create_a_task()
    {
        // Given
        $donedone = new Client('email', 'token');

        $donedone->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->twice()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getBody')->andReturn(json_encode($this->task));

        // When
        $task = $donedone->createTask(
            new Task([
                'title' => $this->task['title'],
                'description' => $this->task['description'],
                'status' => $this->task['status']['id'],
            ]),
            $this->account['id'],
            $this->project['id']
        );

        // Then
        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals($this->task['id'], $task->id);
        $this->assertEquals($this->task['status'], $task->status);
    }
}
