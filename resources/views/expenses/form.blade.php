<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
    <h2>Expense Form</h2>
    <form method="post" action="{{ url()->route('expenses.store') }}" enctype="multipart/form-data">
        {!! csrf_field() !!}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" placeholder="Enter name" name="name">
        </div>
        <div class="form-group">
            <label for="date">Date:</label>
            <input type="date" class="form-control" id="date" placeholder="Enter date" name="date">
        </div>
        <div class="form-group">
            <label for="file">File:</label>
            <input type="file" class="form-control" id="file" placeholder="Enter file" name="file">
        </div>
        <div class="form-group">
            <label for="amount">Amount:</label>
            <input type="number" class="form-control" id="amount" placeholder="Enter amount" name="amount">
        </div>

        <button type="submit" class="btn btn-default">Submit</button>
        <a href="{{ url()->route('expenses.index') }}" class="btn btn-danger" role="button">Cancel</a>
    </form>
</div>

</body>
</html>
