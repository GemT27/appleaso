<?php
define('QAPP', 1);
ob_start();
define('loginpage', 1);
require('../inc/config.php');
require('chk.php');
require('checkvd.function.php');
ob_clean();
if (isset($_GET['do']) == 'out') {
    setadminname('','');
    setadminpsd('');
    setadmintoken('');
    echo("<meta http-equiv=refresh content='0; url=login.php'>");
    exit();
}

if (isset($_GET['goto'])) {
    setSession("goto", urlsafe_b64decode($_GET['goto']));
}

$login_cachekey = 'login_' . ip();
$try_time = 20;//15分钟内允许尝试的次数
$login_error_time = cacheget($login_cachekey, 900, 'ucmslogin');
if ($login_error_time == false) {
    $login_error_time = 0;
}
if (isset($_GET['code']) && !empty($_GET['code'])) {
    if ($login_error_time <= $try_time) {
        $code = dbstr($_GET['code']);
        $query = getDB()->query("SELECT * FROM " . tableex('admin') . " where ucmsid='$code' and ucmsid<>'' limit 1;");
        $link = getDB()->fetchone($query);
        if ($link) {
            $power = json_decode($link['power'], 1);
            if (!power('b', 1, $power)) {
                $errormsg = '该账户已禁用';
            } else {
                setadminname($link['username'], $link['nickname']);
                setadminpsd($link['psd']);
                cachedel($login_cachekey, 'ucmslogin');
                echo("<meta http-equiv=refresh content='0; url=index.php'>");
                exit();
            }
        } else {
            $errormsg = '尚未绑定该账号,请使用账号密码登录';
            cacheset($login_cachekey, $login_error_time + 1, 900, 'ucmslogin');
        }
    } else {
        $errormsg = '登录过于频繁,请稍后再试';
        cacheset($login_cachekey, $login_error_time + 1, 900, 'ucmslogin');
    }
}
if (isset($_POST['uuu_username'])) {
    if ($login_error_time <= $try_time) {
        if (isset($_POST['uuu_vdtext']) && checkvd($_POST['uuu_vdtext'])) {
            $username = trim(dbstr($_POST['uuu_username']));
            $password = password_md5(trim($_POST['uuu_password']));
            $query = getDB()->query("SELECT * FROM " . tableex('admin') . " where username='$username' and psd='$password'");
            $link = getDB()->fetchone($query);
            if ($link && $password == $link['psd']) {
                $power = json_decode($link['power'], 1);
                if (!power('b', 1, $power)) {
                    $errormsg = '该账户已禁用';
                } else {
                    setadminname($link['username'], $link['nickname']);
                    setadminpsd($link['psd']);
                    cachedel($login_cachekey, 'ucmslogin');
                    echo("<meta http-equiv=refresh content='0; url=index.php'>");
                    exit();
                }
            } else {
                $errormsg = '您填写的账户信息有误';
                cacheset($login_cachekey, $login_error_time + 1, 900, 'ucmslogin');
            }
        } else {
            $errormsg = '验证码错误！';
            cacheset($login_cachekey, $login_error_time + 1, 900, 'ucmslogin');
        }
    } else {
        $errormsg = '登录过于频繁,请稍后再试';
        cacheset($login_cachekey, $login_error_time + 1, 900, 'ucmslogin');
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>后台管理登录</title>
    <meta name="robots" content="noindex,nofollow,nosnippet,noarchive">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="renderer" content="webkit">
    <link rel="stylesheet" href="img/login.css">
    <script type="text/javascript">cmsversion = '<?php echo(version);?>';
        console.log("version:" + cmsversion);</script>
    <script language="javascript" type="text/javascript">
        function checkuser() {
            if (document.getElementById("uuu_username").value == '') {
                alert('请填写用户名');
                document.getElementById("uuu_username").focus();
                return false;
            }
            if (document.getElementById("uuu_password").value == '') {
                alert('请填写密码');
                document.getElementById("uuu_password").focus();
                return false;
            }
            if (document.getElementById("uuu_vdtext").value == '') {
                alert('请填写验证码');
                document.getElementById("uuu_vdtext").focus();
                return false;
            }
            return true;
        }
    </script>
    <script src="img/jquery.min.js"></script>
    <?php
    if (isset($errormsg)) {
        echo("<script type=\"text/javascript\">alert('" . $errormsg . "')</script>");
    }
    ?>
</head>
<body>

<div class="outer_wrap">
    <div class="admin_login_wrap middle">
        <div class="admin_input">
            <div class="logo">
            </div>
            <form name="form1" method="post" action="" onSubmit="return checkuser();">
                <h1 style="margin-top: 0;margin-bottom: 20px; font-weight: 300;font-size: 24px;font-family: 'Open Sans', sans-serif;">
                    登录</h1>
                <p>
                    <input type="text" tabindex="1" name="uuu_username" value="" id="uuu_username"
                           class="admin_input_style" autocomplete="off" placeholder="用户名"/>
                </p>

                <p><input type="password" tabindex="2" name="uuu_password" value="" id="uuu_password"
                          class="admin_input_style" placeholder="密码"/></p>


                <p><input type="vdtext" tabindex="3" name="uuu_vdtext" value="" id="uuu_vdtext"
                          class="admin_input_style vdt" autocomplete="off" placeholder="验证码"/>
                    <img id="vdimgck" align="absmiddle" onClick="this.src=this.src+'?'" style="cursor: pointer;"
                         alt="看不清？点击更换" title="看不清？点击更换" src="../vdk/vdimgck.php"/> 点击图片刷新</p>
                <p class="btn_login_p"><input type="submit" tabindex="4" value="登录" class="submitbtn"/></p>
            </form>
        </div>
    </div>
</div>

<p class="admin_copyright">&copy;<?php echo(date('Y')); ?></a>
</p>
</body>
</html>