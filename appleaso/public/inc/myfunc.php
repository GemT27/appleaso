<?php
!defined('QAPP') AND exit('Forbidden');
/**
 * Created by PhpStorm.
 * User: shenjinqiang
 * Date: 20180628
 * Version:v1.4
 * 增加了JSON文件的快速操作类
 * 用户注销session
 * **20180628
 * 完善和优化微信获取用户信息的函数
 * 完善生成xls的函数
 *
 *
 *
 */
function delDirFiles($dirName, $scanSubDir = false, $ext = '', $delFilemtimeBefore = '')
{
    if (file_exists($dirName) && $handle = opendir($dirName)) {
        while (false !== ($item = readdir($handle))) {
            if ($item != "." && $item != "..") {
                $n_file = $dirName . '/' . $item;
                //echo $n_file . '：';
                if (file_exists($n_file) && is_dir($n_file)) {
                    if ($scanSubDir) {
                        delFile($n_file, true);
                    }
                } else {
                    if ($n_file === rtrim($n_file, $ext)) {
                        if ($delFilemtimeBefore) {
                            $filemtime = filemtime($n_file);
                            //echo $filemtime . '<br>';
                            if ($filemtime !== false) {
                                if ($filemtime < $delFilemtimeBefore) {
                                    unlink($n_file);
                                }
                            }
                        } else {
                            unlink($n_file);
                        }
                    }
                }
            }
        }
        closedir($handle);
    }
}

/**
 * 获取目录下的文件名
 * @param $dirName
 * @return array
 */
function getDirList($dirName)
{
    $ret = array();
    if (file_exists($dirName) && $file_list = scandir($dirName)) {
        foreach ($file_list as $item) {
            if ($item != "." && $item != "..") {
                $ret[] = $item;
            }
        }
    }
    return $ret;
}


/** 递归获取目录下的文件
 * @param string $path
 * @return array
 */
function getDirListLoop($path = './')
{
    $path = rtrim($path, '/') . '/';
    $file = new FilesystemIterator($path);
    $ret = array();
    foreach ($file as $fileinfo) {
        $ret[] = $path . $fileinfo->getFilename();
        if ($fileinfo->isDir()) {
            $subdir_arr = getDirListLoop($path . $fileinfo->getFilename() . '/');
            $ret = array_merge($ret, $subdir_arr);
        }
    }

    return $ret;
}

/*
* 功能：循环检测并创建文件夹
* 参数：$path 文件夹路径 路径，请勿包含文件名，否则文件名也会被创建为目录
* 返回：
*/
function createDirLoop($path)
{
    if (!file_exists($path)) {
        createDirLoop(dirname($path));
        mkdir($path, 0666);
    }
}

function getErrorString($msg)
{
    echo("<b style='color: red;'> ERROR: </b> $msg <br>");
}

/* * 获取GET参数
 * @param $a
 * @return null
 */

function getGet($a)
{
    if (isset($_GET[$a])) {

        return $_GET[$a];
    } else {
        return null;
    }
}

/* * 获取POST参数
 * @param $a
 * @return null
 */

function getPost($a)
{
    if (isset($_POST[$a])) {
        return $_POST[$a];
    } else {
        return null;
    }
}

//获取随机数字
function getRandomNum($len = 4)
{
    $n = "";
    for ($i = 0; $i < $len; $i++) {
        $n .= rand(1, 9);
    }
    return $n;
}

//curl请求post
function request_post($url, $post_data = array())
{
    $options = array(
        'http' => array(
            'method' => 'POST',
            'header' => "Connection: close\r\nContent-type: application/x-www-form-urlencoded",
            'content' => http_build_query($post_data),
            'timeout' => 300,
            'Connection' => "close"
        ),
    );

    $result = file_get_contents($url, false, stream_context_create($options));
    return $result;
}

//请求get
function request_get($url)
{
    $html = file_get_contents($url);
    return $html;
}

/* * 获取GET或POST参数
 * @param $a
 * @return null
 */

function getRequest($a)
{
    if (isset($_GET[$a])) {
        return $_GET[$a];
    } else if (isset($_POST[$a])) {
        return $_POST[$a];
    } else {
        return null;
    }
}

function getCookie($a)
{
    if (isset($_COOKIE[$a])) {
        return $_COOKIE[$a];
    } else {
        return null;
    }
}

/**
 *  设置Cookie
 * @param type $key 键名
 * @param type $val 指
 * @param type $minute 保留时间，分钟
 */
function setCookie_m($key, $val, $minute = null)
{
    if ($minute) {
        setcookie($key, $val, time() + 60 * $minute, '/');
    } else {
        setcookie($key, $val, null, '/');
    }
}

/* * 获取Session
 * @param $a
 * @return string| null
 */

function getSession($a)
{
    if (!isset($_SESSION)) {
        @session_start();
    }

    if (isset($_SESSION[$a])) {
        return $_SESSION[$a];
    } else {
        return null;
    }
}

/* * 设置Session
 * @param $a Session的名称
 * @param $v
 * @return null
 */

function setSession($a, $v)
{
    if (!isset($_SESSION)) {
        @session_start();
    }

    $_SESSION[$a] = $v;
}

/**
 * 注销当前用户的session
 */
function logoutSession()
{
    if (!isset($_SESSION)) {
        @session_start();
    }
    session_destroy();
}

/* * 获取GUID
 * @param string $sp 分隔符
 * @return string
 */

function getGuid($sp = "")
{
    @list($usec, $sec) = explode(" ", microtime());
    $currentTimeMillis = $sec . substr($usec, 2, 3);

    $tmp = rand(0, 1) ? '-' : '';
    $nextLong = $tmp . rand(1000, 9999) . rand(1000, 9999) . rand(1000, 9999) . rand(100, 999) . rand(100, 999);
    $getHostID = 'www.jxsbox.com/271833059@qq.com/*';
    $valueBeforeMD5 = $getHostID . ':' . $currentTimeMillis . ':' . $nextLong;
    $valueAfterMD5 = md5($valueBeforeMD5);
    $raw = strtoupper($valueAfterMD5);

    return substr($raw, 0, 8) . $sp . substr($raw, 8, 4) . $sp . substr($raw, 12, 4) . $sp . substr($raw, 16, 4) . $sp . substr($raw, 20);
}

function base62($x)
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

