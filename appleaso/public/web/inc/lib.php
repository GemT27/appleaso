<?php
!defined('QAPP') AND exit('Forbidden');
/**
 * Created by PhpStorm.
 * User: jxsbox
 * Date: 2018/6/15
 * Time: 下午7:39
 */

/**
 *  从资源文件夹获取样式文件，不用带后缀
 * @param $cssname
 * @return string
 */
function getCss($cssname)
{
    $WebResDir = SystemDir . WebResDir;
    return "{$WebResDir}css/{$cssname}.css";
}

/**
 * 从资源文件夹获取JS文件，不用带后缀
 * @param $jsname
 * @return string
 */
function getJs($jsname)
{
    $WebResDir = SystemDir . WebResDir;
    return "{$WebResDir}js/{$jsname}.js";
}

/**
 * 从资源文件夹获取图片文件，不用带后缀
 * @param $fname
 * @return string
 */
function getImg($fname)
{
    $WebResDir = SystemDir . WebResDir;
//    echo $fname;
    if (is_file("{$WebResDir}img/{$fname}")) {
        return "{$WebResDir}img/{$fname}";
    } elseif (file_exists("{$WebResDir}img/{$fname}.jpg")) {
        return "{$WebResDir}img/{$fname}.jpg";
    } else {
        return "{$WebResDir}img/{$fname}.png";
    }
}

/**
 * 从资源文件夹获取任何文件，需要带上后缀
 * @param $fname
 * @return string
 */
function getAny($fname)
{
    $WebResDir = SystemDir . WebResDir;
    return "{$WebResDir}{$fname}";
}


/**
 * 获取文章的用户名
 * @param $a 传入文章
 * @return string
 */
function getArtAdmin($a)
{
    $userid = $a['adminuid'];
    $admininfo = $query = getDB()->one("SELECT username,nickname FROM " . tableex('admin') . " where id='$userid';");
    if (!$admininfo) {
        $admininfo['username'] = '';
        $admininfo['nickname'] = '';
    }

    return $admininfo['nickname'];
}

/**
 * 获取文章的多图
 * @param $a
 * @param string $liTpl
 * @return string
 * @internal param $article
 */
function getArtImgs($a, $liTpl = "<img src='{img}'>")
{
    $pics = json_decode($a['pics'], 1);
    $imgs = "";
    foreach ($pics as $pic) {
        $imgs .= str_replace("{img}", $pic, $liTpl);
    }
    return $imgs;
}

/**
 * 获取多图（栏目属性）
 * @param $a
 * @param string $liTpl
 * @return string
 * @internal param $article
 */
function getMuiltImgs($jsonStr, $liTpl = "<img src='{img}'>")
{
    $pics = json_decode($jsonStr, 1);
    $imgs = "";
    if (!$pics) $pics = array();
    foreach ($pics as $pic) {
        $imgs .= str_replace("{img}", $pic, $liTpl);
    }
    return $imgs;
}



/**
 * 获取当前页面的文章，自带点击量增加
 * @return bool
 */
function getThisArt()
{
    $a = _getOneArt();
    if ($a && isset($a['click'])) {
        $a['click']++;
        aEditArr($a['cid'], $a['id'], array("click" => $a['click']));
    }
    return $a;
}


/**
 * 插入日志
 * @param $stype tag
 * @param $content 内容
 * @param string $othermsg 其他内容，一般填写当前信息的触发页面与行数
 * @return bool
 */
function jLog($stype, $content, $othermsg = "")
{

    $article = array();
    $article['cid'] = 14;
    $article['stype'] = $stype;
    $article['content'] = $content;
    $article['shijian'] = getNow();
    $article['page'] = $othermsg;
    $id = ainsert($article);
    if (is_numeric($id)) {
        return true;
    }

    return false;
}

/** 对ucms插入文章的补充
 * @param $cid
 * @param $insertArray
 * @return bool|int
 */
function aInsertArr($cid, $insertArray)
{
    $article = array();
    $article['cid'] = $cid;
    foreach ($insertArray as $k => $v) {
        $article[$k] = $v;
    }

    $id = ainsert($article);
    if (is_numeric($id)) {
        return $id;
    }
    return false;
}

/** 对ucms修改文章的补充
 * @param $cid
 * @param $id
 * @param $editArray
 * @return bool|string
 */
