<?php
define('QAPP', 1);

if (!file_exists("cache")) {
    @mkdir("cache", 0777);
}

@require('inc/config.php');

//如果是开发模式，则记录页面运行时间
if ($debug) getRunTime(0);

//jLog("uri",getRequestUri());

//最早被执行
run_web_hook('index', 'preload');
//getSubLmIds(40);
if (z('关闭网站')) {
    $hook_var = true;
    //关闭网站
    run_web_hook('index', 'webstop');
    if ($hook_var) {
        //网站关闭的页面
        if (file_exists(TemplateDir . "webstop.php")) {
            include(TemplateDir . "webstop.php");
        } else {
            echo get_errPage("维护中...", "网站维护中...");
        }
    }
} elseif (z('访问权限') && !web_getadminname()) {
    $hook_var = true;
    //登录后才能访问网站
    run_web_hook('index', 'needlogin');
    if ($hook_var) {
        //网站关闭的页面
        if (file_exists(TemplateDir . "needlogin.php")) {
            include(TemplateDir . "needlogin.php");
        } else {
            $login_url = web_gotoLoginUrl(getRequestUri());
            echo get_errPage("需要登录后才能访问", "需要登录后才能访问网站，<a href='{$login_url}'>去登录</a>");
        }
    }
} else {
    //匹配路由
    $hook_var = true;
    //根据页面url寻找路由前
    run_web_hook('index', 'preroute');
    if ($hook_var) {
        match_route();
    }
    //根据页面url寻找路由后
    run_web_hook('index', 'routed');
}
//如果是开发模式，则输出页面运行时间
if ($debug) file_put_contents(SystemRoot . "debug.txt", getNow() . ' runtime ' . getRunTime(1) . "\n", FILE_APPEND);

