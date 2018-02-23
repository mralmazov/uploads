<?php

function uploadsFiles($files) {

    if (!is_dir('img')) {
        @mkdir('img', 0777);
    }
    if (!is_dir('doc')) {
        @mkdir('doc', 0777);
    }
    if (!is_dir('uploads')) {
        @mkdir('uploads', 0777);
    }

    if (!isset($files['uploads'])) {
        return;
    }

    $file = $files['uploads'];

    if ($file['error']) {

        switch ($file['error']) {
            case UPLOAD_ERR_INI_SIZE:
                return 'The uploaded file exceeds the max file size';
                break;
            case UPLOAD_ERR_FORM_SIZE:
                return 'The uploaded file exceeds the max file size';
                break;
            case UPLOAD_ERR_CANT_WRITE:
                return 'Failed to write file to disk';
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Missing a temporary folder';
                break;
        }
        return 'Error file upload';
    }

    $file['name'] = strtolower($file['name']);

    $allowedExt = [
        'jpg',
        'jpeg',
        'png',
        'gif',
        'doc',
        'docx',
        'xls',
        'xlsx',
        'txt',
        'pdf',
    ];

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    if (!in_array($ext, $allowedExt)) {
        return 'Wrong file type';
    }

    $allowedMimes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'text/plain',
        'application/pdf',
    ];

    if (!in_array($file['type'], $allowedMimes)) {
        return 'Wrong file mime type';
    }

    $moveInImgDir = ['jpg', 'jpeg', 'png', 'gif'];
    $moveInDocDir = ['doc', 'docx', 'xls', 'xlsx', 'txt', 'pdf'];

    if (in_array($ext, $moveInImgDir)) {
        move_uploaded_file($file['tmp_name'], 'img/' . $file['name']);
    }
    if (in_array($ext, $moveInDocDir)) {
        move_uploaded_file($file['tmp_name'], 'doc/' . $file['name']);
    }

    return 'File successfully uploaded';

}

echo uploadsFiles($_FILES);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ЗАГРУЗКА ФАЙЛОВ</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="form">
    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="MAX_FILE_SIZE" value="2000000"/>
        <div><input type="file" name="uploads"></div>
        <br>
        <div><button>Загрузить</button></div>
    </form>
</div>
<div class="gallery">
        <? foreach (scandir('img') as $item) {
            if (in_array($item, ['.', '..'])) {
                continue;
            }
            echo "<img class='photo' src='img/" . $item . "' >";
        } ?>
    </div>

    <div class="list">
        <? foreach (scandir('doc') as $file) {
        if ($file == '.' or $file == '..') {
        continue;
        }
        echo "<br>";
        echo strtolower($file);
        }?>
    </div>

</body>
</html>
