@extends('layouts.admin')

@section('title', trans('general.edit') . ' ' . trans('double-entry::accounts.account'))

@section('content')
    {!! Form::model($account, ['route' => ['double-entry.accounts.update', $account->id], 'method' => 'patch']) !!}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        {{ Form::textGroup('name', trans('general.name'), 'id-card') }}
                        {{ Form::textGroup('code', trans('general.code'), 'barcode') }}
                        {{ Form::selectGroup('type', trans('general.type'), 'list', $types) }}
                        {{ Form::radioGroup('enabled', trans('general.enabled'), $account->enabled) }}
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row save-buttons">
                        <div class="col-md-12">
                            <a href="{{ route('double-entry.accounts.index') }}" class="btn btn-outline-secondary">{{ trans('general.cancel') }}</a>
                            {!! Form::submit(trans('general.save'), ['class' => 'btn btn-success']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection
