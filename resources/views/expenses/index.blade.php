<!DOCTYPE html>
<html lang="en">
<head>
    <title>Expenses</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
    <h2>Expenses: {{ auth()->user()->name }}</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <a class="btn btn-info" role="button" href="{{ route('dashboard') }}"> Dashboard</a>
    <a href="{{ url()->route('expenses.create') }}" class="btn btn-info" role="button">Add New</a>
    <div style="float: right;">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-jet-dropdown-link href="{{ route('logout') }}"
                                 onclick="event.preventDefault();
                                                this.closest('form').submit();">
                {{ __('Log Out') }}
            </x-jet-dropdown-link>
        </form>
    </div>
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Date</th>
            <th>Amount</th>
            <th>File</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($expenses as $expense)
            <tr>
                <td>{{ $expense->id }}</td>
                <td>{{ $expense->name }}</td>
                <td>{{ $expense->date }}</td>
                <td>{{ $expense->amount }}</td>
                <td>@if(!empty($expense->file_path))<a href="{{ $expense->file_path }}">Download</a>@endif</td>
                <td>{{ $expense->status }}</td>
                <td>
                    @can('see')
                        <a href="{{ url()->route('expenses.show', [$expense->id]) }}" class="btn btn-primary" role="button">See</a>
                    @endcan

                    @if(auth()->user()->can('cancel') && $expense->status !== \App\Enums\SystemEnums::StatusIsCanceled)
                        <a href="{{ url()->route('expenses.cancel', [$expense->id]) }}" class="btn btn-danger" role="button">Cancel</a>
                    @endif

                    @if(auth()->user()->can('approve') && $expense->status !== \App\Enums\SystemEnums::StatusIsApproved)
                        <a href="{{ url()->route('expenses.approve', [$expense->id]) }}" class="btn btn-success" role="button">Approve</a>
                    @endif

                    @if(auth()->user()->can('reject') && $expense->status !== \App\Enums\SystemEnums::StatusIsRejected)
                        <a href="{{ url()->route('expenses.reject', [$expense->id]) }}" class="btn btn-danger" role="button">Reject</a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $expenses->links() }}
</div>

</body>
</html>
