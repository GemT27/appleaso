<?php
if (!defined('admin')) {exit();}
if(!power('b',2)) {
	adminmsg('','无权限',3);
}
$thisname=getadminname();
$query = getDB() -> query("SELECT * FROM ".tableex('admin')." where username='$thisname' limit 1");
$user = getDB() -> fetchone($query);
if(isset($_GET['ucmsid'])) {
	$query = getDB() -> query("UPDATE ".tableex('admin')." SET ucmsid='' WHERE username='$thisname'");
	$user['ucmsid']='';
	if($query) {
		$errormsg='取消绑定成功';
	}else {
		$errormsg='取消绑定出错';
	}
}
if(isset($_GET['code'])) {
	checktoken();
	$code=dbstr($_GET['code']);
	if(!empty($code)) {
		$query =getDB() -> query("SELECT count(*) FROM ".tableex('admin')." where ucmsid='".$code."' and username<>'$thisname' limit 1");
		$ifbind = getDB() -> fetchone($query);
		if($ifbind[0]>0) {
			$errormsg='绑定出错,已绑定了其他账号';
		}else {
			$query = getDB() -> query("UPDATE ".tableex('admin')." SET ucmsid='".$code."' WHERE username='$thisname'");
			$user['ucmsid']=$code;
			if($query) {
				$errormsg='绑定成功';
			}else {
				$errormsg='绑定出错';
			}
		}
	}
}
?>
<?php
	if(isset($errormsg)){
	echo("<script type=\"text/javascript\">alert('".$errormsg."')</script>");
	}
	?>
<script language="JavaScript" type="text/javascript">
	function check(){
		if(document.form1.nickname.value==""){
			alert("[昵称]不能为空！");
			document.form1.nickname.focus();
			return false;
		}
		
		if(document.form1.psd.value!=document.form1.psd1.value){
			alert("两次输入密码不一至！");
			document.form1.psd.focus();
			return false;
		}
	return true
	}
</script>
<div id="UMain">
  <!-- 当前位置 -->
<div id="urHere"><em class="homeico"></em>后台管理<b>&gt;</b><strong>修改账户信息</strong> </div>   <div id="mainBox">
    <h3>修改账户信息</h3>
		<form id="form1" name="form1" method="post" action="?do=user_mypost" onsubmit="return check()">
		<?php newtoken();?>
     <table width="100%" border="0" cellpadding="8" cellspacing="0">
  <tr>
		<td width="100" align="right">管理员名称：</td>
		<td height="24" align="left"><input name="nickname" type="text" id="nickname"  class="inputtext" value="<?php echo($user['nickname']);?>"/></td>
		</tr>
		<tr>
		<td width="100" align="right">用 户 名：</td>
		<td height="24" align="left"><input name="username" type="text" id="username" class="inputtext" size="20" value="<?php echo($user['username']);?>" disabled/></td>
		</tr>
		<?php
		if(0) {
		?>
		<tr>

		</tr>
		<?php
		}
		?>
			<tr>
				<td width="100" align="right">密&nbsp;&nbsp;&nbsp;&nbsp;码：</td>
				<td height="25" align="left"><input name="psd" type="password" id="psd" class="inputtext" size="40" value="" onfocus="document.getElementById('psdshow').style.display = '';"/> 不修改则留空,修改后需重新登录</td>
			</tr>
			<tr style="display:none" id="psdshow">
				<td height="25" align="right">确认密码：</td>
				<td height="25" align="left"><input name="psd1" type="password" id="psd1" class="inputtext" size="40" /></td>
			</tr>


      <tr>
       <td></td>
       <td>
        <input class="btn" type="submit" style="width:140px;" value="提交" />
       </td>
      </tr>
     </table>
    </form>
       </div>
 </div>
<script>
loaded=0;
qapp_token='<?php echo(newtoken(999));?>';
</script>

<script>
	loaded=1;
</script>

