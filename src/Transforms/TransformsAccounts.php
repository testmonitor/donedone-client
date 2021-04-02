<?php

namespace TestMonitor\DoneDone\Transforms;

use TestMonitor\DoneDone\Validator;
use TestMonitor\DoneDone\Resources\Account;

trait TransformsAccounts
{
    /**
     * @param array $account
     *
     * @return \TestMonitor\DoneDone\Resources\Account
     */
    protected function fromDoneDoneAccount($account): Account
    {
        Validator::isArray($account);
        Validator::keysExists($account, ['id', 'name']);

        return new Account([
            'id' => $account['id'],
            'name' => $account['name'],
        ]);
    }
}
