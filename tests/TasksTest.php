<?php

namespace TestMonitor\DoneDone\Tests;

use Mockery;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use TestMonitor\DoneDone\Client;
use TestMonitor\DoneDone\Resources\Task;
use TestMonitor\DoneDone\Responses\PaginatedResponse;
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

        $service->shouldReceive('request')
            ->once()
            ->andReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'listTasks' => [$this->task],
                'totalTaskCount' => 1,
                'itemsPerPage' => 50,
                'page' => 1,
            ])));

        // When
        $tasks = $donedone->tasks($this->account['id'], $this->project['id']);

        // Then
        $this->assertInstanceOf(PaginatedResponse::class, $tasks);
        $this->assertIsArray($tasks->items());
        $this->assertCount(1, $tasks->items());
        $this->assertEquals(50, $tasks->perPage());
        $this->assertEquals(1, $tasks->currentPage());
        $this->assertEquals(1, $tasks->total());
        $this->assertInstanceOf(Task::class, $tasks->items()[0]);
        $this->assertEquals($this->task['id'], $tasks->items()[0]->id);
        $this->assertIsArray($tasks->items()[0]->toArray());
    }

    /** @test */
    public function it_should_throw_an_failed_action_exception_when_client_receives_bad_request_while_getting_a_list_of_tasks()
    {
        // Given
        $donedone = new Client('email', 'token');

        $donedone->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')
            ->once()
            ->andReturn(new Response(400, ['Content-Type' => 'application/json'], null));

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

        $service->shouldReceive('request')
            ->once()
            ->andReturn(new Response(404, ['Content-Type' => 'application/json'], null));

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

        $service->shouldReceive('request')
            ->once()
            ->andReturn(new Response(401, ['Content-Type' => 'application/json'], null));

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

        $service->shouldReceive('request')
            ->once()
            ->andReturn(new Response(422, ['Content-Type' => 'application/json'], json_encode(['message' => 'invalid'])));

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

        $service->shouldReceive('request')
            ->once()
            ->andReturn(new Response(200, ['Content-Type' => 'application/json'], json_encode($this->task)));

        // When
        $task = $donedone->task($this->task['id'], $this->account['id'], $this->project['id']);

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

        $service->shouldReceive('request')
            ->twice()
            ->andReturn(new Response(201, ['Content-Type' => 'application/json'], json_encode($this->task)));

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
