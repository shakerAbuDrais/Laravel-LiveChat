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

                        <h1>Welcome, {{ $username }}!</h1>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>User Name</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <tr>
                                        <td>{{ $username }}</td>
                                        <td>{{ $email }}</td>
                                    </tr>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <h2>Subjects</h2>

            <table class="table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Pass Mark</th>
                        <th>Mark Obtained</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subjects as $subject)
                        <tr>
                            <td>{{ $subject->subject }}</td>
                            <td>{{ $subject->pass_mark }}</td>
                            <td>{{ $obtainedMarks[$subject->id] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
