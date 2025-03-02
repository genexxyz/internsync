<!-- filepath: /opt/lampp/htdocs/internsync/resources/views/test-upload.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Laravel File Upload Test</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Laravel File Upload Test</h2>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <strong>{{ $message }}</strong>
        </div>
        <p>File Path: {{ Session::get('path') }}</p>
    @endif
    <form action="{{ route('test.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="document">Choose Document</label>
            <input type="file" name="document" class="form-control" id="document">
            @error('document')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</div>
</body>
</html>