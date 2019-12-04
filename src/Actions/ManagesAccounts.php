<?php

namespace TestMonitor\DoneDone\Actions;

use TestMonitor\DoneDone\Transforms\TransformsAccounts;

trait ManagesAccounts
{
    use TransformsAccounts;

    /**
     * Get a list of of accounts.
     *
     * @return \TestMonitor\DoneDone\Resources\Account[]
     */
    public function accounts(): array
    {
        $result = $this->get('accounts');

        return array_map(function ($project) {
            return $this->fromDoneDoneAccount($project);
        }, $result);
    }
}
