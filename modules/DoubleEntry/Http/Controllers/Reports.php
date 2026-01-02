<?php

namespace Modules\DoubleEntry\Http\Controllers;

use App\Abstracts\Http\Controller;
use Modules\DoubleEntry\Models\Account;

class Reports extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-double-entry-reports');
    }

    public function ledger()
    {
        $accounts = Account::companyId(company_id())
            ->with(['entries.journal'])
            ->get();

        $ledger = $accounts->map(function ($account) {
            $debits = $account->entries->sum('debit');
            $credits = $account->entries->sum('credit');
            
            // Different account types have different normal balances
            // Assets & Expenses: Debit balance (Debits - Credits)
            // Liabilities, Equity, Revenue: Credit balance (Credits - Debits)
            $balance = in_array($account->type, ['asset', 'expense']) 
                ? $debits - $credits 
                : $credits - $debits;
            
            return [
                'name'    => $account->name,
                'code'    => $account->code,
                'type'    => $account->type,
                'debit'   => $debits,
                'credit'  => $credits,
                'balance' => $balance,
            ];
        });

        return view('double-entry::reports.ledger', compact('ledger'));
    }

    public function trialBalance()
    {
        $accounts = Account::companyId(company_id())
            ->with(['entries.journal'])
            ->get();

        $trial_balance = [];
        $total_debits = 0;
        $total_credits = 0;

        foreach ($accounts as $account) {
            $debits = $account->entries->sum('debit');
            $credits = $account->entries->sum('credit');
            
            // Calculate balance based on account type
            $balance = in_array($account->type, ['asset', 'expense']) 
                ? $debits - $credits 
                : $credits - $debits;

            // Trial balance shows debit or credit balance
            $debit_balance = $balance > 0 ? $balance : 0;
            $credit_balance = $balance < 0 ? abs($balance) : 0;

            $trial_balance[] = [
                'code'    => $account->code,
                'name'    => $account->name,
                'type'    => $account->type,
                'debit'   => $debit_balance,
                'credit'  => $credit_balance,
            ];

            $total_debits += $debit_balance;
            $total_credits += $credit_balance;
        }

        return view('double-entry::reports.trial-balance', compact('trial_balance', 'total_debits', 'total_credits'));
    }

    public function exportLedger()
    {
        $accounts = Account::companyId(company_id())
            ->with(['entries.journal'])
            ->get();

        $csv_data = "Code,Name,Type,Debits,Credits,Balance\n";

        foreach ($accounts as $account) {
            $debits = $account->entries->sum('debit');
            $credits = $account->entries->sum('credit');
            
            $balance = in_array($account->type, ['asset', 'expense']) 
                ? $debits - $credits 
                : $credits - $debits;

            $csv_data .= sprintf(
                "%s,%s,%s,%.2f,%.2f,%.2f\n",
                $account->code,
                $account->name,
                $account->type,
                $debits,
                $credits,
                $balance
            );
        }

        $filename = 'general_ledger_' . date('Y-m-d') . '.csv';

        return response($csv_data)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    public function incomeStatement()
    {
        $start_date = request('start_date', date('Y-01-01'));
        $end_date = request('end_date', date('Y-12-31'));
        
        // Get revenue accounts (type = 'revenue')
        $revenue_accounts = Account::companyId(company_id())
            ->where('type', 'revenue')
            ->with(['entries' => function($query) use ($start_date, $end_date) {
                $query->whereHas('journal', function($q) use ($start_date, $end_date) {
                    $q->whereBetween('date', [$start_date, $end_date]);
                });
            }, 'entries.journal'])
            ->get();
        
        // Get expense accounts (type = 'expense')
        $expense_accounts = Account::companyId(company_id())
            ->where('type', 'expense')
            ->with(['entries' => function($query) use ($start_date, $end_date) {
                $query->whereHas('journal', function($q) use ($start_date, $end_date) {
                    $q->whereBetween('date', [$start_date, $end_date]);
                });
            }, 'entries.journal'])
            ->get();
        
        // Calculate totals
        $total_revenue = 0;
        $revenue_data = [];
        
        foreach ($revenue_accounts as $account) {
            $balance = $account->getBalance();
            if ($balance != 0) {
                $revenue_data[] = [
                    'code' => $account->code,
                    'name' => $account->name,
                    'amount' => $balance,
                ];
                $total_revenue += $balance;
            }
        }
        
        $total_expenses = 0;
        $expense_data = [];
        
        foreach ($expense_accounts as $account) {
            $balance = $account->getBalance();
            if ($balance != 0) {
                $expense_data[] = [
                    'code' => $account->code,
                    'name' => $account->name,
                    'amount' => $balance,
                ];
                $total_expenses += $balance;
            }
        }
        
        $net_income = $total_revenue - $total_expenses;
        
        return view('double-entry::reports.income-statement', compact(
            'revenue_data',
            'expense_data',
            'total_revenue',
            'total_expenses',
            'net_income',
            'start_date',
            'end_date'
        ));
    }

    public function balanceSheet()
    {
        $as_of_date = request('as_of_date', date('Y-12-31'));
        
        // Get asset accounts
        $asset_accounts = Account::companyId(company_id())
            ->where('type', 'asset')
            ->with(['entries' => function($query) use ($as_of_date) {
                $query->whereHas('journal', function($q) use ($as_of_date) {
                    $q->where('date', '<=', $as_of_date);
                });
            }, 'entries.journal'])
            ->get();
        
        // Get liability accounts
        $liability_accounts = Account::companyId(company_id())
            ->where('type', 'liability')
            ->with(['entries' => function($query) use ($as_of_date) {
                $query->whereHas('journal', function($q) use ($as_of_date) {
                    $q->where('date', '<=', $as_of_date);
                });
            }, 'entries.journal'])
            ->get();
        
        // Get equity accounts
        $equity_accounts = Account::companyId(company_id())
            ->where('type', 'equity')
            ->with(['entries' => function($query) use ($as_of_date) {
                $query->whereHas('journal', function($q) use ($as_of_date) {
                    $q->where('date', '<=', $as_of_date);
                });
            }, 'entries.journal'])
            ->get();
        
        // Calculate totals
        $total_assets = 0;
        $asset_data = [];
        
        foreach ($asset_accounts as $account) {
            $balance = $account->getBalance();
            if ($balance != 0) {
                $asset_data[] = [
                    'code' => $account->code,
                    'name' => $account->name,
                    'amount' => $balance,
                ];
                $total_assets += $balance;
            }
        }
        
        $total_liabilities = 0;
        $liability_data = [];
        
        foreach ($liability_accounts as $account) {
            $balance = $account->getBalance();
            if ($balance != 0) {
                $liability_data[] = [
                    'code' => $account->code,
                    'name' => $account->name,
                    'amount' => $balance,
                ];
                $total_liabilities += $balance;
            }
        }
        
        $total_equity = 0;
        $equity_data = [];
        
        foreach ($equity_accounts as $account) {
            $balance = $account->getBalance();
            if ($balance != 0) {
                $equity_data[] = [
                    'code' => $account->code,
                    'name' => $account->name,
                    'amount' => $balance,
                ];
                $total_equity += $balance;
            }
        }
        
        // Calculate net income for the period (to be added to equity)
        $revenue_total = Account::companyId(company_id())
            ->where('type', 'revenue')
            ->with(['entries' => function($query) use ($as_of_date) {
                $query->whereHas('journal', function($q) use ($as_of_date) {
                    $q->where('date', '<=', $as_of_date);
                });
            }, 'entries.journal'])
            ->get()
            ->sum(function($account) {
                return $account->getBalance();
            });
        
        $expense_total = Account::companyId(company_id())
            ->where('type', 'expense')
            ->with(['entries' => function($query) use ($as_of_date) {
                $query->whereHas('journal', function($q) use ($as_of_date) {
                    $q->where('date', '<=', $as_of_date);
                });
            }, 'entries.journal'])
            ->get()
            ->sum(function($account) {
                return $account->getBalance();
            });
        
        $net_income = $revenue_total - $expense_total;
        
        // Add net income to equity
        if ($net_income != 0) {
            $equity_data[] = [
                'code' => '',
                'name' => trans('double-entry::reports.net_income'),
                'amount' => $net_income,
            ];
            $total_equity += $net_income;
        }
        
        $total_liabilities_equity = $total_liabilities + $total_equity;
        
        return view('double-entry::reports.balance-sheet', compact(
            'asset_data',
            'liability_data',
            'equity_data',
            'total_assets',
            'total_liabilities',
            'total_equity',
            'total_liabilities_equity',
            'net_income',
            'as_of_date'
        ));
    }
}
