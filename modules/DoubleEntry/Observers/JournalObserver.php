<?php

namespace Modules\DoubleEntry\Observers;

use Modules\DoubleEntry\Models\Journal;

class JournalObserver
{
    /**
     * Handle the Journal "creating" event.
     *
     * @param  \Modules\DoubleEntry\Models\Journal  $journal
     * @return void
     */
    public function creating(Journal $journal)
    {
        $journal->created_by = user_id();
    }

    /**
     * Handle the Journal "updating" event.
     *
     * @param  \Modules\DoubleEntry\Models\Journal  $journal
     * @return void
     */
    public function updating(Journal $journal)
    {
        $journal->updated_by = user_id();
    }
}
