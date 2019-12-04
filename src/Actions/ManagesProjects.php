<?php

namespace TestMonitor\DoneDone\Actions;

use TestMonitor\DoneDone\Resources\Project;
use TestMonitor\DoneDone\Transforms\TransformsProjects;

trait ManagesProjects
{
    use TransformsProjects;

    /**
     * Get a list of of projects.
     *
     * @param int $accountId
     * @return Project[]
     */
    public function projects(int $accountId): array
    {
        $result = $this->get("{$accountId}/internal-projects");

        return array_map(function ($project) {
            return $this->fromDoneDoneProject($project);
        }, $result);
    }

    /**
     * Get a single project.
     *
     * @param int $accountId
     * @param int $id
     *
     * @return Project
     */
    public function project(int $accountId, int $id): Project
    {
        $result = $this->get("{$accountId}/internal-projects/{$id}");

        return $this->fromDoneDoneProject($result);
    }
}
