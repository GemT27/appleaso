<?php
!defined('QAPP') AND exit('Forbidden');
global $power, $a, $article;

date_default_timezone_set('Asia/Shanghai');

//-------------------------------
define('SystemRoot', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
define('IncDir', SystemRoot . 'inc' . DIRECTORY_SEPARATOR);
define('CacheDir', SystemRoot . 'cache' . DIRECTORY_SEPARATOR);
define('SystemDir', '/');//网站程序目录,必须以/开始结束
define('SystemDomain', '');//网站域名 多个域名用;分隔
define('AdminDir', 'admin');//后台目录
define('UploadDir', 'uploadfile');//上传目录
define('TemplateDir', 'web/tpl/');//模板目录
define('WebConfigDir', 'web/inc/');//用户配置目录
define('WebResDir', 'web/res/');//网站资源目录
define('UrlRewrite', 1);//启用伪静态,1为开启,0为关闭
define('IndexFile', 'index.php');//入口文件,无需修改
define('WapOpen', 1);//手机访问页面时自动转换为手机版
define('WapDomain', '');//手机版域名,访问此域名时自动转换为手机版 多个域名用;分隔
define('TableEx', 'j_');//表名前缀
define('ArticleTable', TableEx . 'article');//默认文章表
define('MainTable', 'article');//主文章表，用于后台统计管理员文章数量

define('TemplateStart', '{{');//模板开始标签
define('TemplateEnd', '}}');//模板结束标签

define('AdminFileedit', 0);//后台开启文件修改功能
define('version', '1.1.19.311');
define('AccessControl', 0); //是否允许跨域访问权限控制

require(IncDir . 'myfunc.php');
require(IncDir . 'func.php');

//引入markdown转换类
include_once("markdown.php");

$debug = z('开发模式');


if ($debug) {
    define('IndexCache', 0);//首页缓存时间(秒)
    define('TemplateTime', 0);//模板缓存时间
    ini_set('display_errors', 'On');
    error_reporting(E_ALL);
    define('DbError', 1);//是否显示数据库出错信息
} else {
    define('IndexCache', 1800);//首页缓存时间(秒)
    define('TemplateTime', 1800);//模板缓存时间
    ini_set('display_errors', 'Off');
    error_reporting(0);
    define('DbError', 0);//是否显示数据库出错信息
}


if (!defined("admin") && !defined("loginpage")) {
    if (@file_exists(SystemRoot . WebConfigDir . '/config.php')) {
        @require(SystemRoot . WebConfigDir . '/config.php');
    }

    if (@file_exists(SystemRoot . WebConfigDir . '/lib.php')) {
        @require(SystemRoot . WebConfigDir . '/lib.php');
    }

    if (@file_exists(SystemRoot . WebConfigDir . '/hook.php')) {
        @require(SystemRoot . WebConfigDir . '/hook.php');
    }
}
_stripslashes();