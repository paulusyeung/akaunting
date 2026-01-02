<?php

namespace Modules\DoubleEntry\Http\Controllers;

use App\Abstracts\Http\Controller;
use Modules\DoubleEntry\Models\Journal;
use Modules\DoubleEntry\Models\Account;
use Modules\DoubleEntry\Jobs\CreateJournal;
use Illuminate\Http\Request;


class Journals extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-double-entry-journals')->only(['index', 'show']);
        $this->middleware('permission:create-double-entry-journals')->only(['create', 'store']);
        $this->middleware('permission:update-double-entry-journals')->only(['edit', 'update']);
        $this->middleware('permission:delete-double-entry-journals')->only('destroy');
    }

    public function index()
    {
        $journals = Journal::companyId(company_id())->with('entries.account')->paginate(25);

        return view('double-entry::journals.index', compact('journals'));
    }

    public function create()
    {
        $accounts = Account::companyId(company_id())->enabled()->pluck('name', 'id');

        return view('double-entry::journals.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'reference' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'entries' => [
                'required',
                'array',
                'min:2',
                function ($attribute, $value, $fail) {
                    foreach ($value as $index => $entry) {
                        $hasDebit = !empty($entry['debit']) && $entry['debit'] > 0;
                        $hasCredit = !empty($entry['credit']) && $entry['credit'] > 0;
                        
                        if ($hasDebit && $hasCredit) {
                            $fail(trans('double-entry::errors.entry_both_debit_credit', ['line' => $index + 1]));
                        }
                        
                        if (!$hasDebit && !$hasCredit) {
                            $fail(trans('double-entry::errors.entry_no_debit_credit', ['line' => $index + 1]));
                        }
                    }
                },
            ],
            'entries.*.account_id' => 'required|exists:double_entry_accounts,id',
            'entries.*.debit' => 'nullable|numeric|min:0|required_without:entries.*.credit',
            'entries.*.credit' => 'nullable|numeric|min:0|required_without:entries.*.debit',
        ]);

        try {
            $data = $request->all();
            $data['company_id'] = company_id();
            
            $this->dispatch(new CreateJournal($data));

            return redirect()->route('double-entry.journals.index')->with('success', trans('messages.success.added', ['type' => trans('double-entry::general.journal_entry')]));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(Journal $journal)
    {
        // Validate company ownership
        if ($journal->company_id != company_id()) {
            abort(404);
        }

        return view('double-entry::journals.show', compact('journal'));
    }

    public function destroy(Journal $journal)
    {
        // Validate company ownership
        if ($journal->company_id != company_id()) {
            abort(404);
        }

        $journal->delete();

        return redirect()->route('double-entry.journals.index')->with('success', trans('messages.success.deleted', ['type' => trans('double-entry::general.journal_entry')]));
    }
}
