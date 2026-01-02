<?php

namespace Modules\DoubleEntry\Jobs;

use App\Abstracts\Job;
use Modules\DoubleEntry\Models\Journal;
use Modules\DoubleEntry\Models\Entry;
use Modules\DoubleEntry\Exceptions\UnbalancedJournalException;
use Modules\DoubleEntry\Constants\Balance;
use Illuminate\Support\Facades\DB;

class CreateJournal extends Job
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        return DB::transaction(function () {
            $journal = Journal::create([
                'company_id' => $this->request['company_id'],
                'date' => $this->request['date'],
                'reference' => $this->request['reference'] ?? null,
                'description' => $this->request['description'] ?? null,
            ]);

            $debits = 0;
            $credits = 0;

            foreach ($this->request['entries'] as $entry_data) {
                $debit = (double) ($entry_data['debit'] ?? 0);
                $credit = (double) ($entry_data['credit'] ?? 0);

                Entry::create([
                    'company_id' => $this->request['company_id'],
                    'journal_id' => $journal->id,
                    'account_id' => $entry_data['account_id'],
                    'debit' => $debit,
                    'credit' => $credit,
                ]);

                $debits += $debit;
                $credits += $credit;
            }

            if (abs($debits - $credits) > Balance::FLOAT_TOLERANCE) {
                throw new UnbalancedJournalException(
                    trans('double-entry::errors.unbalanced_results', [
                        'debits' => number_format($debits, 2),
                        'credits' => number_format($credits, 2)
                    ])
                );
            }

            return $journal;
        });
    }
}
