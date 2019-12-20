<?php

namespace TestMonitor\DoneDone\Transforms;

use TestMonitor\DoneDone\Resources\Priority;

trait TransformsPriorities
{
    /**
     * @param array $priority
     *
     * @return \TestMonitor\DoneDone\Resources\Priority
     */
    protected function fromDoneDonePriority(array $priority): Priority
    {
        return new Priority([
            'id' => $priority['id'],
            'name' => $priority['name'],
        ]);
    }
}
