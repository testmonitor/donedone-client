<?php

namespace TestMonitor\DoneDone\Transforms;

use TestMonitor\DoneDone\Validator;
use TestMonitor\DoneDone\Resources\Priority;

trait TransformsPriorities
{
    /**
     * @param array $priority
     * @return \TestMonitor\DoneDone\Resources\Priority
     */
    protected function fromDoneDonePriority($priority): Priority
    {
        Validator::isArray($priority);
        Validator::keysExists($priority, ['id', 'name']);

        return new Priority([
            'id' => $priority['id'],
            'name' => $priority['name'],
        ]);
    }
}