function getCuid()
{
    @list($usec, $sec) = explode(" ", microtime());
    //$sec = substr($sec, 0, 6);
    $sec_a = substr($sec, 2, 3) + rand(100, 999);
    $sec_b = substr($sec, 5, 5) + rand(100, 999);
    $str_sec = base62($sec_a) . base62($sec_b);
    $str_usec = base62(($usec + rand(1, 9)) * 1000000);
    $left = $str_sec . $str_usec . base62(rand(1000, 9999));

    if (strlen($left) < 12) {
        $left .= base62(rand(0, 9));
    }

    return strtoupper($left);
}

/** 统一消息反馈
 * @param $r 结果状态，一般为0或1
 * @param null $a 第一个参数
 * @param null $b 第二个参数
 * @param null $c 第三个参数
 * @param null $d 第四个参数
 * @return string Json字符串
 */
function getMsg($r, $msg = "", $a = "", $b = "", $c = "", $d = "")
{
    $_msg = array();
    $_msg['msg'] = $msg;
    $_msg['r'] = $r;
    $_msg['a'] = $a;
    $_msg['b'] = $b;
    $_msg['c'] = $c;
    $_msg['d'] = $d;
    return json_encode($_msg);
}

function getOkMsg($msg = "", $a = "", $b = "", $c = "", $d = "")
{
    $_msg = array();
    $_msg['r'] = 1;
    $_msg['msg'] = $msg;
    $_msg['a'] = $a;
    $_msg['b'] = $b;
    $_msg['c'] = $c;
    $_msg['d'] = $d;
    return json_encode($_msg);
}

function getErrMsg($msg = "", $a = "", $b = "", $c = "", $d = "")
{
    $_msg = array();
    $_msg['r'] = 0;
    $_msg['msg'] = $msg;
    $_msg['a'] = $a;
    $_msg['b'] = $b;
    $_msg['c'] = $c;
    $_msg['d'] = $d;
    return json_encode($_msg);
}

function getNeedLoginMsg($msg = "")
{
    $_msg = array();
    $_msg['r'] = -100;
    $_msg['msg'] = $msg;
    return json_encode($_msg);
}

/**
 * 检查是否为手机号码
 * @param $usj
 * @return bool
 */
function checkIsnumber($sj)
{
    //判断是否为手机号码
    if (!preg_match("/1[3578]{1}\d{9}$/", $sj)) {
        return false;
    }
    return true;
}

/**
 * 开启输出缓存，用flush_outbuff输出到浏览器
 */
function start_outbuff()
{
    if (extension_loaded('zlib')) {
        ob_start('ob_gzhandler');
    }
}

/**
 * 输出缓存到浏览器
 */
function flush_outbuff()
{
    if (extension_loaded('zlib')) {
        ob_end_flush();
    }
}

/* * 加载模型
 * @param $modname 模型名称，不是文件名称
 * @return bool|null
 */

function loadmod($modname)
{
    //判断模型文件是否存在如果存在则加载数据库操作模型，模型文件以mod_开头
    if (file_exists("./mods/m_$modname.php")) {
        //加载模型文件
        include("./mods/m_$modname.php");
        return true;
    } else {
        getErrorString("in 'mods' folder not find file <b>'m_$modname.php'</b>,function loadmod('$modname');");
        return false;
    }
}

/* * 加载文件
 * @param $filename
 * @return bool
 */

function loadfile($filename)
{
    //判断文件是否存在如果存在
    if (file_exists($filename)) {
        //加载文件
        include($filename);
        return true;
    } else {
        getErrorString("not find file <b>'$filename'</b>,function loadfile('$filename');");
        return false;
    }
}

$readini_arr = null;
$filename_ini = "";

/** 打开配置文件
 * @param $filename
 * @param $onlyget 只获取
 * @return array|null 打开成功返回所有配置的数组形式
 */
function ini_open($filename, $onlyget = false)
{
    global $filename_ini;
    global $readini_arr;
    if (!$readini_arr) {
        $readini_arr = array();
    }
    if ($filename_ini != "") {
        //ini操作函数已经打开了，此函数同时只能处理一个文件
        getErrorString("ini_file is opened,function ini_open()");
        return null;
    }

    $filename_ini = $filename;
    if (file_exists($filename)) {
        $readini_arr = parse_ini_file($filename, true);
    } else {
        $readini_arr = array();
    }

    if ($onlyget) {
        $filename_ini = "";
    }

    return $readini_arr;
}

/* * 写ini配置项
 * @param $section
 * @param $key
 * @param $value
 */

function ini_set_key($section, $key, $value)
{
    global $readini_arr;
    $readini_arr[$section][$key] = $value;
}

function ini_set_array($arr)
{
    global $readini_arr;

    foreach ($arr as $section => $key) {
        //print_r($section);
        //print_r($key);
        foreach ($key as $key2 => $value2) {
            $readini_arr[$section][$key2] = $value2;
        }
    }
}

/** 写出ini配置文件
 * @return bool
 */
function ini_flush()
{
    global $filename_ini;
    global $readini_arr;
    $content = "";
    foreach ($readini_arr as $key => $elem) {
        $content .= "[" . $key . ']' . "\n";
        foreach ($elem as $key2 => $elem2) {
            $content .= $key2 . ' = "' . $elem2 . '"' . "\n";
        }
    }

    if (!$handle = fopen($filename_ini, 'w')) {
        return false;
    }
    if (!fwrite($handle, $content)) {
        return false;
    }
    fclose($handle);
    $filename_ini = "";
    return true;
}

/** 获取毫秒级别的时间戳
 * @return string
 */
function getMillisecond()
{
    list($t1, $t2) = explode(' ', microtime());
    return sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000) . "";
}

/** 获取微秒时间戳
 * @return string
 */
function getMicrosecond()
{
    list($t1, $t2) = explode(' ', microtime());
    return sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000000) . "";
}

$filename_json = null;
$readini_arr_json = "";


//$ddd = new DB_JSON("a.json");
//echo print_r($ddd->getValue("a/b/g/"));
//$ddd->setValue("root/a/1", 100);
//$ddd->setValue("root/a/2", 200);
//$ddd->setValue("root/a/3", 300);
//$ddd->setValue("root/a/4", 400);
//$ddd->setValue("root/b/1", 100);
//$ddd->setValue("root/b/2", 200);
//$ddd->arrayPushValue("root/a/v",3);
//$ddd->arrayPushValue("root/a/v",5);
//$ddd->arrayPushValue("root/a/v",9);
//$ddd->arrayPushValue("root/a/v",1);
//$ddd->arrayPushValue("root/a/v",2);
//$ddd->save();

/**
 * Class DB_JSON 操作JSON文件的类
 */
class DB_JSON
{

    private $_filename, $readini_arr_json, $_md5, $readOnly;

    public static function getKeys($arr)
    {
        return array_keys($arr);
    }

