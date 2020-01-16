<?php
!defined('QAPP') AND exit('Forbidden'); ?>

<?php
$tb = aFindAnd(34, array("id" => getGet("id")));
?>
<!--浏览量-->
{{$editinfo=array()}}
{{$editinfo['id']=$tb['id']}}
{{$editinfo['click']=$tb['click']+1}}
{{$return=aedit($editinfo)}}
<!DOCTYPE html>
<html lang="en">
<head>
    {{file res.php}}
    <title><?php echo $tb['tit']; ?></title>
</head>
<body class="blog">
<div id="header">
    <div class="container">
        <div class="sixteen columns">
            <div id="usernav">

            </div>

        </div>
    </div>
</div>
<!-- /#header -->
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
<div class="container" id="content">
    <div class="eleven columns" style="width: 103%">
        <div class="entry">
            <h1 class="entry-title bold tighten equal remove-top"><?php echo "【" . $tb['label'] . "】 " . $tb['tit'] ?></h1>
            <ul class="blogmeta">
                <li class="blog_dateposted"><?php echo date("Y-m-d H:i", $tb['shijian']) ?></li>
            </ul>
            <div class="intro">
                <!--正文-->
                <?php echo $tb['neirong']; ?>
            </div>
        </div>
        <div class="clear"></div>
        {{$prevset=array()}}
        {{$prevset['column']=id,tit}}
        {{$prevset['order']=id desc}}
        {{$prevset['other']=`"id<".$tb['id']}}
        {{$prevset['where']['label']=$tb['label']}}
        {{$prev=a($prevset)}}
        {{if $prev}}
        <br><a href="{{$prev['link']}}" style="text-decoration: none"><b style="color: black">Prev:</b>&nbsp;【{{$tb['label']}}】{{$prev['tit']}}</a><br>
        {{/if}}
        {{$nextset=array()}}
        {{$nextset['column']=id,tit}}
        {{$nextset['other']=`"id>".$tb['id']}}
        {{$nextset['order']=id asc}}
        {{$nextset['where']['label']=$tb['label']}}
        {{$next=a($nextset)}}
        {{if $next}}
        <br><a href="{{$next['link']}}"
               style="text-decoration: none"><b
                    style="color: black">Next:</b>&nbsp;【{{$tb['label']}}】{{$next['tit']}}</a>
        {{/if}}
    </div>

</div>
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
</body>
</html>