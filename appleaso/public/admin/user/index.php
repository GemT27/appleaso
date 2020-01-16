<?php
if (!defined('admin')) {
    exit();
}
$mylevel = power('alevel');
if ($mylevel < 2) {
    adminmsg('', '无权限', 3);
} else {
    if ($mylevel == 3) {
        $alevel = 3;
    } elseif ($mylevel == 2) {
        $alevel = 2;
    } else {
        $alevel = 0;
    }
}

$tjTable = MainTable;//主文章表
?>
<div id="UMain">
    <div id="urHere"><em class="homeico"></em>后台管理<b>&gt;</b><strong>管理员列表</strong></div>
    <div id="mainBox">
        <h3><a href="?do=user_add" class="actionBtn">添加管理员</a>后台帐户管理</h3>
        <table width="100%" border="0" cellpadding="8" cellspacing="0" class="tableBasic">
            <tr>
                <th width="30" align="center">编号</th>
                <th align="left">管理员名称</th>
                <?php if ($tjTable) { ?>
                    <th align="center">发布文章数量</th>
                <?php } ?>
                <th align="left">管理员账号</th>
                <th align="center">操作</th>
            </tr>
            <?php
            if ($tjTable) {
                $sql = "SELECT *,(select count(id) from " . tableex($tjTable) . " where " .
                    tableex('admin') . ".id=" . tableex($tjTable) . ".adminuid) as count FROM " .
                    tableex('admin') . " where  " . tableex('admin') . ".alevel<=$alevel";
                $alluser = getDB()->all($sql);
//echo $sql;
            } else {
                $alluser = getDB()->all("SELECT * FROM " . tableex('admin') . " where alevel<='$alevel'");
            }
            //统计文章发布的数量
            foreach ($alluser as $link) {
                ?>
                <tr>
                    <td align="center"><?php
                        if ($alevel == 3) {
                            echo($link['id']);
                        } else {
                            echo "*";
                        }
                        ?></td>
                    <td>
                        <?php echo(htmlspecialchars($link['nickname'])); ?>
                    </td>
                    <?php if ($tjTable) { ?>
                        <td align="center"><?php echo($link['count']); ?> 篇</td>
                    <?php } ?>
                    <td align="left">
                        <?php
                        echo($link['username']);
                        if ($alevel == 3) {
                            if ($link['alevel'] == 1) {
                                echo(' (后台用户)');
                            } elseif ($link['alevel'] == 2) {
                                echo(' (管理员)');
                            } elseif ($link['alevel'] == 3) {
                                echo(' <b style="color: #FF0000">(超级管理员)</b>');
                            }
                        }
                        ?>
                    </td>
                    <td align="center">
                        <?php if ($link['alevel'] < $mylevel && $link['username'] != getadminname()): ?>
                            <a href="?do=user_edit&id=<?php echo($link['id']); ?>">修改</a> |
                            <a href="?do=user_del&id=<?php echo($link['id']); ?>&<?php echo(newtoken(2)); ?>"
                               onclick="javascript:if (confirm('确认删除？')){ return true}else{ return false}">删除</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
                <?php
            }
            ?>

        </table>
    </div>
</div>

