<?php
!defined('QAPP') AND exit('Forbidden');
?>
<?php
$id = getGet('id');
$t = aFindAnd(22, array("id" => $id));
if (!$t) {
    redirect("/");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="{{getCss(niceCountryInput)}}">
    {{file res.php}}
    <title>cart</title>
</head>
<div>
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
            <div>


                <div id="cart">

                    <div class="clear"></div>
                    <div class="clear"></div>
                    <br/>


                    <table class="cart">
                        <thead>
                        <tr>
                            <th class="first" colspan="3">Your Products</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th class="last">Subtotal</th>
                        </tr>
                        </thead>
                        <tr>

                            <td class="col1">
                            </td>
                            <td class="col2">
                                <!--缩略图-->
                                <img title="" src="<?php echo $t['img'] ?>" alt="" width="125"/>
                            </td>
                            <td class="col3">
                                <h5 class="remove-top"><a><?php echo $t['name'] ?></a></h5>
                                <div class="small">
                                    <strong>Description:</strong><br/>
                                    <span class="smalltext"><?php echo $t['des'] ?></span>

                                </div>
                            </td>

                            <td class="col4 right" style="text-align: center">
                                <span><input type="text" id="qty" style="width: 100px;text-align: center"
                                             placeholder="Min: {{$t['qty']}}" v-model="qty"></span>

                            </td>
                            <td class="col6" style="text-align: center">$<span><?php echo $t['price'] ?></span></td>
                            <td class="col6" style="text-align: center">$<span id="subtotal"
                                                                               v-text="isNaN(price * parseInt(qty))?0:price * parseInt(qty)"></span>
                            </td>
                        </tr>
                    </table>

                    <table class="cart_totals">
                        <tbody>
                        <tr class="discount">
                            <td>
                                <div class="one_half ">
                                    <p class="remove-bottom"><strong>E-Mail Address</strong><br/>
                                        <input type="text" id="email" name="email" value="" class="email input_full"/>
                                    <div class="message"></div>
                                    </p>
                                    <p class="remove-bottom"><strong>App Link Address</strong><br/>
                                        <input type="text" id="ios" name="ios" value="" class=" input_full"
                                               placeholder=""/>
                                    <div class="message"></div>
                                    </p>
                                    <p class="remove-bottom"><strong>Keywords</strong><br/>
                                        <input type="text" id="keywords" name="keywords" value="" class=" input_full"
                                               placeholder=""/>
                                    <div class="message"></div>
                                    </p>
                                    <p class="remove-bottom"><strong>Target Country</strong><br/>
                                    <div id="testinput" data-selectedcountry="US"
                                         data-showspecial="false"
                                         data-showflags="true" data-i18nall="All selected"
                                         data-i18nnofilter="No selection"
                                         data-i18nfilter="Filter" data-onchangecallback="onChangeCallback">
                                    </div>
                                    <div class="message"></div>
                                    <div></div>
                                    </p>
                                </div>

                </div>

                <div class="one_half last">
                    <p class="pull-right">
                        <span>SubTotal: $</span><span id="subtotal2"><span
                                    v-text="isNaN(price * parseInt(qty))?0:price * parseInt(qty)"></span><br/>
                <span class="bold dim90">TOTAL: &nbsp;$<span
                            id="subtotal3"><span
                                v-text="isNaN(price * parseInt(qty))?0:price * parseInt(qty)"></span><br/>


                <a class="bold button green pull-right" style="cursor:pointer"
                   onclick="pay()">Proceed to Checkout<span
                            class="caption">Purchase &amp; Download</span></a>
                    </p>
                </div>
                <div class="clear"></div>
                </td>
                </tr>
                </tbody>
                </table>
            </div>

        </div>

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
<script src="https://cdn.bootcss.com/vue/2.6.10/vue.min.js"></script>
<script src="{{getJs(niceCountryInput)}}"></script>
<script>


    function onChangeCallback(ctr) {
        console.log("The country was changed: " + ctr);
    }

    $(document).ready(function () {
        new NiceCountryInput($("#testinput")).init();
    });
    var vm = new Vue({
        el: '#app',
        data: {
            qty:<?php echo $t['qty'];?>,
            price:<?php echo $t['price'];?>,
        }
    });


    var email = $("#email");
    var ios = $("#ios");
    var key = $("#keywords");

    function pay() {
        if (email.val() == "") {
            alert("Please enter your mailbox");
        } else if (ios.val() == "") {
            alert("Please enter your IOS product link address");
        } else if (parseInt($("#qty").val()) <<?php echo $t['qty'];?>) {
            alert("The minimum quantity required for purchase is {{$t['qty']}}");
        } else if (key.val() == "") {
            alert("Please enter your keywords");
        } else {
            $.ajax({
                type: "POST",
                url: "/do",
                data: {
                    "email": email.val(),
                    "ios": ios.val(),
                    "keywords": key.val(),
                    "country": $(".niceCountryInputMenuDefaultText").text(),
                    "title": "<?php echo $t['title']; ?>",
                    "id":<?php echo $t['id']; ?>,
                    "price":<?php echo $t['price']; ?>,
                    "qty": parseInt(vm.qty),
                    "total": parseInt(vm.qty) * vm.price,
                    "do": "pay"
                },
                dataType: 'json',
                success: function (d) {
                    window.location.href = "/pay/" + d['msg'];
                    //console.log(d['msg'])
                },
                error: function () {
                    alert("购买失败,请稍后再试");
                }
            })
        }
    }
</script>
</body>
</html>