<?php

namespace TestMonitor\DoneDone\Resources;

class Priority extends Resource
{
    /**
     * The id of the priority.
     *
     * @var int
     */
    public $id;

    /**
     * The name of the priority.
     *
     * @var string
     */
    public $name;

    /**
     * Create a new resource instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->id = $attributes['id'];
        $this->name = $attributes['name'];
    }
}
