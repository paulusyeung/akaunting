<?php

namespace Modules\DoubleEntry\Observers;

use Modules\DoubleEntry\Models\Account;

class AccountObserver
{
    /**
     * Handle the Account "creating" event.
     *
     * @param  \Modules\DoubleEntry\Models\Account  $account
     * @return void
     */
    public function creating(Account $account)
    {
        $account->created_by = user_id();
    }

    /**
     * Handle the Account "updating" event.
     *
     * @param  \Modules\DoubleEntry\Models\Account  $account
     * @return void
     */
    public function updating(Account $account)
    {
        $account->updated_by = user_id();
    }
}