    function __construct($filename, $readOnly = false)
    {
        $this->_filename = $filename;
        $this->readOnly = $readOnly;
        if ($filename && file_exists($filename)) {
            //如果不是只读，则需等待，否则直接读取
            if (!$this->readOnly) {
                //读取文件
                //判断是否锁定
                while ($this->checkIsLock()) {
                    usleep(300);
                }
            }

            $cont = file_get_contents($filename);

            if (trim($cont)) {
                $this->readini_arr_json = json_decode($cont, true);
            } else {
                $this->readini_arr_json = array();
            }
        } else {
            if (!$this->readOnly) {
                if ($filename) {
                    file_put_contents($filename, "");
                }
            }
            $this->readini_arr_json = array();
        }

        if ($filename) {
            //不是只读才需要锁定文件
            if (!$this->readOnly) {
                $this->_md5 = md5_file($filename);
                file_put_contents($this->getLockFile(), getMillisecond());
            }
        }
    }

    //获取锁定的文件路径
    function getLockFile()
    {
        return $this->_filename . '.lock.0';
    }

    function checkIsLock()
    {
        $lockfile = $this->getLockFile();
        if (file_exists($lockfile)) {
            $k = file_get_contents($lockfile);
            if (!$k) {
                //未锁定
                return false;
            }
            if ((getMillisecond() - $k) >= 20000) {
                //超时设置为20秒，大于就算未锁定
                return false;
            } else {
                //锁定
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * @param $key  支持层级操作如 root/a/b
     * @param $value
     * @return bool
     */
    function setValue($key, $value)
    {
        $key = trim($key);
        $key = trim($key, "/");
        $path = explode("/", $key);
        for ($i = 0; $i < count($path); $i++) {
            $path[$i] = trim($path[$i]);
        }
        //$this->readini_arr_json[$key] = $value;
        if (!array_key_exists($path[0], $this->readini_arr_json)) {
            $this->readini_arr_json[$path[0]] = array();
        }
        $ret_val = &$this->readini_arr_json[$path[0]];

        for ($i = 1; $i < count($path); $i++) {
            $node = $path[$i];
            if (is_array($ret_val)) {
                if (array_key_exists($node, $ret_val)) {
                    $ret_val = &$ret_val[$node];
                } else {
                    $ret_val[$node] = array();
                    $ret_val = &$ret_val[$node];
                }
            } else {
                return false;
            }
        }
        $ret_val = $value;
    }

    function arrayPushKeyValue($key, $arrayKey, $value)
    {
        $temp = $this->getValue($key, array());
        $temp[$arrayKey] = $value;
        $this->setValue($key, $temp);
        return $temp;
    }

    function arrayPopKey($key, $arrayKey)
    {
        $temp = $this->getValue($key, array());
        unset($temp[$arrayKey]);
        $this->setValue($key, $temp);
        return $temp;
    }

    function arrayPopVal($key, $value)
    {
        $temp = $this->getValue($key, array());
        while (($i = array_search($value, $temp)) !== false) {
            array_splice($temp, $i, 1);
        }
        $this->setValue($key, $temp);
        return $temp;
    }

    /**
     * @param $key
     * @param $value
     * @param bool $distinct 是否去除重复，默认为false
     * @return array|mixed|string
     */
    function arrayPushValue($key, $value, $distinct = false)
    {
        $temp = $this->getValue($key, array());
        if ($distinct && !in_array($value, $temp)) {
            $temp[] = $value;
            $this->setValue($key, $temp);
        } else {
            $temp[] = $value;
            $this->setValue($key, $temp);
        }
        return $temp;
    }

    function removeKey($key)
    {
        $key = trim($key);
        $key = trim($key, "/");
        $path = explode("/", $key);
        for ($i = 0; $i < count($path); $i++) {
            $path[$i] = trim($path[$i]);
        }
        $ret_val = &$this->readini_arr_json[$path[0]];
        for ($i = 1; $i < count($path); $i++) {
            $node = $path[$i];
            if ($i == count($path) - 1) {
                unset($ret_val[$node]);
                return true;
            } else if (is_array($ret_val)) {
                if (array_key_exists($node, $ret_val)) {
                    $ret_val = &$ret_val[$node];
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
        unset($this->readini_arr_json[$path[0]]);
        return true;
    }

    function getValue($key, $defaultValue = "")
    {
        $key = trim($key);
        $key = trim($key, "/");
        $path = explode("/", $key);
        for ($i = 0; $i < count($path); $i++) {
            $path[$i] = trim($path[$i]);
        }
        $ret_val = $defaultValue;
        if (array_key_exists($path[0], $this->readini_arr_json)) {
            $ret_val = $this->readini_arr_json[$path[0]];
            for ($i = 1; $i < count($path); $i++) {
                $node = $path[$i];
                if (is_array($ret_val) && array_key_exists($node, $ret_val)) {
                    $ret_val = $ret_val[$node];
                } else {
                    if (is_array($ret_val)) {
                        return $defaultValue;
                    } else {
                        if ($i == count($path) - 1) {
                            $ret_val = $ret_val[$node];
                        } else {
                            return $defaultValue;
                        }
                    }
                }
            }
        }
        return $ret_val;
    }

    function overWrite($arr)
    {
        $this->readini_arr_json = $arr;
    }

    function getRaw()
    {
        return $this->readini_arr_json;
    }

    function save()
    {
        if ($this->readOnly) {
            //开启只读模式不能保存
            return false;
        } else if ($this->_filename) {
            $newMd5 = md5_file($this->_filename);
            if ($newMd5 == $this->_md5) {
                $content = json_encode($this->readini_arr_json);
                if (!$handle = fopen($this->_filename, 'w+')) {
                    return false;
                }
                if (!fwrite($handle, $content)) {
                    return false;
                }
                fclose($handle);
                //更新本次的操作
                $this->_md5 = md5_file($this->_filename);
                return true;
            } else {
                //中间可能操作超时，被其他的进程或线程修改了文件，所以不能进行保存
                return false;
            }
        } else {
            //没有文件名，无法进行保存
            return false;
        }
    }

    //解除锁定
    function close()
    {
        if ($this->readOnly) {
            //开启只读模式不需要关闭，也不允许关闭
            return;
        }

        if ($this->_filename && file_exists($this->_filename)) {
            $newMd5 = md5_file($this->_filename);
            if ($newMd5 == $this->_md5) {
                @file_put_contents($this->getLockFile(), '');
                $this->_filename = "";
            }
        }
    }

    function __destruct()
    {
        $this->close();
    }

}

function tofloat($num)
{
    $dotPos = strrpos($num, '.');
    $commaPos = strrpos($num, ',');
    $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos :
        ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);

    if (!$sep) {
        return floatval(preg_replace("/[^0-9]/", "", $num));
    }

    return floatval(
        preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' .
        preg_replace("/[^0-9]/", "", substr($num, $sep + 1, strlen($num)))
    );
}

/** 获得当前时间，格式为2015-02-02 14:01:05
 * @param string $sp1 第一个分隔符，分割年-月-日，默认-
 * @param string $sp2 第二个分隔符，分割日期与时间，默认为空格
 * @param string $sp3 第三个分隔符，时间间的分割，默认：
 * @return string 返回当前的时间
 */
function getNow($sp1 = "-", $sp2 = " ", $sp3 = ":")
{
    return date("Y" . $sp1 . "m" . $sp1 . "d" . $sp2 . "H" . $sp3 . "i" . $sp3 . "s");
}

/**
 * 获取表示sqlite当前时间的字符串
 * @param string $xg 修改，可以为+int second  -int minute
 * @return string
 */
function getNow_SQLITE($xg = '')
{
    if ($xg) {
        return "DATETIME('now','localtime'," . $xg . ")";
    } else {
        return "DATETIME('now','localtime')";
    }
}

//
// Function: 获取远程图片并把它保存到本地
//
//
//   确定您有把文件写入本地服务器的权限
//
//
// 变量说明:
// $url 是远程图片的完整URL地址，不能为空。
// $filename 是可选变量: 如果为空，本地文件名将基于时间和日期
// 自动生成.
function getImage($url, $save_filename = "")
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

function saveBase64Image($imgBase64, $filename = "")
{
    $base64_image_content = $imgBase64;
    //匹配出图片的格式
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
        $type = strtolower($result[2]);
        $exts = "jpg,jpeg,png,gif,bmp";
        if (stripos($exts, $type) === false) {
            return false;
        }
        if (!$filename) {
            $new_file = UploadDir . "/image/" . date("Ymd") . "/";
            if (!file_exists($new_file)) {
                mkdir($new_file, 0777);
            }
            $new_file = $new_file . getCuid() . ".{$type}";
        } else {
            $new_file = $filename;
        }
        if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content))) !== false) {
            return SystemDir . $new_file;
        } else {
            return false;
        }
    }
    return false;
}

