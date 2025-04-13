<!DOCTYPE html>
<html>
<head>
    <title>File Upload</title>
</head>
<body>
    <h2>Upload File</h2>
    @if (session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    <form action="{{ route('file.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" required>
        <button type="submit">Upload</button>
    </form>

    <h3>Uploaded Files:</h3>
    <ul>
        @foreach ($files as $file)
            <li>
                <a href="{{ Storage::url($file) }}" target="_blank">{{ basename($file) }}</a>
            </li>
        @endforeach
    </ul>
</body>
</html>
