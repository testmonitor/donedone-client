<?php

namespace TestMonitor\DoneDone\Transforms;

use TestMonitor\DoneDone\Resources\Project;

trait TransformsProjects
{
    /**
     * @param array $project
     *
     * @return \TestMonitor\DoneDone\Resources\Project
     */
    protected function fromDoneDoneProject(array $project): Project
    {
        return new Project([
            'id' => $project['id'],
            'name' => $project['name'],
        ]);
    }
}