function keywordInArray($array = array(), $keyword = array(), $fanwei = array())
{
    if (!$keyword || !$array) {
        return false;
    }
    if ($fanwei) {
        foreach ($fanwei as $f) {
            foreach ($keyword as $ky) {
                foreach ($array as $k => $v) {
                    if ($k == $f && false !== stripos($v, $ky)) {
                        return true;
                    }
                }
            }
        }
    } else {
        foreach ($keyword as $ky) {
            foreach ($array as $k => $v) {
                if (stripos($v, $ky)) {
                    return true;
                }
            }
        }
    }
    return false;
}

function dateFormat($format, $date)
{
    return date($format, strtotime($date));
}

function ifnull($val, $default)
{
    if (!$val) {
        return $default;
    }
    return $val;
}

/**
 * 微信获取用户信息
 * @param $appid AppID
 * @param $appsecret  AppSecret
 * @return string  返回用户信息或错误信息
 */
function getWxUserinfo($appid, $appsecret)
{
    function getAuthorizeCode($appid)
    {
        $baseUrl = urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING']);
        header('location:https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $appid . '&redirect_uri=' . $baseUrl . '&response_type=code&scope=snsapi_userinfo&state=123');
        exit();
    }

    function getAccessToken($appid, $appsecret, $code)
    {
        $token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appid . '&secret=' . $appsecret . '&code=' . $code . '&grant_type=authorization_code';
        $token = json_decode(file_get_contents($token_url), true);
        // echo $token;
        if (isset($token['errcode'])) {
            $ret_arr['errcode'] = $token['errcode'];
            $ret_arr['err'] = $token['errmsg'];
            return $ret_arr;
        }
        $access_token_url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=' . $appid . '&grant_type=refresh_token&refresh_token=' . $token['refresh_token'];
        //转成对象
        $acc = file_get_contents($access_token_url);
        $access_token = json_decode($acc, true);
        return $access_token;
    }

    $code = getGet('code');
    $state = getRequest("state");

    //echo $code;
    $ret_arr = array();

    $session_wx_userinfo = getSession("wx_user_info");
    if ($session_wx_userinfo) {
        return $session_wx_userinfo;
    }
    if (!$code) {
        getAuthorizeCode($appid);
    }
    $access_token = getAccessToken($appid, $appsecret, $code);
    //file_put_contents("./temp/access_token.txt", json_encode($access_token));
//    var_dump($access_token);
//    echo "<br>-----<br>";
//    echo "code:" . $code;
//    echo "<br>-----<br>";
//    var_dump($access_token);
//    echo "<br>-----<br>";
    if (isset($access_token["errcode"])) {
        if ($access_token["errcode"] === 40163) {
            getAuthorizeCode($appid);
        }
        $ret_arr['errcode'] = $access_token["errcode"];
        $ret_arr['err'] = isset($access_token["errmsg"]) ? $access_token["errmsg"] : "AccessToken获取错误";
        return $ret_arr;
    }

    $user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $access_token['access_token'] . '&openid=' . $access_token['openid'] . '&lang=zh_CN';
//转成数组
    $user_info = json_decode(file_get_contents($user_info_url), true);
//    echo $access_token;
    if (isset($user_info['errcode'])) {
        $ret_arr['errcode'] = $user_info['errcode'];
        $ret_arr['err'] = '用户信息获取错误：' . isset($user_info['errmsg']);
        return $ret_arr;
    }
    //file_put_contents("./temp/user_info.txt", json_encode($user_info));
    setSession("wx_user_info", $user_info);
    return $user_info;
}

/** 生成XLS文件，并下载
 * @param string $filename 文件名
 * @param string $tables_string 完整的表格
 */
function echo_XLS_Content($filename = '', $tables_string = "")
{
    function getHeaderXls($filename = '')
    {
        if (!$filename) {
            $filename = "表格-" . getNow('', '', '');
        }
        header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Transfer-Encoding: binary ");
        header("Content-Disposition:attachment;filename=$filename.xls");
    }

    getHeaderXls($filename);
    $html_header = '<!doctype html><html lang="zh"><head><meta charset="UTF-8"><title></title></head><body>';
    echo $html_header . $tables_string . "<body></html>";
}

/** 别名，生成XLS文件，并下载
 * @param string $filename 文件名
 * @param string $tables_string 完整的表格
 */
function downloadXLS($filename = '', $tables_string = "")
{
    echo_XLS_Content($filename = '', $tables_string = "");
}


/*
 时间到时间描述
 time 是时间戳
 */
function date2info($time = NULL)
{
    $text = '';
    $time = $time === NULL || $time > time() ? time() : intval($time);
    $t = time() - $time; //时间差 （秒）
    $y = date('Y', $time) - date('Y', time());//是否跨年
    switch ($t) {
        case $t == 0:
            $text = '刚刚';
            break;
        case $t < 60:
            $text = $t . '秒前'; // 一分钟内
            break;
        case $t < 60 * 60:
            $text = floor($t / 60) . '分钟前'; //一小时内
            break;
        case $t < 60 * 60 * 24:
            $text = floor($t / (60 * 60)) . '小时前'; // 一天内
            break;
        case $t < 60 * 60 * 24 * 3:
            $text = floor($t / (60 * 60 * 24)) == 1 ? '昨天 ' . date('H:i', $time) : '前天 ' . date('H:i', $time); //昨天和前天
            break;
        case $t < 60 * 60 * 24 * 30:
            $text = date('m月d日 H:i', $time); //一个月内
            break;
        case $t < 60 * 60 * 24 * 365 && $y == 0:
            $text = date('m月d日', $time); //一年内
            break;
        default:
            $text = date('Y-m-d H:i', $time); //一年以前
            break;
    }
    return $text;
}


/**
 * 高效取出两个数组的差集 ,比原生的array_diff快N倍
 * warning: 函数体内 $array_2 会被键值对调，如果有重复的元素将不能使用这个函数
 */
function array_diff_fast($array_1, $array_2)
{
    $array_2 = array_flip($array_2);
    foreach ($array_1 as $key => $item) {
        if (isset($array_2[$item])) {
            unset($array_1[$key]);
        }
    }

    return $array_1;
}

/**
 * 为url自动添加上http://
 * @param string $url
 * @return string $url
 */
function add_http($url)
{
    if (!preg_match("/^(http|https):/i", trim($url))) $url = 'http://' . $url;

    return $url;
}

function add_https($url)
{
    if (!preg_match("/^(http|https):/i", trim($url))) $url = 'https://' . $url;

    return $url;
}

/**
 * 输出指定数量的换行标签
 * @param int $num
 */
function br($num = 1)
{
    return str_repeat("<br />", $num);
}

/**
 * 获取Unix时间戳浮点数
 * 参数为空返回当前Unix时间戳浮点数
 * 参数为 start 或 s :标记程序开始时间浮点数；返回空
 * 参数为 end 或 e :返回程序执行所消耗的时间
 * @return 单位：秒
 * eg:
 * current_microtime('start');
 * sleep(3);
 * echo current_microtime('end');
 */
function current_microtime($type = '')
{

    $current_time = microtime(true);

    if ('' == $type) return $current_time;

    static $_start_time;

    switch ($type) {
        case 'start':
        case 's':
            $_start_time = $current_time;
            break;

        case 'end':
        case 'e':
            if (empty($_start_time)) return 'not found start position.';
            $use_time = $current_time - $_start_time;
            unset($_start_time);
            return $use_time;
            break;

        default:
            return $current_time;
            break;
    }

}

/**
 * 有些服务器禁止使用file_get_contents()函数，代替file_get_contents函数的curl
 * @param string $url
 * @param array $options
 */

function curl_get_contents($url, $options = array(), $get_code = FALSE)
{
    $ch = curl_init();

    $default_options = array(
        'CURLOPT_URL' => $url,
        'CURLOPT_CUSTOMREQUEST' => 'POST',
        'CURLOPT_RETURNTRANSFER' => 1,
        'CURLOPT_TIMEOUT' => 3,
    );

    $options = $options ? array_merge($default_options, $options) : $default_options;


    foreach ($options as $option => $value) {
        curl_setopt($ch, constant($option), $value);
    }

    $rt = curl_exec($ch);

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    return $get_code ? $httpCode : $rt;

}

/**
 * curl 获取HTTP 状态码
 * @param string $url
 * @param array $options
 */
function curl_get_http_code($url, $options = array())
{
    return curl_get_contents($url, $options, true);
}

/**
 * 使用虚假IP，发起CURL请求
 * @param string $url
 * @param string $ip
 * @param array $options
 */
function curl_fake_ip($url, $ip = '', $options = array())
{

    $ip = $ip ?: make_ip();

    $client_ip = 'CLIENT-IP:' . $ip;
    $x_forwarded_for = 'X-FORWARDED-FOR:' . $ip;

    $http_header = array($client_ip, $x_forwarded_for);

    if ($options && array_key_exists('CURLOPT_HTTPHEADER', $options))
        $http_header = array_merge($http_header, $options['CURLOPT_HTTPHEADER']);

    $request_data = array('CURLOPT_HTTPHEADER' => $http_header);

    $options = $options ? array_merge($options, $request_data) : $request_data;

    return curl_get_contents($url, $options);

}

/**
 * 随机生产一个有效的IP地址
 */
function make_ip()
{
    return rand(1, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255);
}


/**
 * 友好的输入变量 dump [+ die]
 * @param mixed $var
 * @param bool $die
 */

function dd($var, $die = false)
{
    echo '<pre>';

    var_dump($var);

    echo '</pre>';

    if (!$die) die;
}

/**
 * 文件下载
 * @param string $file
 */
function download_file($file)
{


    $file = parse_document_root($file);

    if ((isset($file)) && (file_exists($file))) {
        header("Content-length: " . filesize($file));
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        return readfile("$file");
    } else {
        return "File Not Found!";
    }
}


/**
 * 获取当前顶级域名
 */
function top_domain()
{
    $host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'] . ($_SERVER['SERVER_PORT'] == '80' ? '' : ':' . $_SERVER['SERVER_PORT']));

    $host = strtolower($host);
    if (strpos($host, '/') !== false) {
        $parse = @parse_url($host);
        $host = $parse['host'];
    }
    $topleveldomaindb = array('com', 'edu', 'gov', 'int', 'mil', 'net', 'org', 'biz', 'info', 'pro', 'name', 'museum', 'coop', 'aero', 'xxx', 'idv', 'mobi', 'cc', 'me', 'asia', 'mobi', 'tech', 'wang');
    $str = '';
    foreach ($topleveldomaindb as $v) {
        $str .= ($str ? '|' : '') . $v;
    }
    $matchstr = "[^\.]+\.(?:(" . $str . ")|\w{2}|((" . $str . ")\.\w{2}))$";
    if (preg_match("/" . $matchstr . "/ies", $host, $matchs)) {
        $domain = $matchs['0'];
    } else {
        $domain = $host;
    }
    return $domain;
}


/**
 * 把HTMl代码转换为实体,支持多维数组递归转换
 *
 * @param  mixed $value
 * @return string
 */
function e($value)
{
    return is_array($value) ? array_map('e', $value) : htmlentities($value, ENT_QUOTES, 'UTF-8', false);
}


/**
 * 检测是否是Ajax提交
 *
 * @return void
 */
function is_ajax_request()
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
}

