<?php

function reArrayFiles(&$file_post)
{
    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i = 0; $i < $file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}


if ($_FILES['files']) {
    $files = reArrayFiles($_FILES['files']);
    
    $result = array();

    foreach ($files as $file) {

        list ($name, $ext) = explode('.', $file['name']);

        $folder = date('m-d-Y');
        $uploadDir = "media/" . $folder;

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = md5(rand() . microtime(true)) . '.' . $ext;
        $uploadDir .= '/' . $fileName;

        if (move_uploaded_file($file['tmp_name'], $uploadDir)) {

            $size = getimagesize($uploadDir);

            $result[] = array(
                "url" => 'http://' . $_SERVER['SERVER_ADDR'] . ($_SERVER['SERVER_PORT'] != 80 ? ":" . $_SERVER['SERVER_PORT'] : '') . '/' . $uploadDir,
                "name" => $fileName,
                "width" => $size[0],
                "height" => $size[1],
                "type" => $size['mime'],
                "size" => $file['size'],
            );
        }


    }
    echo json_encode($result);
    exit();
}

?>

<form action="./" method="post" enctype="multipart/form-data">
    Send these files:<br/>
    <input name="files[]" type="file" multiple/><br/>
    <input type="submit" value="Send files"/>
</form>