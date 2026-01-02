<?php

namespace Tests\Unit\Modules\DoubleEntry;

use Tests\TestCase;
use Modules\DoubleEntry\Models\Account;
use Modules\DoubleEntry\Models\Entry;

class AccountBalanceTest extends TestCase
{
    /** @test */
    public function it_calculates_asset_balance_correctly()
    {
        $account = Account::create([
            'company_id' => 1,
            'name' => 'Cash',
            'code' => '1000',
            'type' => 'asset',
            'enabled' => 1,
        ]);

        // Create entries
        Entry::create([
            'company_id' => 1,
            'journal_id' => 1,
            'account_id' => $account->id,
            'debit' => 1000,
            'credit' => 0,
        ]);

        Entry::create([
            'company_id' => 1,
            'journal_id' => 1,
            'account_id' => $account->id,
            'debit' => 0,
            'credit' => 200,
        ]);

        // Asset: Debit - Credit = 1000 - 200 = 800
        $this->assertEquals(800, $account->fresh()->getBalance());
    }

    /** @test */
    public function it_calculates_revenue_balance_correctly()
    {
        $account = Account::create([
            'company_id' => 1,
            'name' => 'Sales Revenue',
            'code' => '4000',
            'type' => 'revenue',
            'enabled' => 1,
        ]);

        Entry::create([
            'company_id' => 1,
            'journal_id' => 1,
            'account_id' => $account->id,
            'debit' => 200,
            'credit' => 0,
        ]);

        Entry::create([
            'company_id' => 1,
            'journal_id' => 1,
            'account_id' => $account->id,
            'debit' => 0,
            'credit' => 1000,
        ]);

        // Revenue: Credit - Debit = 1000 - 200 = 800
        $this->assertEquals(800, $account->fresh()->getBalance());
    }
}