/**
 * 判断是否SSL协议
 * @return boolean
 */
function is_ssl()
{
    if (isset($_SERVER['HTTPS']) && ('1' == $_SERVER['HTTPS'] || 'on' == strtolower($_SERVER['HTTPS']))) {
        return true;
    } elseif (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) {
        return true;
    }
    return false;
}

/**
 * 检测是否是本地IP
 */
function is_local_ip()
{
    $serverIP = $_SERVER['SERVER_ADDR'];
    if ($serverIP == '127.0.0.1') return true;
    if (strpos($serverIP, '10.60') !== false) return false;
    return !filter_var($serverIP, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE);
}

/**
 * 检测是否是邮箱地址
 */
function is_email($str)
{
    return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
}


/**
 * 检测是否是UTF8字符集
 */
function is_utf8($string)
{
    $c = 0;
    $b = 0;
    $bits = 0;
    $len = strlen($string);

    for ($i = 0; $i < $len; $i++) {
        $c = ord($string[$i]);

        if ($c > 128) {
            if (($c >= 254)) return false;
            elseif ($c >= 252) $bits = 6;
            elseif ($c >= 248) $bits = 5;
            elseif ($c >= 240) $bits = 4;
            elseif ($c >= 224) $bits = 3;
            elseif ($c >= 192) $bits = 2;
            else return false;

            if (($i + $bits) > $len) return false;

            while ($bits > 1) {
                $i++;
                $b = ord($string[$i]);
                if ($b < 128 || $b > 191) return false;
                $bits--;
            }
        }
    }
    return true;
}


