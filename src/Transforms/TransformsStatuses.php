<?php

namespace TestMonitor\DoneDone\Transforms;

use TestMonitor\DoneDone\Validator;
use TestMonitor\DoneDone\Resources\Status;

trait TransformsStatuses
{
    /**
     * @param array $status
     * @return \TestMonitor\DoneDone\Resources\Status
     */
    protected function fromDoneDoneStatus($status): Status
    {
        Validator::isArray($status);
        Validator::keysExists($status, ['id', 'name']);

        return new Status([
            'id' => $status['id'],
            'name' => $status['name'],
        ]);
    }
}
