<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing page</title>
</head>

<body>
    <form enctype="multipart/form-data" action="/api/store" method="POST">
        @method('POST')
        @csrf
        <!-- MAX_FILE_SIZE (maximum file size in bytes) must precede the file input field used to upload the QR code image -->
        <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
        <!-- The "name" of the input field has to be "file" as it is the name of the POST parameter -->
        Choose QR code image to read/scan: <input name="file" type="file" />
        <input type="submit" value="Read QR code" />
    </form>
</body>

</html>