function aEditArr($cid, $id, $editArray)
{
    $article = array();
    $article['cid'] = $cid;
    $article['id'] = $id;
    foreach ($editArray as $k => $v) {
        $article[$k] = dbstr($v);
    }

//    var_dump($article);
    $bool = aedit($article);
    return $bool;
}

/**
 * 查找一篇文章并返回
 * @param $cid
 * @param $whereArray
 * @param bool,int $ison 1,0  false表示不支持此字段
 * @return bool
 */
function aFindAnd($cid, $whereArray, $ison = false)
{
    $set = array();
    $set['cid'] = $cid;
    $set['where'] = array();
    if ($ison === false) {
//不支持此字段
    } else {
        $set['where']['isuse'] = $ison;
    }


    foreach ($whereArray as $k => $v) {
        $set['where'][$k] = dbstr($v);
    }
//    var_dump($set);
    $a = a($set);

    if ($a) {
        return $a;
    } else {
        return false;
    }
}

/**
 * 查找文章列表并返回
 * @param $cid
 * @param $whereArray 填写order表示排序
 *  * @param bool,int $ison 1,0  false表示不支持此字段
 * @return array
 */
function listFindAnd($cid, $whereArray, $ison = false)
{
    $set = array();
    $set['cid'] = $cid;
    $set['where'] = array();
    if ($ison === false) {
//不支持此字段
    } else {
        $set['where']['isuse'] = $ison;
    }
    foreach ($whereArray as $k => $v) {
        if ($k == "order" || $k == "pagesize") {
            $set[$k] = $v;
        } else {
            $set['where'][$k] = $v;
        }


    }
//    var_dump($set);
    $a = alist($set);

    if ($a && $a['list']) {
        return $a['list'];
    } else {
        return array();
    }
}

/** 制作缩略图,支持png和jpg，还能保留png透明度
 * @param $from
 * @param $to
 * @param $towidth
 * @param $toheight
 * @param int|缩放模式，0智能，1按照宽度等比，2按照高度等比，3拉伸 $scaleType 缩放模式，0智能，1按照宽度等比，2按照高度等比，3拉伸
 * @return bool
 * @throws ImagickException
 */
function sltMaker($from, $to, $towidth, $toheight, $scaleType = 0)
{
    $im = new Imagick();
    /* Read the image file */
    $im->readImage($from);

    // 按指定大小生成缩略图，而且不变形，缩略图函数
    list($fw, $fh, $type) = getimagesize($from);

    if ($scaleType == 0) {
        // 使缩略后的图片不变形，并且限制在 缩略图宽高范围内
        if ($fw / $towidth > $fh / $toheight) {
            $toheight = $towidth * ($fh / $fw);
        } else {
            $towidth = $toheight * ($fw / $fh);
        }
    } else if ($scaleType == 1) {
        $toheight = $towidth * ($fh / $fw);
    } else if ($scaleType == 2) {
        $towidth = $toheight * ($fw / $fh);
    } else if ($scaleType == 3) {
        //不处理，写了要多少就多少，进行拉伸
    }
    $im->thumbnailImage($towidth, $toheight);
    $ret = $im->writeImage($to);
    $im->destroy();
    return $ret ? true : false;
}


//临时全局变量
$GTMP = array();

function getGTMP($k, $default = "")
{
    global $GTMP;
    if (isset($GTMP[$k])) {
        return $GTMP[$k];
    } else {
        return $default;
    }
}

function setGTMP($k, $v = "")
{
    global $GTMP;
    $GTMP[$k] = $v;
}

//前端获取用户名
function web_getadminname()
{
    if (isset($_COOKIE['admin_' . cookiehash]) && !empty($_COOKIE['admin_' . cookiehash])) {
        $username = trim($_COOKIE['admin_' . cookiehash]);
        if (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
            return false;
        }
        Return trim($username);
    }
    return false;
}

//前端获取昵称
function web_getadminnick()
{
    if (isset($_COOKIE['nick_' . cookiehash]) && !empty($_COOKIE['nick_' . cookiehash])) {
        $nick = trim($_COOKIE['nick_' . cookiehash]);
        $nick = htmlspecialchars($nick);
        Return $nick;
    }
    return "noname";
}

function web_gotoLoginUrl($backUrl = "/")
{
    return "/admin/login.php?goto=" . urlsafe_b64encode($backUrl);
}