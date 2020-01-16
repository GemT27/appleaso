<?php
!defined('QAPP') AND exit('Forbidden'); ?>
<?php
$fid = getGet('id');
$kind = listFindAnd(33, array("id" => $fid));
if (empty($kind)) {
    redirect("/news");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    {{file res.php}}
    <!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="{{getCss(bootstrap.min)}}">

    <title>news</title>
</head>
<body class="signup">
<div id="app">
    <div id="header">
        <div class="container">
            <div class="sixteen columns">
                <div id="usernav">
                </div>

            </div>
        </div>
    </div>

    <!--菜单栏-->
    <div id="nav-wrap">
        <div class="container">
            <div id="nav">
                <div class="sixteen columns">
                    <ul>
                        <li><img src="{{z(logo图片)}}" style="width:135px;height: 35px" alt=""></li>
                        <li class=""><a href="/">Home</a></li>

                        <li class="selected"><a href="/news">News</a></li>
                        <li class=""><a href="/search">SEARCH</a></li>
                        <li class="last"><a href="/contact">Contact</a></li>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>

    <!-- /#teaser -->
    <div class="container" id="content">
        <div class="aligncenter" id="intro">
            <h1 class="remove-bottom animated fadeIn" style="text-align: center">News</h1>
        </div>

        <div class="btn-group-lg" role="group" aria-label="...">
            <a type="button" class="btn btn-default" href="/news">ALL</a>
            <?php $bankuai = listFindAnd(33, array());
            $label = "";
            for ($i = 0; $i < count($bankuai); $i++) {
                $fenlei = listFindAnd(33, array("label" => $bankuai[$i]["label"]));
                if ($fenlei[0]['id'] == $kind[0]['id']) {
                    $label = $bankuai[$i]['label'];
                    echo '<a type="button" class="btn btn-success" href="/fenlei?id=' . $fenlei[0]['id'] . '">' . $bankuai[$i]['label'] . '</a>&nbsp;';
                } else {
                    echo '<a type="button" class="btn btn-default" href="/fenlei?id=' . $fenlei[0]['id'] . '">' . $bankuai[$i]['label'] . '</a>&nbsp;';
                }
            } ?>

        </div>
        <div class="fifteen columns mv30 offset1" style="width: 1140px">
            <?php
            $t = listFindAnd(34, array());
            ?>
            <?php
            $hot = 0;
            foreach ($t as $k => $v)
                $hot = max($hot, (int)$v['click'])
            ?>
            <div class="panel panel-default">
                <!-- Default panel contents -->
                <div class="panel-heading">&nbsp;&nbsp;<?php echo $label ?></div>
                <ul class="list-group">
                    <!--分页-->
                    {{if count($t)!=0}}
                    {{$set=array()}}
                    {{$set['cid']=34}}
                    {{$set['pagesize']=50}}
                    {{$set['page']=page}}
                    {{$set['where']['label']=$label}}
                    {{$articles=alist($set)}}
                    {{loop $articles['list'] as $a}}
                    <!-- Table -->
                    <li class="list-group-item">
                        <span class="badge"><?php echo $a['click'] ?></span>
                        <a href="<?php echo $a['link']; ?>"><b><?php echo "【" . $a['label'] . "】" . $a['tit'] ?>&nbsp;&nbsp;<?php echo $hot == $a['click'] ? "<span class=\"label label-danger\">Hot</span>" : '' ?></b></a>
                    </li>
                    {{/loop}}
                </ul>
            </div>
            {{//分页:}}
            <ul class="pagelist">{{pagelist($articles)}}</ul>
            {{/if}}
        </div><!--/#grid-->

    </div><!--/#content-->
    <!--页脚-->
    <div id="footer">
        <div class="container">
            <div class="sixteen columns" id="legal">

                <p class="legal">{{z(页脚信息)}}</p>
                <p class="legal">&copy;
                    {{z(版权)}}<br/>
                </p>
                <div class="resources">

                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .pagelist {
        text-align: center;
        padding-top: 20px;
        margin-right: 20px;
    }

    .pagelist li {
        display: inline-block;
        border: solid 1px #ccc;
        margin-right: 2px;
    }

    .fifteen {
        padding: 0;
    }

    .pagelist a {
        display: block;
        padding: 4px 12px;
        color: #666;
        background: #eee;
        text-decoration: none;
    }

    .pagelist li a:hover {
        background: #ccc
    }

    .pagelist li.on a {
        background: #ccc
    }
</style>
</body>
<script src="{{getJs(bootstrap.min)}}"></script>
</html>