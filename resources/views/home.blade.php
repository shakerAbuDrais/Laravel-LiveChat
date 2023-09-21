@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('You are logged in!') }}
                    </div>
                </div>
            </div>
        </div>

        <h2>Subjects</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Pass Mark</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subjects as $subject)
                    <tr>
                        <td>{{ $subject->subject }}</td>
                        <td>{{ $subject->pass_mark }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
