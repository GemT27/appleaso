<?php
if (!defined('admin')) {
    exit();
}
$editorarray = array();
$cid = intval($_GET['cid']);
$link = adminchannel($cid);
if ($link['ifshowadmin'] == 0) {
    adminmsg('', '此栏目已经禁用');
}
$cname = $link['cname'];
$csetting = json_decode($link['csetting'], 1);
if (isset($csetting['listnoedit']) && $csetting['listnoedit'] == 1) {
    adminmsg('', '禁止编辑');
}//禁止修改
if (!power('s', $cid, $power, 2)) {
    adminmsg('', '无权限');
}//没有编辑权限
//文章来源表
if (isset($_GET['articletable']) && !empty($_GET['articletable'])) {
    $articletable = dbstr($_GET['articletable']);
    $articletableuri = '&articletable=' . $articletable;
} else {
    $articletable = '';
    $articletableuri = '';
    if (isset($csetting['articletable'])) {
        $articletable = $csetting['articletable'];
    } else {
        adminmsg('', '栏目尚未配置');
    }
}
if (!admintablecheck($articletable)) {
    adminmsg('', '栏目表名有误');
}
if (isset($_GET['articlemname'])) {
    $articlemname = dbstr(htmlspecialchars($_GET['articlemname']));
    if (isset($_GET['articlechoose'])) {
        $articlechoose = dbstr(htmlspecialchars($_GET['articlechoose']));
        $articleschooseuri = '&articlechoose=' . $articlechoose . '&articlemname=' . $articlemname;
    } elseif (isset($_GET['articleschoose'])) {
        $articleschoose = dbstr(htmlspecialchars($_GET['articleschoose']));
        $articleschooseuri = '&articleschoose=' . $articleschoose . '&articlemname=' . $articlemname;
    } elseif (isset($_GET['allarticleschoose'])) {
        $allarticleschoose = dbstr(htmlspecialchars($_GET['allarticleschoose']));
        $articleschooseuri = '&allarticleschoose=' . $allarticleschoose . '&articlemname=' . $articlemname;
    } else {
        $articleschooseuri = '';
    }
} else {
    $articleschooseuri = '';
}
if (isset($_GET['moudleid']) && isset($_GET['fid'])) {
    $moudleid = dbstr(htmlspecialchars($_GET['moudleid']));
    $fid = intval($_GET['fid']);
    $moudleiduri = '&fid=' . $fid . '&moudleid=' . $moudleid;
} else {
    $moudleiduri = '';
}
$id = intval($_GET['id']);
$delsql = '';//如果栏目开通了普通用户只能管理自己的文章
if (power('sadmin', 0) || power('s', $cid, $power, 5)) {
} else {
    if (isset($csetting['listadminuid']) && $csetting['listadminuid'] == 1) {
        $delsql = " and adminuid='$myadminuid'";
    }
}//普通用户只能编辑自己的文章
$query = getDB()->query("SELECT * FROM $articletable where id='$id' and cid='$cid'$delsql limit 1");
$article = getDB()->fetchone($query);
if (!$article) {
    adminmsg('', '文章不存在');
}
if (power('sadmin', 0) || power('s', $cid, $power, 5)) {
    $thissql = '';
} else {
    $thissql = " and ifadmin<>'1'";
}//不是管理员则不要管理员字段
$query = getDB()->query("SELECT * FROM " . tableex('moudle') . " where hide<>'1' and ifcreated='1' and cid='$cid'$thissql order by morder asc");
$moudle = getDB()->fetchall($query);
run_admin_hook($cid, 'edit');
?>
<script charset="utf-8" src="img/kindeditor.js"></script>
<script>
    function autoSaveImg(url, callback, _textarea) {
        if (url.substring(0, 4).toLowerCase() === "http") {
            $.post("./list/saveimg.php", {img: url, isurl: 1}, function (d) {
                console.log(d);
                callback.call(this, {textarea: _textarea, url: url, data: d});
            }).error(function () {
                callback.call(this, {textarea: _textarea, url: url, data: ""});
            })
        } else {
            var canvas = document.createElement('CANVAS'),
                ctx = canvas.getContext('2d'),
                img = new Image;
            img.crossOrigin = 'Anonymous';
            img.onload = function () {
                canvas.height = img.height;
                canvas.width = img.width;
                ctx.drawImage(img, 0, 0);
                var dataURL = canvas.toDataURL('image/png');
                $.post("./list/saveimg.php", {img: dataURL}, function (d) {
                    console.log(d);
                    callback.call(this, {textarea: _textarea, url: url, data: d});
                }).error(function () {
                    callback.call(this, {textarea: _textarea, url: url, data: ""});
                })
            }

            img.onerror = function () {
                callback.call(this, {textarea: _textarea, url: url, data: ""});
            }

            img.src = url;
        }
    }

    function convertImgToBase64(url, callback, _textarea) {
        var canvas = document.createElement('CANVAS'),
            ctx = canvas.getContext('2d'),
            img = new Image;
        img.crossOrigin = 'Anonymous';
        img.onload = function () {
            canvas.height = img.height;
            canvas.width = img.width;
            ctx.drawImage(img, 0, 0);
            var dataURL = canvas.toDataURL('image/png');
            callback.call(this, {textarea: _textarea, url: url, data: dataURL});
            canvas = null;
        };
        img.onerror = function () {
            callback.call(this, {textarea: _textarea, url: url, data: ""});
        }
        img.src = url;
    }

    var needReplace_arr = [];
    var replace_count = 0;

    function getImages(_this, last = 0) {
        var m_this_submit = $(_this).find("input[type=submit]");
        if (last) {
            m_this_submit.val("编辑");
            _this.submit();
            return;
        }
        var allTextArea = $("textarea").not(".ke-edit-textarea");

        function callback(m_object) {
            needReplace_arr.push(m_object);
            if (m_object && m_object.data) {
                var dd = $(m_object.textarea).val();
                dd = dd.replace(m_object.url, m_object.data);
                $(m_object.textarea).val(dd);
            }

            if (needReplace_arr.length == replace_count) {
                getImages(_this, 1);//提交
            }
        }

        allTextArea.each(function () {
            var m_textarea = this;
            var dd = $(this).val();


            m_this_submit.val("正在上传图片，请稍等...");
            var regx = new RegExp(/<img[\s\S]*?src\s*=\s*[\"|\'](.*?)[\"|\'][\s\S]*?>/gi);
            var oked = dd.replace(regx, function (mm, c) {
                //convertImgToBase64(c, callback, m_textarea);
                if (c.substring(0, 4) == 'http' || c.substring(0, 4) == 'file') {
                    autoSaveImg(c, callback, m_textarea);
                    replace_count++;
                }
            });
        });
        if (replace_count > 0) {
            return false;
        } else {
            return true;
        }


    }
</script>
<div id="UMain">
    <!-- 当前位置 -->
    <div id="urHere"><em class="homeico"></em>后台管理<?php echo(admin_nav($link)); ?></div>
    <div id="mainBox">
        <h3>
            <?php
            echo('<a href="?do=list&cid=' . $cid . $articletableuri . $articleschooseuri . $moudleiduri . '" class="actionBtn" style="margin-left:10px"><em>返回</em></a>');
            ?>
            <?php
            if ($link['ifshow']) {
                $visit = array();
                $visit['cid'] = $cid;
                $visit['where']['id'] = $id;
                $visit_article = a($visit);
                if ($visit_article && !empty($visit_article['link'])) {
                    echo('<a href="' . $visit_article['link'] . '" class="actionBtn" style="margin-left:10px;padding: 0 30px;" target="_blank"><em>浏览</em></a>');
                }
            }
            ?>
            <?php echo($cname); ?></h3>
        <form onsubmit="return getImages(this);" id="form1" name="form1" method="post"
              action="?do=list_editpost&cid=<?php echo($cid); ?><?php echo($articletableuri); ?><?php echo($articleschooseuri); ?><?php echo($moudleiduri); ?>">
            <?php newtoken(); ?>
            <input type="hidden" name="id" value="<?php echo($id); ?>">
            <input type="hidden" name="cid" value="<?php echo($cid); ?>">

            <table width="100%" border="0" cellpadding="8" cellspacing="0" class="">
                <?php
                foreach ($moudle as $value) {
                    if (empty($value['fid'])) {
                        $thisinput = array();
                        $thisinput['style'] = $value['mfunction'];
                        if (isset($article[$value['mname']])) {
                            $strdefault = $article[$value['mname']];
                        } else {
                            $strdefault = '';
                        }
                        ?>
                        <tr<?php if ($value['ifmore'] == 1) {
                            echo(' class="listhidden"');
                            $haveshowmoudle = 1;
                        } ?>>
                            <?php
                            if (power('alevel') >1 && z("开发模式")) {
                                ?>
                                <td height="24"  width="100" align="right">
                                    <?php echo($value['minfo']); ?>
                                    <br>
                                    <span style="color: #999999"><?php echo($value['mname']); ?></span>
                                </td>
                                <?php
                            } else {
                                ?>
                                <td height="24" width="100" align="right"><?php echo($value['minfo']); ?></td>
                                <?php
                            }
                            ?>

                            <td height="24" align="left">
                                <?php
                                $thisinput['pictips'] = $value['mcontent'];
                                $thisinput['id'] = $value['id'];
                                $thisinput['from'] = 'list';
                                $thisinput['strarray'] = explode('|', $value['strarray']);
                                $thisinput['kind'] = $value['mkind'];
                                $thisinput['inputname'] = $value['mname'];
                                $thisinput['inputvalue'] = $strdefault;
                                htmlinput($thisinput);
                                unset($thisinput);
                                ?>

                                <i><?php echo($value['mcontent']); ?></i>
                                <?php
                                //二级字段
                                foreach ($moudle as $value2) {
                                    if ($value2['fid'] == $value['mname']) {
                                        $thisinput2 = array();
                                        $thisinput2['style'] = $value2['mfunction'];
                                        if (isset($article[$value2['mname']])) {
                                            $strdefault = $article[$value2['mname']];
                                        } else {
                                            $strdefault = '';
                                        }
                                        echo('&nbsp;&nbsp;&nbsp;' . $value2['minfo'] . ':&nbsp;');
                                        $thisinput2['pictips'] = $value2['mcontent'];
                                        $thisinput2['id'] = $value2['id'];
                                        $thisinput2['from'] = 'list';
                                        $thisinput2['strarray'] = explode('|', $value2['strarray']);
                                        $thisinput2['kind'] = $value2['mkind'];
                                        $thisinput2['inputname'] = $value2['mname'];
                                        $thisinput2['inputvalue'] = $strdefault;
                                        htmlinput($thisinput2);
                                        unset($thisinput2);
                                        echo('<i>' . $value2['mcontent'] . '</i>');
                                    }
                                }
                                ?>

                            </td>
                        </tr>
                        <?php
                    }//一级字段
                }
                ?>
                <tr>
                    <td></td>
                    <td>
                        <input class="btn" style="width:140px;" type="submit" value="编 辑"/>
                        <?php
                        if (isset($haveshowmoudle)) {
                            ?>
                            <a href="javascript:;" class="listmore">更多</a>
                            <script type="text/javascript">
                                $(document).ready(function () {
                                    $(".listmore").click(function () {
                                        $(".listhidden").toggle();
                                        if ($(".listmore").text() == '隐藏') {
                                            $(".listmore").text('更多');
                                        } else {
                                            $(".listmore").text('隐藏');
                                        }
                                    });
                                });
                            </script>
                            <?php
                        }
                        ?>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
