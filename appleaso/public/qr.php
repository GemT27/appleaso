<?php
/**
 * Created by PhpStorm.
 * User: shenj
 * Date: 2018.10.24
 * Time: 21:27
 */
define('QAPP', 1);
include './inc/myfunc.php';
$txt = '';
include './inc/qrcode.php';
if (getGet('txt') || getGet('t')) {
    //生产二维码
    $txt = getGet('txt');
    if (!$txt) {
        $txt = getGet('t');
    }
} else if (!!$_SERVER['REQUEST_URI']) {
    $txt = substr($_SERVER['REQUEST_URI'], strlen("/qr/"));
} else {

}
QRcode::png($txt, false, QR_ECLEVEL_H, 5, 2);
