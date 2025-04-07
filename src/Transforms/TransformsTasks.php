<?php

namespace TestMonitor\DoneDone\Transforms;

use TestMonitor\DoneDone\Validator;
use TestMonitor\DoneDone\Resources\Task;

trait TransformsTasks
{
    /**
     * @param \TestMonitor\DoneDone\Resources\Task $task
     * @return array
     */
    protected function toDoneDoneTask(Task $task): array
    {
        return [
            'title' => $task->title,
            'description' => $task->description,
            'statusID' => $task->status,
            'priorityID' => $task->priority,
            'assigneeID' => $task->assignee ?? null,
        ];
    }

    /**
     * @param array $task
     * @return \TestMonitor\DoneDone\Resources\Task
     */
    protected function fromDoneDoneTask($task): Task
    {
        Validator::isArray($task);
        Validator::keysExists($task, ['id', 'refNumber', 'status', 'priority']);

        return new Task([
            'id' => $task['id'],
            'refNumber' => $task['refNumber'],
            'title' => $task['title'] ?? '',
            'description' => $task['text'] ?? '',

            'status' => ['id' => $task['status']['id'], 'name' => $task['status']['name']],
            'priority' => ['id' => $task['priority']['id'], 'name' => $task['priority']['name']],
            'assignee' => ['id' => $task['assignedTo']['id'] ?? null, 'name' => $task['assignedTo']['name'] ?? null],
        ]);
    }
}
