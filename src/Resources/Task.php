<?php

namespace TestMonitor\DoneDone\Resources;

class Task extends Resource
{
    /**
     * The id of the issue.
     *
     * @var string
     */
    public $id;

    /**
     * The reference number of the issue.
     *
     * @var string
     */
    public $refNumber;

    /**
     * The title of the task.
     *
     * @var string
     */
    public $title;

    /**
     * The description of the task.
     *
     * @var string
     */
    public $description;

    /**
     * The task status.
     *
     * @var array
     */
    public $status;

    /**
     * The task priority.
     *
     * @var array
     */
    public $priority;

    /**
     * The task assignee.
     *
     * @var array
     */
    public $assignee;

    /**
     * Create a new resource instance.
     *
     * @param $attributes
     */
    public function __construct(array $attributes)
    {
        $this->id = $attributes['id'] ?? null;
        $this->refNumber = $attributes['refNumber'] ?? null;
        $this->title = $attributes['title'];
        $this->description = $attributes['description'];
        $this->status = $attributes['status'];
        $this->priority = $attributes['priority'] ?? null;
        $this->assignee = $attributes['assignee'] ?? null;
    }
}
