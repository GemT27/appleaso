<?php
/**
 * Created by PhpStorm.
 * User: shenjinqiang
 * Date: 16-4-5
 * Time: 下午4:03
 */
!defined('QAPP') AND exit('Forbidden');
function checkvd($vd='')
{
    @session_start();
    //echo  $_SESSION['securimage_code_value'];
    if (isset($_SESSION['securimage_code_value']) && $_SESSION['securimage_code_value'] === strtolower($vd)) {
        return true;
    }

    return false;
}