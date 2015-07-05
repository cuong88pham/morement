<?php

// function created by www.thewebhelp.com


function create_square_image($original_file, $destination_file = NULL, $square_size = 96)
{

    if (isset($destination_file) and $destination_file != NULL) {
        if (!is_writable($destination_file)) {
            echo '<p style="color:#FF0000">Oops, the destination path is not writable. Make that file or its parent folder wirtable.</p>';
        }
    }

    // get width and height of original image
    $imagedata = getimagesize($original_file);
    $original_width = $imagedata[0];
    $original_height = $imagedata[1];

    if ($original_width > $original_height) {
        $new_height = $square_size;
        $new_width = $new_height * ($original_width / $original_height);
    }
    if ($original_height > $original_width) {
        $new_width = $square_size;
        $new_height = $new_width * ($original_height / $original_width);
    }
    if ($original_height == $original_width) {
        $new_width = $square_size;
        $new_height = $square_size;
    }

    $new_width = round($new_width);
    $new_height = round($new_height);

    // load the image
    if (substr_count(strtolower($original_file), ".jpg") or substr_count(strtolower($original_file), ".jpeg")) {
        $original_image = imagecreatefromjpeg($original_file);
    }
    if (substr_count(strtolower($original_file), ".gif")) {
        $original_image = imagecreatefromgif($original_file);
    }
    if (substr_count(strtolower($original_file), ".png")) {
        $original_image = imagecreatefrompng($original_file);
    }

    $smaller_image = imagecreatetruecolor($new_width, $new_height);
    $square_image = imagecreatetruecolor($square_size, $square_size);

    imagecopyresampled($smaller_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);

    if ($new_width > $new_height) {
        $difference = $new_width - $new_height;
        $half_difference = round($difference / 2);
        imagecopyresampled($square_image, $smaller_image, 0 - $half_difference + 1, 0, 0, 0, $square_size + $difference, $square_size, $new_width, $new_height);
    }
    if ($new_height > $new_width) {
        $difference = $new_height - $new_width;
        $half_difference = round($difference / 2);
        imagecopyresampled($square_image, $smaller_image, 0, 0 - $half_difference + 1, 0, 0, $square_size, $square_size + $difference, $new_width, $new_height);
    }
    if ($new_height == $new_width) {
        imagecopyresampled($square_image, $smaller_image, 0, 0, 0, 0, $square_size, $square_size, $new_width, $new_height);
    }


    // if no destination file was given then display a png		
    if (!$destination_file) {
        imagepng($square_image, NULL, 9);
    }

    // save the smaller image FILE if destination file given
    if (substr_count(strtolower($destination_file), ".jpg")) {
        imagejpeg($square_image, $destination_file, 100);
    }
    if (substr_count(strtolower($destination_file), ".gif")) {
        imagegif($square_image, $destination_file);
    }
    if (substr_count(strtolower($destination_file), ".png")) {
        imagepng($square_image, $destination_file, 9);
    }

    imagedestroy($original_image);
    imagedestroy($smaller_image);
    imagedestroy($square_image);

}


// in your php pages create images with a code like this:
// create_square_image("sample.jpg","sample_thumb.jpg",200);
// first parameter is the name of the image file to resize
// second parameter is the path where you would like to save the new square thumb, e.g. "sample_thumb.jpg" or just "NULL" if you do not want to save new image. If NULL then this file should be used as the "src" of the image. Folder whre you save image has to be writable, "777" permission code on most servers.
// 200 is the size of the new square thumb

// create_square_image("sample.jpg","sample_thumb.jpg",200);

ini_set("display_errors", 1);
ini_set("track_errors", 1);
ini_set("html_errors", 1);
error_reporting(E_ALL);
function createThumbs($pathToImages, $pathToThumbs, $thumbWidth)
{
    // open the directory
    $dir = opendir($pathToImages);
    // loop through it, looking for any/all JPG files:
    while (false !== ($fname = readdir($dir))) {
        // parse path for the extension
        $info = pathinfo($pathToImages . $fname);
        // continue only if this is a JPEG image
        if (strtolower($info['extension']) == 'jpg') {
            echo "Creating thumbnail for {$fname} <br />";
            // load image and get image size
            // $img = imagecreatefromjpeg("{$pathToImages}{$fname}");
            // $width = imagesx($img);
            // $height = imagesy($img);
            // // calculate thumbnail size
            // $new_width = $thumbWidth;
            // $new_height = floor($height * ($thumbWidth / $width));
            // // create a new temporary image
            // $tmp_img = imagecreatetruecolor($new_width, $new_height);
            // // copy and resize old image into new image 
            // imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            // // save thumbnail into a file
            // imagejpeg($tmp_img, "{$pathToThumbs}{$fname}");
            create_square_image("{$pathToImages}{$fname}", "{$pathToThumbs}{$fname}", $thumbWidth);
        }
    }
    // close the directory
    closedir($dir);
}

// call createThumb function and pass to it as parameters the path 
// to the directory that contains images, the path to the directory
// in which thumbnails will be placed and the thumbnail's width. 
// We are assuming that the path will be a relative path working 
// both in the filesystem, and through the web for links
createThumbs("media/07-04-2015/", "media/thumbs/07-04-2015/", 200);

?>

