@extends('layouts.admin')

@section('title', trans('double-entry::accounts.chart_of_accounts'))

@section('new_button')
    <a href="{{ route('double-entry.accounts.create') }}" class="btn btn-success btn-sm">{{ trans('general.add_new') }}</a>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-flush" id="datatable-basic">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ trans('general.name') }}</th>
                                    <th>{{ trans('general.code') }}</th>
                                    <th>{{ trans('general.type') }}</th>
                                    <th>{{ trans('general.enabled') }}</th>
                                    <th class="text-center">{{ trans('general.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($accounts as $account)
                                    <tr>
                                        <td>{{ $account->name }}</td>
                                        <td>{{ $account->code }}</td>
                                        <td>{{ trans('double-entry::accounts.types.' . $account->type) }}</td>
                                        <td>{{ $account->enabled ? trans('general.yes') : trans('general.no') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('double-entry.accounts.edit', $account->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                                            {!! Form::open(['route' => ['double-entry.accounts.destroy', $account->id], 'method' => 'delete', 'style' => 'display:inline']) !!}
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ trans('general.confirm.delete') }}')"><i class="fa fa-trash"></i></button>
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
