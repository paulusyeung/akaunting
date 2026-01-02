<?php

namespace Modules\DoubleEntry\Models;

use App\Abstracts\Model;
use Modules\DoubleEntry\Constants\Balance;

class Journal extends Model
{
    protected $table = 'double_entry_journals';

    protected $fillable = ['company_id', 'date', 'reference', 'description', 'created_by', 'updated_by'];

    public function entries()
    {
        return $this->hasMany('Modules\DoubleEntry\Models\Entry', 'journal_id');
    }

    /**
     * Check if the journal entry is balanced.
     *
     * @return bool
     */
    public function isBalanced()
    {
        $debits = $this->entries->sum('debit');
        $credits = $this->entries->sum('credit');

        return abs($debits - $credits) < Balance::FLOAT_TOLERANCE;
    }
}
