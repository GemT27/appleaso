<?php
!defined('QAPP') AND exit('Forbidden');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    {{file res.php}}
    <title>Contact</title>
</head>
<body class="contact">
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

                    <li class=""><a href="/news">News</a></li>
                    <li class=""><a href="/search">SEARCH</a></li>
                    <li class="selected last"><a href="/contact">Contact</a></li>
                </ul>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>

<div class="aligncenter" id="intro">
    <h1 class="remove-bottom animated fadeIn move1" style="text-align: center">{{s(顶部主标题,25)}}</h1>
</div>

<div class="container" id="content">
    <div class="sixteen columns">
        <div class="note info">
            <h4>{{s(顶部副标题,25)}}</h4>
        </div>
        <div class="clear">&nbsp;</div>

        <h2 class="remove-top">{{s(中间主标题,25)}}</h2>

        <p>{{s(中间副标题,25)}}</p>

        <form action="{{u(`24)}}" method="post">
            <div class="one_half">
                <p class="remove-bottom"><strong>Name: </strong><br/>
                    <input type="text" id="name" name="name" value="" class="name input_full" placeholder="Your Name"
                           required/>
                <div class="message"></div>
                </p>
            </div>
            <div class="one_half last">
                <p class="remove-bottom"><strong>E-Mail Address</strong><br/>
                    <input type="text" name="email" value="" class="email input_full"
                           placeholder="you@yourdomain.com" required/>
                <div class="message"></div>
                </p>
            </div>
            <div class="clear"></div>

            <div class="one_half">
                <p class="remove-bottom"><strong>Phone</strong><br/>
                    <input type="text" name="phone" value="" class="phone1 input_full"/>
                <div class="message"></div>
                </p>
            </div>
            <div class="one_half last">
                <p class="remove-bottom"><strong>Subject</strong><br/>
                    <select name="subject" class="input_full">
                        <?php
                        $t = listFindAnd(26, array());
                        for ($i = 0; $i < count($t); $i++) {
                            $check = "";
                            if ($i == 0) {
                                $check = "select='selected'";
                            }
                            echo "<option value={$t[$i]['title']} {$check}>{$t[$i]['title']}</option>";
                        }
                        ?>
                    </select>
                <div class="message"></div>
                </p>
            </div>
            <div class="clear"></div>

            <p class="remove-bottom"><strong>Message</strong><br/>
                <textarea name="message" rows="10" cols="70"  maxlength="2000" required></textarea>
            <div class="message"></div>
            </p>

            <p class="smalltext">{{s(底部标题,25)}}</p>
            <input type="submit" name="submit" value="Submit" class="large green button pull-right"/>
            <div class="clear"></div>
        </form>
        {{if isset($_POST["name"])}}
        {{$newarticle=array()}}
        {{$newarticle['cid']=24}}
        {{$newarticle['name']=$_POST["name"]}}
        {{$newarticle['email']=$_POST["email"]}}
        {{$newarticle['phone']=$_POST["phone"]}}
        {{$newarticle['subject']=$_POST["subject"]}}
        {{$newarticle['msg']=$_POST["message"]}}
        {{if strlen($newarticle['phone'])!=11}}
        {{$id=-1}}
        {{else}}
        {{$id=ainsert($newarticle)}}
        {{/if}}
        {{if $id>0}}
        <script>
            alert('留言成功');
        </script>
        {{else}}
        <script>
            alert('留言失败,请输入正确的手机号码')
        </script>
        {{/if}}
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
<script>
</script>
</body>
</html>