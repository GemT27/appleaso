<?php
!web_getadminname() and redirect(web_gotoLoginUrl("/")) and exit();

//客户意见
$view = listFindAnd(24,array());
$table1 = "<table border='1'>
<tr>
<th>姓名</th>
<th>邮箱</th>
<th>电话</th>
<th>问题类型</th>
<th>意见</th>
<th>时间</th>
</tr>";
foreach ($view as $v) {
    $time = date('Y-m-d H:i:s', $v['shijian']);
    $table1 .= "<tr>
<td>{$v['name']}</td>
<td>{$v['email']}</td>
<td>{$v['phone']}</td>
<td>{$v['subject']}</td>
<td>{$v['msg']}</td>
<td>{$time}</td>
</tr>";
}
$table1 .= "</table>";
echo_XLS_Content("客户意见", $table1);