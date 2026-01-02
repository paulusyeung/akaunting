@extends('layouts.admin')

@section('title', trans('general.add_new') . ' ' . trans('double-entry::general.journal_entry'))

@section('content')
    {!! Form::open(['route' => 'double-entry.journals.store', 'id' => 'journal-form']) !!}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        {{ Form::dateGroup('date', trans('general.date'), 'calendar', ['required' => 'required', 'value' => date('Y-m-d')], date('Y-m-d')) }}
                        {{ Form::textGroup('reference', trans('general.reference'), 'barcode') }}
                        {{ Form::textareaGroup('description', trans('general.description')) }}
                    </div>

                    <hr>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="entries-table">
                            <thead>
                                <tr>
                                    <th>{{ trans('double-entry::accounts.account') }}</th>
                                    <th width="150">{{ trans('general.debit') }}</th>
                                    <th width="150">{{ trans('general.credit') }}</th>
                                    <th width="50"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="entry-row">
                                    <td>
                                        {{ Form::select('entries[0][account_id]', $accounts, null, ['class' => 'form-control select2', 'required' => 'required', 'placeholder' => trans('general.select')]) }}
                                    </td>
                                    <td>
                                        {{ Form::number('entries[0][debit]', 0, ['class' => 'form-control debit-input', 'step' => 'any', 'min' => '0']) }}
                                    </td>
                                    <td>
                                        {{ Form::number('entries[0][credit]', 0, ['class' => 'form-control credit-input', 'step' => 'any', 'min' => '0']) }}
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm remove-row" style="display:none;"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr>
                                <tr class="entry-row">
                                    <td>
                                        {{ Form::select('entries[1][account_id]', $accounts, null, ['class' => 'form-control select2', 'required' => 'required', 'placeholder' => trans('general.select')]) }}
                                    </td>
                                    <td>
                                        {{ Form::number('entries[1][debit]', 0, ['class' => 'form-control debit-input', 'step' => 'any', 'min' => '0']) }}
                                    </td>
                                    <td>
                                        {{ Form::number('entries[1][credit]', 0, ['class' => 'form-control credit-input', 'step' => 'any', 'min' => '0']) }}
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm remove-row" style="display:none;"><i class="fa fa-times"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="text-right"><strong>{{ trans('general.total') }}</strong></td>
                                    <td id="total-debit">0.00</td>
                                    <td id="total-credit">0.00</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm mt-3" id="add-row"><i class="fa fa-plus"></i> {{ trans('general.add_new') }}</button>
                </div>
                <div class="card-footer">
                    <div class="row save-buttons">
                        <div class="col-md-12">
                            <a href="{{ route('double-entry.journals.index') }}" class="btn btn-outline-secondary">{{ trans('general.cancel') }}</a>
                            {!! Form::submit(trans('general.save'), ['class' => 'btn btn-success', 'id' => 'submit-btn']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection

@section('js')
<script>
    $(document).ready(function() {
        let entryCount = 2;

        $('#add-row').on('click', function() {
            let newRow = `<tr class="entry-row">
                <td>
                    <select name="entries[${entryCount}][account_id]" class="form-control" required>
                        <option value="">{{ trans('general.select') }}</option>
                        @foreach($accounts as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" name="entries[${entryCount}][debit]" value="0" class="form-control debit-input" step="any" min="0">
                </td>
                <td>
                    <input type="number" name="entries[${entryCount}][credit]" value="0" class="form-control credit-input" step="any" min="0">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa fa-times"></i></button>
                </td>
            </tr>`;
            
            $('#entries-table tbody').append(newRow);
            entryCount++;
        });

        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
            calculateTotals();
        });

        $(document).on('input', '.debit-input, .credit-input', function() {
            calculateTotals();
        });

        function calculateTotals() {
            let totalDebit = 0;
            let totalCredit = 0;

            $('.debit-input').each(function() {
                totalDebit += parseFloat($(this).val()) || 0;
            });

            $('.credit-input').each(function() {
                totalCredit += parseFloat($(this).val()) || 0;
            });

            $('#total-debit').text(totalDebit.toFixed(2));
            $('#total-credit').text(totalCredit.toFixed(2));

            if (Math.abs(totalDebit - totalCredit) < 0.00001 && totalDebit > 0) {
                $('#submit-btn').prop('disabled', false);
            } else {
                $('#submit-btn').prop('disabled', true);
            }
        }

        // Initial check
        calculateTotals();
    });
</script>
@endsection
