<html>
<head></head>
<body>
<form id="my" action="{{ url('api/uploadImg') }}" enctype="multipart/form-data" method="post">

    <input type="file" name="file" />
    <input type="submit" value="submit" />
</form>
</body>
</html>