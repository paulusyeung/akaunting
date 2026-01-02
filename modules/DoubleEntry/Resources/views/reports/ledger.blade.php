@extends('layouts.admin')

@section('title', trans('double-entry::general.ledger_report'))

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ trans('general.code') }}</th>
                                    <th>{{ trans('general.name') }}</th>
                                    <th class="text-right">{{ trans('general.debit') }}</th>
                                    <th class="text-right">{{ trans('general.credit') }}</th>
                                    <th class="text-right">{{ trans('general.balance') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ledger as $item)
                                    <tr>
                                        <td>{{ $item['code'] }}</td>
                                        <td>{{ $item['name'] }}</td>
                                        <td class="text-right">{{ number_format($item['debit'], 2) }}</td>
                                        <td class="text-right">{{ number_format($item['credit'], 2) }}</td>
                                        <td class="text-right"><strong>{{ number_format($item['balance'], 2) }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-right"><strong>{{ trans('general.total') }}</strong></td>
                                    <td class="text-right"><strong>{{ number_format(collect($ledger)->sum('debit'), 2) }}</strong></td>
                                    <td class="text-right"><strong>{{ number_format(collect($ledger)->sum('credit'), 2) }}</strong></td>
                                    <td class="text-right"><strong>{{ number_format(collect($ledger)->sum('balance'), 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
