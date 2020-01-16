<?php
if (!defined('admin')) {
    exit();
}
unset($link);
if (isset($_GET['cid'])) {
    $cid = intval($_GET['cid']);
    $link = adminchannel($cid);
    if ($link['ifshowadmin'] == 0) {
        adminmsg('', '此栏目已经禁用');
    }
    $csetting = json_decode($link['csetting'], 1);
    $cname = $link['cname'];
    if ($link['ckind'] == 2 && !power('s', $cid, $power, 4)) {
        adminmsg('', '无权限', 3);
    }
} else {
    $cid = 0;
    $cname = '系统设置';
}
if (!power('s', $cid)) {
    adminmsg('', '无权限');
}
?>
<script charset="utf-8" src="img/kindeditor.js"></script>
<div id="UMain">
    <!-- 当前位置 -->
    <div id="urHere"><em class="homeico"></em>后台管理<?php
        if (isset($link)) {
            echo(admin_nav($link));
            if ($link['ckind'] == 2) {
                echo('<b>&gt;</b>设置');
            }
        } else {
            echo('<b>&gt;</b><strong>' . $cname . '</strong> ');
        }
        ?>
        <div class="list">
            <?php
            if ($cid > 0) {
                admin_nav_list($cid);
            }
            ?>
        </div>
    </div>

    <div id="mainBox">
        <h3>
            <?php
            if ($cid > 0 && $link['ckind'] == 2) {
                echo('<a style="margin-left:10px" href="?do=list&cid=' . $cid . '" class="actionBtn">返回</a>');
            }
            ?>


            <?php echo($cname); ?></h3>
        <form id="form1" name="form1" method="post" action="?do=str_editpost&cid=<?php echo($cid); ?>">
            <?php newtoken(); ?>
            <table width="100%" border="0" cellpadding="8" cellspacing="0" class="">
                <?php
if(power('s',$cid,$power,5)) {
	$query = getDB() -> query("SELECT * FROM ".tableex('str')." where strcid='$cid' and inputkind>0 order by strorder");
}else {
	$query = getDB() -> query("SELECT * FROM ".tableex('str')." where strcid='$cid' and inputkind>0 and ifadmin='0' order by strorder");
}
                $strlist = getDB()->fetchall($query);
                if ($cid == 0 && !$strlist && power('alevel') == 3) {
                    echo("<meta http-equiv=refresh content='0; url=?do=install'>");
                    exit();
                }
                foreach ($strlist as $link) {
                    ?>
                    <tr>
                        <?php
                        if (power('alevel') == 3) {
                            ?>
                            <td width="100" align="right"><a href="?do=sadmin_code&id=<?php echo($link['id']);?>&cid=<?php echo($cid);?>&kind=0" tabindex="-1" target="_blank" style="color:#666666"><?php echo($link['strname']);?></a></td>
                            <?php
                        } else {
                            ?>
                            <td width="100" align="right"><?php echo($link['strname']); ?></td>
                            <?php
                        }
                        ?>
                        <td>
                            <?php
                            $strarray = explode('|', $link['strarray']);
                            $thisinput = array(
                                'id' => $link['id'],
                                'from' => 'str',
                                'pictips' => $link['strtip'],
                                'kind' => $link['inputkind'],
                                'inputname' => 'input_' . $link['id'],
                                'inputvalue' => $link['strvalue'],
                                'style' => $link['strstyle'],
                                'strarray' => $strarray
                            );
//                            var_dump($thisinput);
                            htmlinput($thisinput);
                            echo(' ' . $link['strtip']);
                            ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                <?php
                if (power('s', $cid, $power, 4)) {
                    ?>
                    <tr>
                        <td></td>
                        <td>
                            <?php
                            if ($strlist) {
                                echo '<input class="btn" style="width:140px;" type="submit" value="提交" />';
                            }
                            if ($cid > 0 && !$strlist && power('alevel') == 3) {
                                echo('<a href="?do=sadmin_sbasic&cid=' . $cid . '">该栏目尚未添加变量,前去添加</a>');
                            } else if (!$strlist) {
                                echo '<p style="text-align: right;font-size: 24px;color: #0d7fba;font-weight: bold">&uarr;&uarr;&uarr;&uarr;&uarr;&uarr;&uarr;&uarr;&uarr;</p>';
                                echo '<p style="text-align: right">请点击上面的子栏目添加文章</p>';
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </form>
    </div>
</div>