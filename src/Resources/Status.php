<?php

namespace TestMonitor\DoneDone\Resources;

class Status extends Resource
{
    /**
     * The id of the status.
     *
     * @var int
     */
    public $id;

    /**
     * The name of the status.
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