/*
* 列出目录下的所有匹配文件
* @param string $dir
* @param string $pattern
* @return array $files
*/
function ls($dir, $pattern = '')
{
    $files = array();
    $dir = realpath($dir);
    if (is_dir($dir)) $files = glob($dir . DIRECTORY_SEPARATOR . '*' . $pattern);
    return empty($files) ? array() : $files;
}

/**
 * 记录调试信息到文件
 * @param mixed $msg ,支持直接传入数组
 * @param string $filename ; @default = debug_时间年月日.txt
 * @param string $path ; @default = "."; 根目录请传参： “/”
 */
function log_record($msg = '', $filename = '', $path = '.')
{
    static $debug_log_record_number = 0;
    $debug_log_record_number++;

    @$msg = is_array($msg) || is_object($msg) ? var_export($msg, true) : $msg;

    $function_call_info = debug_backtrace();
    $function_call_info = array_shift($function_call_info);


    $delimit = str_repeat('-', 30) . PHP_EOL;

    $message = '[ #' . $debug_log_record_number . ' ]' . PHP_EOL;
    $message .= $delimit;
    $message .= '[DATE] ' . date('Y-m-d H:i:s') . PHP_EOL;
    $message .= '[URI] ' . $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . PHP_EOL;
    $message .= '[REMOTE_ADDR] ' . $_SERVER['REMOTE_ADDR'] . PHP_EOL;
    $message .= '[POSITION] ' . $function_call_info['file'] . ':' . $function_call_info['line'] . PHP_EOL;
    $message .= '[MESSAGE] ' . $msg . PHP_EOL;
    $message .= $delimit . PHP_EOL;

    $filename = $filename ? rtrim($filename, '.txt') . '.txt' : 'debug_' . date('Ymd') . '.txt';

    if ($path == '/') $path = $_SERVER['DOCUMENT_ROOT'];


    return file_put_contents(rtrim($path, '/') . '/' . $filename, $message, FILE_APPEND);
}


/**
 * 递归创建目录
 * @example  mkdirs('/a/b/c/d');
 */
function mkdirs($dir, $mode = 0777)
{
    if (substr($dir, 0, 1) == '/') $dir = $_SERVER['DOCUMENT_ROOT'] . $dir;

    if (is_dir($dir) || @mkdir($dir, $mode)) return TRUE;
    if (!mkdirs(dirname($dir), $mode)) return FALSE;

    return @mkdir($dir, $mode);
}


/**
 * 伪造404页面
 */
function not_found()
{
    send_http_status(404);

    exit('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL ' . $_SERVER["SCRIPT_NAME"] . ' was not found on this server.</p></body></html>');
}

/**
 * 把“/”解析为根目录
 * @param string $path
 * @return string $path
 */
function parse_document_root($path)
{
    return substr($path, 0, 1) == '/' ? $_SERVER['DOCUMENT_ROOT'] . $path : $path;
}


/**
 * 生产一个唯一的订单号
 * @param string $prefix
 * @return string $order_no
 */
function build_order_no($prefix = '')
{
    return $prefix . date('YmdHi') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
}


/**
 * 生产一个可指定长度的随机字符串
 * @param number $length zh - max:1000
 * @param string $type {1:0-9 ; a:a-z ; A:A-Z ; default:mixed}
 * @return string
 */
function build_random($length = 16, $type = "mix")
{

    switch ($type) {
        case '1':
        case 'num':
            $pool = '0123456789';
            break;
        case 'a':
        case 'lower':
            $pool = 'abcdefghijklmnopqrstuvwxyz';
            break;
        case 'A':
        case 'upper':
            $pool = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            break;
        case 'zh':
        case 'cn':
            $pool = simple_chinese(true);
            return join("", array_rand(array_flip($pool), ($length > 1000) ? 1000 : $length));
            break;
        case 'mix':
        default:
            $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            break;
    }

    return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);

}

/**
 * 获取中文
 * return string or array/string $chinese
 */
function simple_chinese($type = false)
{
    $pool = '们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩康遵牧遭幅园腔订香肉弟屋敏恢忘编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航衣孙龄岭骗休借';

    if ($type) {
        preg_match_all("/./su", $pool, $array);

        return $array[0];
    }

    return $pool;
}


/**
 * 去除BOM
 */
function remove_utf8_bom($string)
{
    if (substr($string, 0, 3) == pack('CCC', 239, 187, 191)) return substr($string, 3);

    return $string;
}

