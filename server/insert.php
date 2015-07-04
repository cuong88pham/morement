<?php

ini_set("display_errors", 1);
ini_set("track_errors", 1);
ini_set("html_errors", 1);
error_reporting(E_ALL);

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


if ($_FILES['files'] && $_POST) {

    require_once 'libs/php_cps_api/cps_simple.php';

    $connectionStrings = array(
        'tcp://cloud-us-0.clusterpoint.com:9007',
        'tcp://cloud-us-1.clusterpoint.com:9007',
        'tcp://cloud-us-2.clusterpoint.com:9007',
        'tcp://cloud-us-3.clusterpoint.com:9007',
    );
    $cpsConn = new CPS_Connection(new CPS_LoadBalancer($connectionStrings), 'Media', 'harry88pham@gmail.com', '12344321', 'document', '//document/id', array('account' => 100693));
    $cpsConn->setDebug(true);
    $cpsSimple = new CPS_Simple($cpsConn);


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

            $result[uniqid()] = array(
                "name" => $_POST['name'],
                "title" => $_POST['title'],
                "caption" => $_POST['caption'],
                "user" => $_POST['user'],
                "url" => 'http://130.211.244.98' . ($_SERVER['SERVER_PORT'] != 80 ? ":" . $_SERVER['SERVER_PORT'] : '') . '/' . $uploadDir,
                "name" => $fileName,
                "location" => array(
                    "lat" => $_POST['lat'],
                    "long" => $_POST['long'],
                    "address" => $_POST['address'],
                ),
                "width" => $size[0],
                "height" => $size[1],
                "type" => $size['mime'],
                "size" => $file['size'],
                "type" => $_POST['type'],
                "status" => 1,
            );

        }


    }

    $ok = $cpsSimple->insertMultiple($result);


    echo json_encode(array(
        "ok" => $ok,
        "result" => $result
    ));
}

?>
<style type="text/css">
    label {
        display: block;
    }

    input {
        width: 300px;
    }
</style>
<form method="post" enctype="multipart/form-data" style="line-height: 50px;">
    <label>title: <input name="title" type="input"/></label>
    <label>caption: <input name="caption" type="input"/></label>
    <label>user: <input name="user" type="input"/></label>
    <label>location lat: <input name="lat" id="lat" type="input"/>
        <button onclick="getLocation(); return false;" type="button">Get current</button>
    </label>
    <label>location long: <input name="long" id="long" type="input"/></label>
    <label>address: <input name="address" id="address" type="input"/>
        <button onclick="getAddress(); return false;" type="button">Get address</button>
    </label>
    <label>type:
        <select name="type">
            <option value="1">image</option>
            <option value="2">video</option>
        </select>
    </label>

    <label>Files: <input name="files[]" type="file" multiple/></label>
    <br/>
    <input type="submit" value="Send files"/>
</form>
<script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true">
</script>
<script type="application/javascript">

    function getLocation() {
        navigator.geolocation.getCurrentPosition(function (pos) {
            crd = pos.coords;
            document.getElementById('lat').value = crd.latitude;
            document.getElementById('long').value = crd.longitude;
            console.log('Your current position is:');
            console.log('Latitude : ' + crd.latitude);
            console.log('Longitude: ' + crd.longitude);
            console.log('More or less ' + crd.accuracy + ' meters.');
        })
    }

    function getAddress() {
        var geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(document.getElementById('lat').value, document.getElementById('long').value);

        geocoder.geocode({
            'latLng': latlng
        }, function (results, status) {

            if (status == google.maps.GeocoderStatus.OK) {
                document.getElementById('address').value = results[0].formatted_address;
            } else {
                console && console.log('Geocoder failed due to: ' + status);
            }
        });
    }
</script>