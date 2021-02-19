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
    <a href="{{ url()->route('expenses.index') }}" class="btn btn-info" role="button">Back</a>
    <table class="table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Date</th>
            <th>Amount</th>
            <th>File</th>
            <th>Status</th>
{{--            <th>Action</th>--}}
        </tr>
        </thead>
        <tbody>
        @if($expense)
            <tr>
                <td>{{ $expense->name }}</td>
                <td>{{ $expense->date }}</td>
                <td>{{ $expense->amount }}</td>
                <td>@if(!empty($expense->file_path))<a href="{{ $expense->file_path }}">Download</a>@endif</td>
                <td>{{ $expense->status }}</td>
{{--                <td>--}}
{{--                    <a href="{{ url()->route('expenses.show', [$expense->id]) }}" class="btn btn-primary" role="button">See</a>--}}
{{--                    <a href="{{ url()->route('expenses.create') }}" class="btn btn-danger" role="button">Cancel</a>--}}
{{--                </td>--}}
            </tr>
        @endif
        </tbody>
    </table>
</div>

</body>
</html>
