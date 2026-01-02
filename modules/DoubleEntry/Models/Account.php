<?php

namespace Modules\DoubleEntry\Models;

use App\Abstracts\Model;

class Account extends Model
{
    protected $table = 'double_entry_accounts';

    protected $fillable = ['company_id', 'name', 'code', 'type', 'enabled', 'created_by', 'updated_by'];

    public function entries()
    {
        return $this->hasMany('Modules\DoubleEntry\Models\Entry', 'account_id');
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', 1);
    }

    /**
     * Calculate the balance for this account based on its type.
     * Assets & Expenses have debit normal balance (Debits - Credits)
     * Liabilities, Equity, Revenue have credit normal balance (Credits - Debits)
     *
     * @return float
     */
    public function getBalance()
    {
        $debits = $this->entries->sum('debit');
        $credits = $this->entries->sum('credit');

        return in_array($this->type, ['asset', 'expense']) 
            ? $debits - $credits 
            : $credits - $debits;
    }
}
