<?php
!web_getadminname() and redirect(web_gotoLoginUrl("/")) and exit();

//购买记录
$buy = listFindAnd(28,array());
$table2 = "<table border='2'>
    <tr>
        <th>支付是否完成</th>
        <th>套餐id</th>
        <th>套餐名</th>
        <th>套餐原价</th>
        <th>购买数量</th>
        <th>购买总价</th>
        <th>邮箱</th>
        <th>ios产品链接地址</th>
        <th>时间</th>
    </tr>";
foreach ($buy as $b) {
    $time = date('Y-m-d H:i:s', $b['shijian']);
    $table2 .= "<tr>
        <td>{$b['ispay']}</td>
        <td>{$b['proid']}</td>
        <td>{$b['title']}</td>
        <td>{$b['price']}</td>
        <td>{$b['qty']}</td>
        <td>{$b['total']}</td>
        <td>{$b['email']}</td>
        <td>{$b['ios']}</td>
        <td>{$time}</td>
    </tr>";
}
$table2 .= "</table>";
echo_XLS_Content("购买记录", $table2);