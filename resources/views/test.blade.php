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
        <form id="upload" method='POST' action='{{ route('test.store') }}' enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="fb-file form-group ">
                    <label for="attachment" class="fb-file-label">
                        Upload Pdf File
                        <span class="fb-required">*</span>
                        <span class="tooltip-element" tooltip="choose your pdf">?</span>
                    </label>
                    <input type="file" placeholder="choose your pdf" class="form-control" name="file"
                           title="choose your pdf" aria-required="true">
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success" name="submit" value="tesseract">tesseract</button>
                <button type="submit" class="btn btn-success" name="submit" value="pdf-to-text">pdf-to-text</button>
                <button type="submit" class="btn btn-success" name="submit" value="api-ocr-space">api-ocr-space</button>
                <button type="submit" class="btn btn-success" name="submit" value="google-cloud-vision">
                    google-cloud-vision
                </button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
