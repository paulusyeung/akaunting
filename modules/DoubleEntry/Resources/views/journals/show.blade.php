@extends('layouts.admin')

@section('title', trans('double-entry::general.journal_entry') . ': ' . $journal->reference)

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ trans('general.date') }}: {{ $journal->date }}</h3>
                </div>
                <div class="card-body">
                    <p><strong>{{ trans('general.description') }}:</strong> {{ $journal->description }}</p>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ trans('double-entry::accounts.account') }}</th>
                                    <th class="text-right">{{ trans('general.debit') }}</th>
                                    <th class="text-right">{{ trans('general.credit') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($journal->entries as $entry)
                                    <tr>
                                        <td>{{ $entry->account->name }} ({{ $entry->account->code }})</td>
                                        <td class="text-right">{{ $entry->debit > 0 ? number_format($entry->debit, 2) : '-' }}</td>
                                        <td class="text-right">{{ $entry->credit > 0 ? number_format($entry->credit, 2) : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="text-right"><strong>{{ trans('general.total') }}</strong></td>
                                    <td class="text-right"><strong>{{ number_format($journal->entries->sum('debit'), 2) }}</strong></td>
                                    <td class="text-right"><strong>{{ number_format($journal->entries->sum('credit'), 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('double-entry.journals.index') }}" class="btn btn-outline-secondary">{{ trans('general.back') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection
