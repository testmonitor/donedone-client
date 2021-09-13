<?php

namespace TestMonitor\DoneDone\Actions;

use TestMonitor\DoneDone\Transforms\TransformsPriorities;

trait ManagesPriorities
{
    use TransformsPriorities;

    /**
     * Get a list of of priorities.
     *
     * @param int $accountId
     * @param int $projectId
     * @return \TestMonitor\DoneDone\Resources\Priority[]
     */
    public function priorities(int $accountId, int $projectId): array
    {
        $result = $this->get("{$accountId}/internal-projects/{$projectId}/priorities");

        return array_map(function ($priority) {
            return $this->fromDoneDonePriority($priority);
        }, $result);
    }
}
