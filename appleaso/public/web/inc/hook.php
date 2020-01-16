<?php
/**
 * Created by PhpStorm.
 * User: jxsbox
 * Date: 2018/11/9
 * Time: 9:44 PM
 */

web_hook("article", "文章权限", "art_qx");
function art_qx()
{
//    print_r(debug_backtrace());
    //jLog("webhook", "art_qx");
    global $article;
    global $qx;
    global $msg;
//    var_dump($article);
    if (z("访问权限")) {
        $qx = getQX($article['fid'], true);
//        echo $qx;
        $msg = "您没有权限查看该文章！";
    } elseif ($article['needlogin']) {
        $qx = !!web_getadminname();
        $gotoUrl = web_gotoLoginUrl(getRequestUri());
        $msg = "该文章要求登录账号才能查看！<a href='{$gotoUrl}'>登录</a>";
    } else {
        $qx = true;
    }
}