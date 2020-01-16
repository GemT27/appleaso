<?php
define('QAPP', 1);
//die;
$start_time = microtime(true);
define('admin', true);
require('../inc/config.php');

if (getSession("goto") != "") {
    $gotourl = getSession("goto");
    setSession("goto", "");
    header("Location:" . $gotourl);
    exit();
}

require('chk.php');
//die;
newtoken(10);
if (isset($_GET['do']) && isset($_GET['nohtml'])) {
    if (isset($_GET['do'])) {
        $thisdo = explode('_', $_GET['do']);
    }
    if (!isset($thisdo[1])) {
        $thisdo[1] = 'index';
    }
    check_admin_file($thisdo[0], $thisdo[1]);
    require($thisdo[0] . '/' . $thisdo[1] . '.php');
    die();
}
function check_admin_file($dir, $file)
{
    if (!preg_match("/^[a-z\d]{1,25}$/i", $dir)) {
        die('file error');
    }
    if (!preg_match("/^[a-z\d]{1,25}$/i", $file)) {
        die('file error');
    }
    if (!is_file($dir . '/' . $file . '.php')) {
        die('file not exist');
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>后台管理中心</title>
    <meta name="referrer" content="origin-when-cross-origin">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

    <link href="img/admin.css" rel="stylesheet" type="text/css">
    <script src="img/jquery.min.js"></script>
    <script src="img/js.js"></script>
    <script type="text/javascript">cmsversion = '<?php echo(version);?>';console.log("version:"+cmsversion);</script>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1"/>
    <meta name="renderer" content="webkit">
    <link rel="shortcut icon" href="img/ico.ico">
</head>
<body>
<?php
if (isset($_GET['do'])) {
    $thisdo = explode('_', $_GET['do']);
}
?>
<?php require('top.php'); ?>
<?php
if (isset($_GET['do'])) {
    if (!isset($thisdo[1])) {
        $thisdo[1] = 'index';
    }
    check_admin_file($thisdo[0], $thisdo[1]);
    require($thisdo[0] . '/' . $thisdo[1] . '.php');
} else {
    if (power('s', 0, $power)) {
        echo("<meta http-equiv=refresh content='0; url=?do=str'>");
        exit();
    }
    foreach ($channels as $value) {
        if (power('s', $value['cid'])) {
            if ($value['ckind'] == 1 || $value['ckind'] == 3) {
                $firsturl = '?do=str&cid=' . $value['cid'];
            }
            if ($value['ckind'] == 2) {
                $firsturl = '?do=list&cid=' . $value['cid'];
            }
            if ($value['ckind'] == 4) {
                if ($value['newwindow'] != 1) {
                    $firsturl = $value['cvalue'];
                }
            }
            break;
        }
    }
    if (isset($firsturl)) {
        echo("<meta http-equiv=refresh content='0; url=$firsturl'>");
        exit();
    }

}
?>
<div class="clear"></div>
<div id="UFooter">&copy;<?php echo(date('Y')); ?> <a href="https://www.jxsbox.com" target="_blank">JXSBOX</a>
    <?php
    if (z("开发模式")){
        $end_time = microtime(true);
        $total_time = substr($end_time - $start_time, 0, 5);
        echo " ".$total_time.'ms';
    }else{
         echo "/ base <a href='http://uuu.la' target='_blank'>UCMS</a>";
    }
    ?>
</div>
<div class="clear"></div>
</div>
</body>
</html>
