<?php

namespace TestMonitor\DoneDone\Transforms;

use TestMonitor\DoneDone\Resources\Status;

trait TransformsStatuses
{
    /**
     * @param array $status
     *
     * @return \TestMonitor\DoneDone\Resources\Status
     */
    protected function fromDoneDoneStatus(array $status): Status
    {
        return new Status([
            'id' => $status['id'],
            'name' => $status['name'],
        ]);
    }
}
