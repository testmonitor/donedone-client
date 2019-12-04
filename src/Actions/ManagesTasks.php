<?php

namespace TestMonitor\DoneDone\Actions;

use TestMonitor\DoneDone\Resources\Task;
use TestMonitor\DoneDone\Transforms\TransformsTasks;

trait ManagesTasks
{
    use TransformsTasks;

    /**
     * Get a list of of tasks.
     *
     * @param int $accountId
     * @param int $projectId
     * @param int $page
     *
     * @return Task[]
     */
    public function tasks(int $accountId, int $projectId, int $page = 1): array
    {
        $result = $this->get("{$accountId}/internal-projects/{$projectId}/tasks", ['page' => $page]);

        return array_map(function ($task) {
            return $this->fromDoneDoneTask($task);
        }, $result['listTasks']);
    }

    /**
     * Get a single task.
     *
     * @param int $id
     * @param int $accountId
     * @param int $projectId
     *
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
     *
     * @return Task
     */
    public function createTask(Task $task, int $accountId, int $projectId)
    {
        $result = $this->post("{$accountId}/internal-projects/{$projectId}/tasks/", [
            'json' => $this->toDoneDoneTask($task),
        ]);

        return $this->fromDoneDoneTask($result);
    }
}
