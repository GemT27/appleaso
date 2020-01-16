<?php
define('QAPP', 1);
require_once('../../inc/config.php');
function base62_20171106($x)
{
    $show = '';
    while ($x > 0) {
        $s = $x % 62;
        if ($s > 35) {
            $s = chr($s + 61);
        } elseif ($s > 9 && $s <= 35) {
            $s = chr($s + 55);
        }
        $show = $s . $show;
        $x = floor($x / 62);
    }
    if (!$show)
        $show = "0";
    return $show;
}
function getCuid_20171106()
{
    @list($usec, $sec) = explode(" ", microtime());
    //$sec = substr($sec, 0, 6);
    $sec_a = substr($sec, 2, 3) + rand(100, 999);
    $sec_b = substr($sec, 5, 5) + rand(100, 999);
    $str_sec = base62_20171106($sec_a) . base62_20171106($sec_b);
    $str_usec = base62_20171106(($usec + rand(1, 9)) * 1000000);
    $left = $str_sec . $str_usec . base62_20171106(rand(1000, 9999));

    if (strlen($left) < 12) {
        $left .= base62_20171106(rand(0, 9));
    }

    return strtoupper($left);
}

function myGetImage($url, $save_filename = "")
{
    if ($url == "")
        return false;
    if ($save_filename == "") {
        $save_filename = date("dMYHis");
    }
    $img = file_get_contents($url);
    $size = strlen($img);
    $fp2 = @fopen($save_filename, "w+");
    fwrite($fp2, $img);
    fclose($fp2);
    return true;
}

//文件保存目录路径(系统真是路径)
$save_path = SystemRoot . UploadDir . DIRECTORY_SEPARATOR;

//相对站点的路径
$save_url = SystemDir . UploadDir . '/';

//alert(str_replace('Public/Js/kindeditor/php/','',dirname($_SERVER['PHP_SELF']) . '/Uploads/'));
if ((isset($_POST['img'])) && (!empty($_POST['img']))) {
    $img = $_POST['img'];
    //set_time_limit(0);
    $ymd = date("Ymd");
    $save_path .= $ymd . '/';
    $save_url .= $ymd . '/';
    if (!file_exists($save_path)) {
        @mkdir($save_path, 0777);
    }
    $filename = getCuid_20171106() . '.png';
    $save_path .= $filename;
    $save_url .= $filename;

    if (isset($_POST['isurl'])) {
        if (myGetImage($img, $save_path)) {
            echo $save_url;
        } else {
            echo "";
        }
    } else {
        $base64_body = substr(strstr($img, ','), 1);
        $data = base64_decode($base64_body);
        file_put_contents($save_path, $data);
        echo $save_url;
    }
} else {
    echo '';
}