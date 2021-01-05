<?php

namespace TestMonitor\DoneDone\Actions;

use TestMonitor\DoneDone\Transforms\TransformsStatuses;

trait ManagesStatuses
{
    use TransformsStatuses;

    /**
     * Get a list of of statuses.
     *
     * @param int $accountId
     * @param int $projectId
     *
     * @return \TestMonitor\DoneDone\Resources\Status[]
     */
    public function statuses(int $accountId, int $projectId): array
    {
        $result = $this->get("{$accountId}/internal-projects/{$projectId}/statuses");

        return array_map(function ($status) {
            return $this->fromDoneDoneStatus($status);
        }, $result);
    }
}
