<?php

namespace Tests\Feature\Modules\DoubleEntry;

use Tests\TestCase;
use Modules\DoubleEntry\Models\Account;
use Modules\DoubleEntry\Models\Journal;
use Modules\DoubleEntry\Models\Entry;
use Modules\DoubleEntry\Jobs\CreateJournal;

class JournalCreationTest extends TestCase
{
    /** @test */
    public function it_creates_balanced_journal_entry()
    {
        // Create test accounts
        $cash = Account::create([
            'company_id' => 1,
            'name' => 'Cash',
            'code' => '1000',
            'type' => 'asset',
            'enabled' => 1,
        ]);

        $revenue = Account::create([
            'company_id' => 1,
            'name' => 'Revenue',
            'code' => '4000',
            'type' => 'revenue',
            'enabled' => 1,
        ]);

        // Create balanced journal
        $data = [
            'company_id' => 1,
            'date' => date('Y-m-d'),
            'reference' => 'TEST-001',
            'description' => 'Test journal entry',
            'entries' => [
                ['account_id' => $cash->id, 'debit' => 100, 'credit' => 0],
                ['account_id' => $revenue->id, 'debit' => 0, 'credit' => 100],
            ],
        ];

        $job = new CreateJournal($data);
        $journal = $job->handle();

        $this->assertInstanceOf(Journal::class, $journal);
        $this->assertTrue($journal->isBalanced());
        $this->assertEquals(2, $journal->entries->count());
    }

    /** @test */
    public function it_rejects_unbalanced_journal_entry()
    {
        $this->expectException(\Exception::class);

        $cash = Account::create([
            'company_id' => 1,
            'name' => 'Cash',
            'code' => '1000',
            'type' => 'asset',
            'enabled' => 1,
        ]);

        $revenue = Account::create([
            'company_id' => 1,
            'name' => 'Revenue',
            'code' => '4000',
            'type' => 'revenue',
            'enabled' => 1,
        ]);

        // Create unbalanced journal (should fail)
        $data = [
            'company_id' => 1,
            'date' => date('Y-m-d'),
            'reference' => 'TEST-002',
            'description' => 'Unbalanced entry',
            'entries' => [
                ['account_id' => $cash->id, 'debit' => 100, 'credit' => 0],
                ['account_id' => $revenue->id, 'debit' => 0, 'credit' => 50], // Unbalanced!
            ],
        ];

        $job = new CreateJournal($data);
        $job->handle(); // Should throw exception
    }
}
