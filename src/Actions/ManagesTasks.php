<?php

namespace TestMonitor\DoneDone\Actions;

use TestMonitor\DoneDone\Resources\Task;
use TestMonitor\DoneDone\Transforms\TransformsTasks;
use TestMonitor\DoneDone\Responses\PaginatedResponse;

trait ManagesTasks
{
    use TransformsTasks;

    /**
     * Get a list of of tasks.
     *
     * @param int $accountId
     * @param int $projectId
     * @param null|string $query
     * @param int $page
     * @return \TestMonitor\DoneDone\Responses\PaginatedResponse
     */
    public function tasks(int $accountId, int $projectId, $query = null, int $page = 1): PaginatedResponse
    {
        $result = $this->get("{$accountId}/tasks/all", [
            'query' => [
                'internal_project_ids' => $projectId,
                'search_term' => $query,
                'page' => $page,
            ],
        ]);

        return new PaginatedResponse(
            array_map(fn ($task) => $this->fromDoneDoneTask($task), $result['listTasks']) ?? [],
            $result['totalTaskCount'],
            $result['itemsPerPage'],
            $result['page'],
        );
    }

    /**
     * Get a single task.
     *
     * @param int $id
     * @param int $accountId
     * @param int $projectId
     * @return Task
     */
    public function task(int $id, int $accountId, int $projectId): Task
    {
        $result = $this->get("{$accountId}/internal-projects/{$projectId}/tasks/{$id}");

        return $this->fromDoneDoneTask($result);
    }

    /**
     * Create a new task.
     *
     * @param \TestMonitor\DoneDone\Resources\Task $task
     * @param int $accountId
     * @param int $projectId
     * @return Task
     */
    public function createTask(Task $task, int $accountId, int $projectId)
    {
        $result = $this->post("{$accountId}/internal-projects/{$projectId}/tasks/", [
            'json' => $this->toDoneDoneTask($task),
        ]);

        return $this->task($result['id'], $accountId, $projectId);
    }
}