/**
 * 去除JS的代码
 */
function remove_script($string)
{
    return preg_replace("'<script(.*?)<\/script>'is", "", $string);
}


/**
 * URL重定向
 * @param string $url 重定向的URL地址
 * @param integer $time 重定向的等待时间（秒）
 * @param string $msg 重定向前的提示信息
 * @return void
 */
function redirect($url, $time = 0, $msg = '')
{
    //多行URL地址支持
    $url = str_replace(array("\n", "\r"), '', $url);
    if (empty($msg))
        $msg = "Automatically jump to {$url} after {$time} seconds.";
    if (!headers_sent()) {
        // redirect
        if (0 === $time) {
            header('Location: ' . $url);
        } else {
            header("refresh:{$time};url={$url}");
            echo($msg);
        }
        exit();
    } else {
        $str = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
        if ($time != 0)
            $str .= $msg;
        exit($str);
    }
}

/**
 * 发送HTTP状态
 * @param integer $code 状态码
 * @return void
 */
function send_http_status($code)
{
    $_status = array(
        200 => 'OK',
        301 => 'Moved Permanently',
        302 => 'Moved Temporarily ',
        400 => 'Bad Request',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error',
        503 => 'Service Unavailable',
    );
    if (isset($_status[$code])) {
        header('HTTP/1.1 ' . $code . ' ' . $_status[$code]);
        // 确保FastCGI模式下正常
        header('Status:' . $code . ' ' . $_status[$code]);
    }
}


/**
 * 退出,清空session
 */
function session_logout()
{
    session_destroy();
    unset($_SESSION);
}

/**
 * 增强版 截取字符串，支持多语言，例如中文
 * @param string $string
 * @param int $offset 非负，从0计数
 * @param int $length 截取长度
 * @param string $append 结尾拼接字符，如“...”
 * @return string $string
 * @example str_slice($chinese , 0, 5 ,'...');
 */
function str_slice($string, $offset = 0, $length = '')
{
    if ('' == $length) {
        $length = $offset;
        $offset = 0;
    }
    if (function_exists('mb_substr')) return mb_substr($string, $offset, $length, 'utf-8');

    preg_match_all("/./su", $string, $data);

    return join("", array_slice($data[0], $offset, $length));
}


/**
 * 让 str_replace() 只替换一次
 * @param $needle 将要被替换的内容
 * @param $replace 替换后的内容
 * @param $haystack 需要处理的内容
 */
function str_replace_once($needle, $replace, $haystack)
{

    $pos = strpos($haystack, $needle);

    if ($pos === false) return $haystack;

    return substr_replace($haystack, $replace, $pos, strlen($needle));
}


/**
 * 去除代码中的空白和注释
 * @param string $content 代码内容
 * @return string
 */
function strip_php_whitespace($content)
{
    $stripStr = '';
    //分析php源码
    $tokens = token_get_all($content);
    $last_space = false;
    for ($i = 0, $j = count($tokens); $i < $j; $i++) {
        if (is_string($tokens[$i])) {
            $last_space = false;
            $stripStr .= $tokens[$i];
        } else {
            switch ($tokens[$i][0]) {
                //过滤各种PHP注释
                case T_COMMENT:
                case T_DOC_COMMENT:
                    break;
                //过滤空格
                case T_WHITESPACE:
                    if (!$last_space) {
                        $stripStr .= ' ';
                        $last_space = true;
                    }
                    break;
                case T_START_HEREDOC:
                    $stripStr .= "<<<HEREDOC\n";
                    break;
                case T_END_HEREDOC:
                    $stripStr .= "HEREDOC;\n";
                    for ($k = $i + 1; $k < $j; $k++) {
                        if (is_string($tokens[$k]) && $tokens[$k] == ';') {
                            $i = $k;
                            break;
                        } else if ($tokens[$k][0] == T_CLOSE_TAG) {
                            break;
                        }
                    }
                    break;
                default:
                    $last_space = false;
                    $stripStr .= $tokens[$i][1];
            }
        }
    }
    return $stripStr;
}


/**
 * 删除php源码中的注释
 * @param string $content
 * @return No Comment content
 */
function strip_php_comment($content)
{
    $stripStr = '';
    //分析php源码
    $tokens = token_get_all($content);

    for ($i = 0, $j = count($tokens); $i < $j; $i++) {
        if (is_string($tokens[$i])) {

            $stripStr .= $tokens[$i];
        } else {
            switch ($tokens[$i][0]) {
                //过滤各种PHP注释
                case T_COMMENT:
                case T_DOC_COMMENT:
                    break;

                default:
                    $stripStr .= $tokens[$i][1];
            }
        }
    }
    return $stripStr;
}

/* 去除html空格与换行 */
function strip_html_whitespace($content)
{
    $find = array("~>\s+<~", "~>(\s+\n|\r)~");
    $replace = array('><', '>');

    return preg_replace($find, $replace, $content);
}


/**
 * XML编码
 * @param mixed $data 数据
 * @param string $encoding 数据编码
 * @param string $root 根节点名
 * @return string
 */
function xml_encode($data, $encoding = 'utf-8', $root = 'root')
{
    $xml = '<?xml version="1.0" encoding="' . $encoding . '"?>';
    $xml .= '<' . $root . '>';
    $xml .= data_to_xml($data);
    $xml .= '</' . $root . '>';
    return $xml;
}

/**
 * 数据XML编码
 * @param mixed $data 数据
 * @return string
 */
function data_to_xml($data)
{
    $xml = '';
    foreach ($data as $key => $val) {
        is_numeric($key) && $key = "item id=\"$key\"";
        $xml .= "<$key>";
        $xml .= (is_array($val) || is_object($val)) ? data_to_xml($val) : $val;
        list($key,) = explode(' ', $key);
        $xml .= "</$key>";
    }
    return $xml;
}


/**
 * 把驼峰命名法转换成下划线拼接命名法
 * @param string $str
 * @return string
 */
function camecase_to_underline($str)
{
    $temp_array = array();
    for ($i = 0; $i < strlen($str); $i++) {
        $ascii_code = ord($str[$i]);
        if ($ascii_code >= 65 && $ascii_code <= 90) {
            if ($i == 0) {
                $temp_array[] = chr($ascii_code + 32);
            } else {
                $temp_array[] = '_' . chr($ascii_code + 32);
            }
        } else {
            $temp_array[] = $str[$i];
        }
    }
    return implode('', $temp_array);

}

/**
 * 把下划线拼接命名法转换成驼峰命名法
 * @param string $str
 * @param boolean $ucfirst
 * @return string
 */
