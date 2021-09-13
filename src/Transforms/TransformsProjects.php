<?php

namespace TestMonitor\DoneDone\Transforms;

use TestMonitor\DoneDone\Validator;
use TestMonitor\DoneDone\Resources\Project;

trait TransformsProjects
{
    /**
     * @param array $project
     * @return \TestMonitor\DoneDone\Resources\Project
     */
    protected function fromDoneDoneProject($project): Project
    {
        Validator::isArray($project);
        Validator::keysExists($project, ['id', 'name']);

        return new Project([
            'id' => $project['id'],
            'name' => $project['name'],
        ]);
    }
}
