<!DOCTYPE html>
<html>
<head>
    <title>TEST</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.0/css/bootstrap.min.css">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.0/js/bootstrap.min.js'></script>
</head>
<body>
<div class="container">
    <br>
    <div class="card">
        <div class="card-body">
            <div class="form-group container">
                <label for="exampleTextarea">Result</label>
                <textarea class="form-control" id="exampleTextarea" rows="30">
                    {{ $res }}
                </textarea>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('test.index') }}" role="button" class="btn btn-primary">back</a>
        </div>
    </div>
</div>
</body>
</html>
