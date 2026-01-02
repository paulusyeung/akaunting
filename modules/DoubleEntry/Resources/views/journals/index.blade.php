@extends('layouts.admin')

@section('title', trans('double-entry::general.journals'))

@section('new_button')
    <a href="{{ route('double-entry.journals.create') }}" class="btn btn-success btn-sm">{{ trans('general.add_new') }}</a>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ trans('general.date') }}</th>
                                    <th>{{ trans('general.reference') }}</th>
                                    <th>{{ trans('general.description') }}</th>
                                    <th class="text-right">{{ trans('general.amount') }}</th>
                                    <th class="text-center">{{ trans('general.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($journals as $journal)
                                    <tr>
                                        <td>{{ $journal->date }}</td>
                                        <td>{{ $journal->reference }}</td>
                                        <td>{{ $journal->description }}</td>
                                        <td class="text-right">{{ $journal->entries->sum('debit') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('double-entry.journals.show', $journal->id) }}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>
                                            {!! Form::open(['route' => ['double-entry.journals.destroy', $journal->id], 'method' => 'delete', 'style' => 'display:inline']) !!}
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
