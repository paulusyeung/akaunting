<?php

namespace Modules\DoubleEntry\Http\Controllers;

use App\Abstracts\Http\Controller;
use Modules\DoubleEntry\Models\Account;
use Illuminate\Http\Request;


class Accounts extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-double-entry-accounts')->only(['index', 'show']);
        $this->middleware('permission:create-double-entry-accounts')->only(['create', 'store']);
        $this->middleware('permission:update-double-entry-accounts')->only(['edit', 'update']);
        $this->middleware('permission:delete-double-entry-accounts')->only('destroy');
    }

    public function index()
    {
        $accounts = Account::companyId(company_id())->paginate(25);

        return view('double-entry::accounts.index', compact('accounts'));
    }

    public function create()
    {
        $types = [
            'asset'     => trans('double-entry::accounts.types.asset'),
            'liability' => trans('double-entry::accounts.types.liability'),
            'equity'    => trans('double-entry::accounts.types.equity'),
            'revenue'   => trans('double-entry::accounts.types.revenue'),
            'expense'   => trans('double-entry::accounts.types.expense'),
        ];

        return view('double-entry::accounts.create', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:double_entry_accounts,code,NULL,id,company_id,' . company_id(),
            'type' => 'required|in:asset,liability,equity,revenue,expense',
        ]);

        Account::create([
            'company_id' => company_id(),
            'name' => $request->name,
            'code' => $request->code,
            'type' => $request->type,
            'enabled' => $request->get('enabled', 1),
        ]);

        return redirect()->route('double-entry.accounts.index')->with('success', trans('messages.success.added', ['type' => trans('double-entry::accounts.account')]));
    }

    public function edit(Account $account)
    {
        // Validate company ownership
        if ($account->company_id != company_id()) {
            abort(404);
        }

        $types = [
            'asset'     => trans('double-entry::accounts.types.asset'),
            'liability' => trans('double-entry::accounts.types.liability'),
            'equity'    => trans('double-entry::accounts.types.equity'),
            'revenue'   => trans('double-entry::accounts.types.revenue'),
            'expense'   => trans('double-entry::accounts.types.expense'),
        ];

        return view('double-entry::accounts.edit', compact('account', 'types'));
    }

    public function update(Account $account, Request $request)
    {
        // Validate company ownership
        if ($account->company_id != company_id()) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:double_entry_accounts,code,' . $account->id . ',id,company_id,' . company_id(),
            'type' => 'required|in:asset,liability,equity,revenue,expense',
        ]);

        $account->update([
            'name' => $request->name,
            'code' => $request->code,
            'type' => $request->type,
            'enabled' => $request->get('enabled', 1),
        ]);

        return redirect()->route('double-entry.accounts.index')->with('success', trans('messages.success.updated', ['type' => trans('double-entry::accounts.account')]));
    }

    public function destroy(Account $account)
    {
        // Validate company ownership
        if ($account->company_id != company_id()) {
            abort(404);
        }

        // Prevent deletion if account has entries
        if ($account->entries()->exists()) {
            return redirect()->back()
                ->with('error', trans('double-entry::errors.account_has_entries'));
        }

        $account->delete();

        return redirect()->route('double-entry.accounts.index')->with('success', trans('messages.success.deleted', ['type' => trans('double-entry::accounts.account')]));
    }
}
