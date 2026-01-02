<?php

namespace Modules\DoubleEntry\Models;

use App\Abstracts\Model;

class Entry extends Model
{
    protected $table = 'double_entry_entries';

    protected $fillable = ['company_id', 'journal_id', 'account_id', 'debit', 'credit'];

    public function journal()
    {
        return $this->belongsTo('Modules\DoubleEntry\Models\Journal', 'journal_id');
    }

    public function account()
    {
        return $this->belongsTo('Modules\DoubleEntry\Models\Account', 'account_id');
    }
}
