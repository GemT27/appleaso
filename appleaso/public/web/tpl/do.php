<?php
$do = getPost('do');
//产品id
$id = getPost('id');
if ($do == "pay") {
    //产品价格
    $price = getPost('price');
    //产品名
    $name = getPost('title');
    //总价
    $total = getPost('total');
    //购买数量
    $qty = getPost('qty');
    //邮箱
    $email = getPost('email');
    //ios1
    $ios = getPost('ios');
    //keywords
    $key = getPost('keywords');
    //country
    $country = getPost('country');
    $payid = aInsertArr(28, array("proid" => $id, "title" => $name, "price" => $price,
        "qty" => $qty, "total" => $total, "email" => $email, "ios" => $ios, "country" => $country, "keywords" => $key));
    if ($payid) {
        exit(getOkMsg($payid));
    }
} else if ($do == "payok") {
    $ok = aEditArr(28, $id, array("ispay" => 1));
    if ($ok) {
        exit(getOkMsg($ok));
    }
} else if ($do == "search") {
    $country = getPost('country');
    $term = getPost('country');
}