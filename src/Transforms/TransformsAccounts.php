<?php

namespace TestMonitor\DoneDone\Transforms;

use TestMonitor\DoneDone\Resources\Account;

trait TransformsAccounts
{
    /**
     * @param array $account
     *
     * @return \TestMonitor\DoneDone\Resources\Account
     */
    protected function fromDoneDoneAccount(array $account): Account
    {
        return new Account([
            'id' => $account['id'],
            'name' => $account['name'],
        ]);
    }
}
