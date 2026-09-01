<?php

namespace App\Services;

use App\Models\Account;
use App\Models\User;

class AccountService
{
    public function create(User $user, array $data): Account
    {
        return $user->accounts()->create($data);
    }

    public function update(Account $account, array $data): Account
    {
        $account->update($data);

        return $account;
    }

    public function delete(Account $account): void
    {
        $account->delete();
    }
}