function underline_to_camecase($str, $ucfirst = true)
{
    $str = explode('_', $str);

    foreach ($str as $key => $val)
        $str[$key] = ucfirst($val);

    if (!$ucfirst)
        $str[0] = strtolower($str[0]);

    return implode('', $str);
}

/**
 * 获取内存使用情况
 * @return string
 */
function memory_usage()
{
    return (!function_exists('memory_get_usage')) ? '0' : round(memory_get_usage() / 1024 / 1024, 2) . 'MB';

}


/**
 * 字节格式化 把字节数格式为 B K M G T 描述的大小
 * @return string
 */
function byte_format($size, $dec = 2)
{
    $unit = array("B", "KB", "MB", "GB", "TB", "PB");
    $pos = 0;
    while ($size >= 1024) {
        $size /= 1024;
        $pos++;
    }
    return round($size, $dec) . " " . $unit[$pos];
}


/**
 * 检测是否是一个匿名函数
 * @param mixed $var
 * @return boolean
 */
function is_closure($var)
{
    return $var instanceof Closure;
}

/**
 * Return the default value of the given value.
 *
 * @param  mixed $value
 * @return mixed
 */
function value($value)
{
    return $value instanceof Closure ? $value() : $value;
}

/**
 * 备案号外链,工业和信息化部（工信部）网站地址
 */
function icp_link()
{
    return 'http://www.miitbeian.gov.cn';
}


function get_errPage($title, $body)
{
    $html = <<<HTML
<!doctype html><html lang="zh"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"><meta http-equiv="X-UA-Compatible" content="ie=edge"><title>$title</title></head>
<body style="text-align: center;color: #e7041a;font-size: 1rem;">$body</body><html>
HTML;
    return $html;
}

function get_useWXPage()
{
    $html = <<<HTML
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
    <title>请在微信中打开</title>
</head>
<body style="background-color: #e1e0de">
<div style="text-align: center">
    <img style="border: 0" src="data:image/jpeg;base64,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"
         alt="">
</div>
</body>
</html>
HTML;
    return $html;
}


/*将$content存储到磁盘，并返回md5，下次可以通过该md5提取存储的内容*/
function setDataToShort($content)
{
    $u = md5($content);
    $u = strtolower($u);

    $left2 = substr($u, 0, 2);
    if (!file_exists("./short/{$left2}/{$u}")) {
        @mkdir("./short/{$left2}", 0777);
        file_put_contents("./short/{$left2}/{$u}", $content);
    }
    return $u;
}

/**根据short获取内容
 * @param $md5_cc
 * @return bool|string
 */
function getDataByShort($md5_cc)
{
    $u = strtolower($md5_cc);
    $left2 = substr($u, 0, 2);
    $info = "";
    if (file_exists("./short/{$left2}/{$u}")) {
        $info = file_get_contents("./short/{$left2}/{$u}");
    }
    return $info;
}


class allowIp
{
//使用方法
    /*
    $ips = array("127.0.0.1", "115.159.24.194", "122.225.0.194");
    $oBlock_ip = new allowIp($ips);

    //检测IP，仅限本机访问
    if (!$oBlock_ip->checkIP()) {
        echo "禁止访问";
        exit();
    }
    */


    function __construct($allow_ip)
    {
        if (empty($allow_ip)) {
            return false;
        }
        $this->allow_ip = $allow_ip;
        $this->ip = '';
    }


    private function makePregIP($str)
    {
        if (strstr($str, "-")) {
            $aIP = explode(".", $str);
            $preg_limit = "";
            foreach ($aIP as $k => $v) {
                if (!strstr($v, "-")) {
                    $preg_limit .= $this->makePregIP($v);
                    $preg_limit .= ".";
                } else {
                    $aipNum = explode("-", $v);
                    $preg = "";
                    for ($i = $aipNum[0]; $i <= $aipNum[1]; $i++) {
                        $preg .= $preg ? "|" . $i : "[" . $i;
                    }
                    $preg_limit .= strrpos($preg_limit, ".", 1) == (strlen($preg_limit) - 1) ? $preg . "]" : "." . $preg . "]";
                }
            }
        } else {
            $preg_limit = $str;
        }
        return $preg_limit;
    }

    private function getAllBlockIP()
    {
        if ($this->allow_ip) {
            $i = 1;
            foreach ($this->allow_ip as $k => $v) {
                $ipaddres = $this->makePregIP($v);
                $ip = str_ireplace(".", ".", $ipaddres);
                $ip = str_replace("*", "[0-9]{1,3}", $ip);
                $ipaddres = "/" . $ip . "/";
                $ip_list[] = $ipaddres;
                $i++;
            }
        }
        return $ip_list;
    }

    public function checkIP()
    {
        $iptable = $this->getAllBlockIP();
        $IsJoined = false;
        $Ip = $this->get_client_ip();
        $Ip = trim($Ip);
        if ($iptable) {
            foreach ($iptable as $value) {
                if (preg_match("{$value}", $Ip)) {
                    $IsJoined = true;
                    break;
                }
            }
        }
        if (!$IsJoined) {
            return false;
        }
        return true;
    }

    private function get_client_ip()
    {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
            $ip = getenv("REMOTE_ADDR");
        } else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = "unknown";
        }
        $this->ip = $ip;
        return ($ip);
    }
}



function getRunTime($mode = 0)
{
    static $t;
    if (!$mode) {
        $t = microtime();
        return;
    }
    $t1 = microtime();
    //list($m0,$s0) = split(" ",$t);
    list($m0, $s0) = explode(" ", $t);
    //list($m1,$s1) = split(" ",$t1);
    list($m1, $s1) = explode(" ", $t1);
    return sprintf("%.3f ms", ($s1 + $m1 - $s0 - $m0) * 1000);
}
/**
 * URL base64解码
 * '-' -> '+'
 * '_' -> '/'
 * 字符串长度%4的余数，补'='
 * @param unknown $string
 * @return bool|string
 */
function urlsafe_b64decode($string) {

        $data = str_replace(array('-','_'),array('+','/'),$string);

    $mod4 = strlen($data) % 4;

    if ($mod4) {

              $data .= substr('====', $mod4);

    }

    return base64_decode($data);

  }


/**
 * URL base64编码
 * '+' -> '-'
 * '/' -> '_'
 * '=' -> ''
 * @param unknown $string
 * @return mixed|string
 */

function urlsafe_b64encode($string) {

        $data = base64_encode($string);

    $data = str_replace(array('+','/','='),array('-','_',''),$data);

    return $data;

  }
