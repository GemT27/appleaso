<?php
/**
 * Created by PhpStorm.
 * User: jxsbox
 * Date: 2018/7/16
 * Time: 下午1:35
 */
!defined('QAPP') AND exit('Forbidden');
include_once "../web/inc/lib.php";

//存放hook函数
function diy_select($action='',$name='',$value='') {
    $option=array('左边'=>'left','右边'=>'right',);
    if($action=='output') {
        echo('<select name="'.$name.'">');
        echo("<option value='fa-bullhorn'>"+$option[0]+"</option>");
        foreach($option as $val=>$v) {
            if($value==$v) {
                echo('<option value="'.$v.'" selected>'.$val.'</option>');
            }else {
                echo('<option value="'.$v.'">'.$val.'</option>');
            }
        }
        echo('</select>');
    }
    if($action=='input') {
        if(isset($_POST[$name]) && in_array($_POST[$name],$option)) {
            Return $_POST[$name];
        }
        Return '';

    }
    if($action=='show') {
        Return $value;
    }
}

//板块选择
function bankuai_select($action = '', $name = '', $value = '')
{
    $bankuai = listFindAnd(33, array());
    $option = array();
    foreach ($bankuai as $val => $v) {
        $option[$val] = $v['label'];
    }
    //var_dump($option);
    if ($action == 'output') {
        echo('<select name="' . $name . '">');
        foreach ($option as $val => $v) {
            if ($value == $v) {
                echo('<option value="' . $v . '" selected>' . $v . '</option>');
            } else {
                echo('<option value="' . $v . '">' . $v . '</option>');
            }
        }
        echo('</select>');
    }
    if ($action == 'input') {
        if (isset($_POST[$name]) && in_array($_POST[$name], $option)) {
            Return $_POST[$name];
        }
        Return '';

    }
    if ($action == 'show') {
        Return $value;
    }
}