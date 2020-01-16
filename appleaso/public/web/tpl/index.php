<?php
!defined('QAPP') AND exit('Forbidden');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    {{file res.php}}
    <title>{{z(网站标题)}}</title>
</head>
<body class="signup">

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
                <ul style="text-align: center">
                    <li><img  src="{{z(logo图片)}}"  style="width:135px;height: 35px" alt=""></li>
                    <li class="selected"><a href="/">Home</a></li>

                    <li class=""><a href="/news">News</a></li>
                    <li class=""><a href="/search">SEARCH</a></li>
                    <li class="last"><a href="/contact">Contact</a></li>
                </ul>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
<div class="container" id="content">
    <div class="sixteen columns">
        <div class="aligncenter" id="intro">
            <h1 class="remove-bottom animated fadeIn move1" style="text-align: center">{{s(主页头部标题,19)}}</h1>
            <h2 class="remove-top animated fadeIn move1" style="text-align: center">{{s(主页头部副标题,19)}}</h2>
        </div>
        <!-- 套餐-->
        <div id="signup">
            <?php
            $tb = listFindAnd(22, array("isshow" => 1, "order" => "aorder"));
            ?>
            {{loop $tb as $k=>$v}}
            {{if $k==count($tb)-1}}
            <div class="one_third starter last" style="background-color: {{$v['box']}}">
                {{else}}
                <div class="one_third starter" style="background-color: {{$v['box']}}">
                    {{/if}}
                    <h3 style="color: {{$v['headcolor']}}">{{$v['title']}}<br/><span>{{$v['title2']}}</span></h3>
                    <p class="desc" style="color: {{$v['headcolor']}}"><strong>{{$v['tips']}}:</strong> <br/>{{$v['tips2']}}</p>

                    <ul class="none spaced">
                        <?php $exp = explode("\n", $v['func']);
                        for ($a = 0; $a < count($exp); $a++) {
                            echo "<li style='color: {$v['fontcolo']}'><i class='fa fa-check green-text' style='padding-right: 5px;' ></i>" . $exp[$a] . "</li>";
                        }
                        ?>
                    </ul>
                    <br />
                    <div class="price" style="color: {{$v['headcolor']}}"><span>$</span>{{$v['price']}}</div>
                    <div class="clear"></div>
                    <div class="aligncenter btnholder">
                        <a href="/shop/{{$v['id']}}" class="button"
                           style="background-color: {{$v['btn']}}"><span style="color:{{$v['btnfontcolor']}} ">Choose Plan</span></a>
                    </div>
                </div>
                {{/loop}}


            </div><!--/dev-->
            <div class="clear"></div>
            <p class="aligncenter">
                {{s(主页中间内容,19)}}
            </p>
            <div>
                <blockquote>
                    <em>{{s(主页中间副内容,19)}}</em>
                </blockquote>


                <h3 class="lighten brand remove-bottom">Frequently Asked Questions</h3>

                <?php $problem = listFindAnd(20, array("isshow" => 1));


                ?>

                <div class="one_half">
                    <?php
                    for ($i = 0; $i < count($problem); $i++) {
                        if ($problem[$i]['weizhi'] == 'left') {
                            echo "<h4 class=\"equal\">" . $problem[$i]['problem'] . "</h4>" . "<p>" . $problem[$i]['answer'] . "</p>";
                        }
                    }
                    ?>
                </div>
                <div class="one_half last">
                    <?php
                    for ($i = 0; $i < count($problem); $i++) {
                        if ($problem[$i]['weizhi'] == 'right') {
                            echo "<h4 class=\"equal\">" . $problem[$i]['problem'] . "</h4>" . "<p>" . $problem[$i]['answer'] . "</p>";
                        }
                    }
                    ?>
                </div>

            <div class="clear"></div>
            <div class="aligncenter" style="text-align: center">
                <h5>Any Other Questions? <a href="/contact/">Please Ask!</a></h5>
            </div>
            <hr/>

            <div class="subheading">
                <h4>{{s(主页底部标题,19)}}</h4>
                <h5>{{s(主页底部副标题,19)}}</h5>
            </div>

            <div class="one_half">
                <p>{{s(主页底部内容,19)}}
                </p>

            </div>

            <div class="one_half last">
                <ul class="none">
                    {{$arr=s(主页底部能实现的功能,19)}}
                    <?php
                    $arr = explode("\r\n", $arr);
                    ?>
                    {{if $arr[0]!=''}}
                    {{loop $arr as $i}}
                    <li><i class="fa fa-check green-text" style="padding-right: 5px"></i>{{$i}}</li>
                    {{/loop}}
                    {{/if}}
                </ul>
            </div>
            <div class="clear"></div>
        </div>


    </div>

    <div class="clear"></div>
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

  <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-21220102-29"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-21220102-29');
</script>


  
  
</body>
</html>