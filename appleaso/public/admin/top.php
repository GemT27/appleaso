<?php
!defined('QAPP') AND exit('Forbidden');
adminchannelscache();
?>
<div id="UWrap">
    <div id="UHead">
        <div id="head">
            <div class="logo"><a href="index.php"><?php
                    try {
                        echo z("网站标题");
                    } catch (exception $e) {
                        echo "网站后台";
                    }
                    ?></a></div>
            <div class="nav">
                <ul>
                    <li class="onRight">
                    </li>
                </ul>
                <ul class="navRight">

                    <li class="onRight"><a href="<?php echo(gethomeurl()); ?>" target="_blank">前台首页</a></li>
                    <?php
                    if (power('b', 2)) {
                        echo('<li class="onRight"><a href="?do=user_my">个人中心</a></li>');
                    }
                    ?>
                    <?php
                    if (power('alevel') > 1) {
                        echo('<li class="onRight"><a href="?do=user">帐户管理</a></li>');
                    }
                    if (power('alevel') == 3) {
                        echo('<li class="onRight"><a href="?do=sadmin">栏目配置</a></li>');
                        if (AdminFileedit) {
                            echo('<li class="onRight"><a href="?do=sadmin_file">文件管理</a></li>');
                        }
                        echo('<li class="onRight"><a href="?do=str_cache&' . newtoken(3) . '">清空缓存</a></li>');
                    }
                    ?>
                    <li class="onRight"><a href="login.php?do=out&<?php echo(newtoken(2)); ?>">退出</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div id="ULeft">
        <div id="menu">
            <ul>
                <?php if (power('s', 0)) { ?>
                    <li<?php if ((!isset($_GET['cid']) || $_GET['cid'] == 0) && isset($thisdo[0]) && $thisdo[0] == 'str') {
                        echo(' class="on"');
                    } ?>>
                        <a href="?do=str">全局设置</a></li>
                    <p class="line"></p>
                <?php } ?>
                <?php
                if (isset($_GET['cid'])) {
                    $cid = intval($_GET['cid']);
                } else {
                    $cid = 0;
                }
                $mydaddy[0] = 1;
                function getleftlist2($cdaddy = 0)
                {
                    Global $channels;
                    Global $power, $cid;
                    $ifhaveson = 0;
                    foreach ($channels as $value) {
                        if ($value['fid'] == $cdaddy) {
                            if ($value['ifshowleft'] == 1 && $value['ifshowadmin'] == 1 && power('s', $value['cid'], $power)) {
                                $ifhaveson++;
                                $ifhaveson += getleftlist($value['cid']);

                                if ($cid == $value['cid']) {
                                    $thisstr = ' class="on"';
                                } else {
                                    $thisstr = '';
                                }

                                if ($value['fid'] != 0) {
                                    $thisstr .= ' style="margin-left: 15px;"';
                                }
                                if ($value['ckind'] == 1) {
                                    echo('<li' . $thisstr . ' rel="' . $value['cid'] . '"><a href="?do=str&cid=' . $value['cid'] . '">' . $value['cname'] . '</a></li>');
                                } elseif ($value['ckind'] == 2) {
                                    echo('<li' . $thisstr . ' rel="' . $value['cid'] . '"><a href="?do=list&cid=' . $value['cid'] . '">' . $value['cname'] . '</a></li>');
                                } elseif ($value['ckind'] == 3) {
                                    echo('<li' . $thisstr . ' rel="' . $value['cid'] . '"><a href="?do=str&cid=' . $value['cid'] . '">' . $value['cname'] . '</a></li>');
                                } elseif ($value['ckind'] == 4) {
                                    if ($value['newwindow'] == 1) {
                                        echo('<li' . $thisstr . ' rel="' . $value['cid'] . '"><a href="' . $value['cvalue'] . '" target="_blank">' . $value['cname'] . '</a></li>');
                                    } else {
                                        echo('<li' . $thisstr . ' rel="' . $value['cid'] . '"><a href="' . $value['cvalue'] . '">' . $value['cname'] . '</a></li>');
                                    }
                                } elseif ($value['ckind'] == 5) {
                                    echo('<li' . $thisstr . ' rel="' . $value['cid'] . '"><a href="' . get_transit_channel($value['cid'], 2) . '">' . $value['cname'] . '</a></li>');
                                }
                            }


                            if ($value['fid'] == 0 && $ifhaveson > 0) {
                                echo('<p class="line"></p>');
                                $ifhaveson = 0;
                            }
                        }
                    }

                    Return $ifhaveson;
                }

                function getleftlist($cdaddy = 0, $times = 0)
                {
                    Global $channels;
                    Global $power, $cid;
                    $times++;
                    $mynav = array();
                    foreach ($channels as $value) {
                        if ($value['fid'] == $cdaddy && $times < 5) {
                            if ($value['ifshowleft'] == 1 && $value['ifshowadmin'] == 1 && power('s', $value['cid'], $power)) {
                                if ($cid == $value['cid']) {
                                    $thisstr = ' class="on"';
                                } else {
                                    $thisstr = '';
                                }
                                if ($value['ckind'] == 1) {
                                    $mynav[] = array('url' => '?do=str&cid=' . $value['cid'], 'title' => $value['cname'], 'cid' => $value['cid'], 'fid' => $value['fid'], 'blank' => '');
                                } elseif ($value['ckind'] == 2) {
                                    $mynav[] = array('url' => '?do=list&cid=' . $value['cid'], 'title' => $value['cname'], 'cid' => $value['cid'], 'fid' => $value['fid'], 'blank' => '');
                                } elseif ($value['ckind'] == 3) {
                                    $mynav[] = array('url' => '?do=str&cid=' . $value['cid'], 'title' => $value['cname'], 'cid' => $value['cid'], 'fid' => $value['fid'], 'blank' => '');
                                } elseif ($value['ckind'] == 4) {
                                    if ($value['newwindow'] == 1) {
                                        $mynav[] = array('url' => $value['cvalue'], 'title' => $value['cname'], 'cid' => $value['cid'], 'fid' => $value['fid'], 'blank' => 'target="_blank"');
                                    } else {
                                        $mynav[] = array('url' => $value['cvalue'], 'title' => $value['cname'], 'cid' => $value['cid'], 'fid' => $value['fid'], 'blank' => '');
                                    }
                                } elseif ($value['ckind'] == 5) {
                                    $mynav[] = array('url' => get_transit_channel($value['cid'], 2), 'title' => $value['cname'], 'cid' => $value['cid'], 'fid' => $value['fid'], 'blank' => '');
                                }
                            }
                        }
                    }
                    if (count($mynav) > 0) {
                        foreach ($mynav as $thisnav) {
                            if ($thisnav['fid'] == 0) {
                                echo('<ul rel="' . $thisnav['cid'] . '">');
                            }
                            $style = '';
                            if ($times >= 2) {
                                $style .= 'text-indent:' . (($times - 1) * 10) . 'px;';
                            }
                            if ($cid == $thisnav['cid']) {
                                $ifon = 1;
                                echo('<li class="on" rel="' . $thisnav['cid'] . '" style="' . $style . '"><a href="' . $thisnav['url'] . '"' . $thisnav['blank'] . '>' . $thisnav['title'] . '</a>');
                                run_admin_hook($thisnav['cid'], 'leftnav');
                                echo('</li>' . "\r\n");
                            } else {
                                echo('<li rel="' . $thisnav['cid'] . '" style="' . $style . '"><a href="' . $thisnav['url'] . '"' . $thisnav['blank'] . '>' . $thisnav['title'] . '</a>');
                                run_admin_hook($thisnav['cid'], 'leftnav');
                                echo('</li>' . "\r\n");
                            }
                            getleftlist($thisnav['cid'], $times);
                            if ($thisnav['fid'] == 0) {
                                echo('</ul>');
                            }
                        }
                    }
                    Return count($mynav);
                }

                getleftlist();
                ?>
            </ul>
        </div>
    </div>
    <script type="text/javascript">
        $(document).keydown(function (event) {
            var ctrlc = event.which;
            if (ctrlc == 13 && event.ctrlKey) {
                $('#form1').submit();
            }
            if (event.altKey) {
                <?php
                if (isset($_GET['cid']) && $_GET['cid'] > 0) {
                    if (power('s', intval($_GET['cid']), $power, 1)) {
                        echo('if(ctrlc == 65){window.location.href = "?do=list_add&cid=' . intval($_GET['cid']) . '";}');
                    }
                    if (power('s', intval($_GET['cid']), $power, 4)) {
                        echo('if(ctrlc == 83){window.location.href = "?do=str&cid=' . intval($_GET['cid']) . '";}');
                    }
                    if (power('s', intval($_GET['cid']))) {
                        echo('if(ctrlc == 86){window.location.href = "?do=list&cid=' . intval($_GET['cid']) . '";}');
                    }
                    if (power('alevel') == 3) {
                        echo('if(ctrlc == 90){window.location.href = "?do=sadmin_cedit&cid=' . intval($_GET['cid']) . '";}');
                        echo('if(ctrlc == 88){window.location.href = "?do=sadmin_aindex&cid=' . intval($_GET['cid']) . '";}');
                        echo('if(ctrlc == 67){window.location.href = "?do=sadmin_sbasic&cid=' . intval($_GET['cid']) . '";}');
                    }
                } elseif (!isset($_GET['cid']) && power('alevel') == 3) {
                    echo('if(ctrlc == 67){window.location.href = "?do=sadmin_sbasic";}');
                    echo('if(ctrlc == 90){window.location.href = "?do=sadmin";}');
                }
                if (AdminFileedit && power('alevel') == 3) {
                    echo('if(ctrlc == 70){window.location.href = "?do=sadmin_file";}');
                }
                ?>
                if (ctrlc == 72) {
                    window.open('<?php echo(SystemDir);?>');
                }
            }
        });
        $(function () {
            $('#form1').find("table").eq(0).find("tr").eq(0).find('input[type!=hidden]').eq(0).focus();
            $('#form1').find("table").eq(0).find("tr").eq(0).find('textarea').eq(0).focus();
            $('#form1').find("table").eq(0).find("tr").eq(0).find('select').eq(0).focus();
        });
    </script>