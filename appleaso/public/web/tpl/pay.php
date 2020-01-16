<?php
!defined('QAPP') AND exit('Forbidden');
?>
<?php
$id = getGet('id');
$t = aFindAnd(28, array("id" => $id,"ispay"=>0));
if (!$t) {
    redirect("/");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    {{file res.php}}

    <title>pay</title>
</head>
<body>
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
                    <li class="last"><a href="/contact">Contact</a></li>
                </ul>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
<div class="container" id="content">
    <div id="checkout">
        <div class="outsetbox" style="margin-top: 80px">
            <div class="three_fourths remove-bottom last">
                <p class="remove-top remove-bottom">
                    <strong>{{s(页面主标题,29)}}</strong>
                    <br/></br>
                    {{s(页面副标题,29)}}
                </p>
                <div style="margin-top: 50px"></div>
                <div class="clear"></div>
                <span>Order:</span>
                <p>Buy Meal: <b><?php echo $t['title'] ?></b></p>
                <p>Buy Qty&nbsp;&nbsp;: <b><?php echo $t['qty'] ?></b></p>
                <p>
                    Buy Total: <b>$<?php echo $t['total'] ?></b>
                </p>

                <!--pay-->
                <div id="paypal-button-container" style="width: 100px">

                </div>

            </div>
            <div class="clear"></div>
        </div>
        <hr class="remove-top">
        <div class="one_third last " style="float: right">
            <div class="clear"></div>
            <div class="aligncenter">
                <br/>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<div class="clear"></div>
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
<!-- PayPal JavaScript SDK -->
<script src="https://www.paypal.com/sdk/js?client-id=AfNS9F4aEOkts8R1pC1dsP3zt35zTha06TKVr4xdEDVfgCyrz_qSPOBkBxuJG7vn72iD_F2oWA-j-Iwn"></script>

<script>
    // 渲染按钮到 #paypal-button-container 里面
    paypal.Buttons({

        // 设置交易信息 amount value 是交易的金额
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: '<?php echo $t['total'] ?>'
                    }
                }]
            });
        },

        // 交易完成后回调
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                $.ajax({
                    type: "POST",
                    data: {
                        "id":<?php echo $t['id']; ?>,
                        "do": "payok"
                    },
                    dataType: 'json',
                    success: function (d) {
                        // 显示交易成功的消息
                        alert('Transaction completed by ' + details.payer.name.given_name + '!');
                    },
                    error: function () {
                        alert('Transaction failure!');
                    }
                })
            });
        }

    }).render('#paypal-button-container');
</script>

</body>
</html>