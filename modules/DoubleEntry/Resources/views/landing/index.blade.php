@extends('layouts.admin')

@section('title', trans('double-entry::general.name'))

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ trans('double-entry::general.welcome') }}</h3>
                </div>
                <div class="card-body">
                    <p>{{ trans('double-entry::general.description') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
