<?php 
include_once("header.php");	
include_once('inc/conn.php');
	if($islogin>0){

//当前用户的角色
$role=$islogin;
switch($role){
	case 1:
	$str="普通用户";
	break;
	case 2:
	$str="普通用户-升级为自助借还的用户";
	break;
	case 3:
	$str="图书管理员";
	break;
	case 4:
	$str="公共管理员";
	break;
	case 5:
	$str="主管";
	break;
	case 6:
	$str="公共管理员-升级自助借还的用户";
	break;
	case 7:
	$str="主管-开通了自助借还功能";
	break;
	case 9:
	$str="超级用户";
	break;
}
//echo $str;

//echo $role;
	
?>

<div class="container-fluid">
	<div class="page-header">
		<h4><?php echo $name ?>的当前角色为：<?php echo $str ?></h4>
	</div>
  <form id="form1" name="form1" method="post" action="test_change_role_post.php" class="well form-search" action="opac.php" method="post">
    <p>
      <label>
        <input type="radio" name="role_id" value="1" <?php if($role=='1') echo 'checked'?> id="role_id_0" />
        普通用户</label>
      <br />
      <label>
        <input type="radio" name="role_id" value="3" <?php if($role=='3') echo 'checked'?> id="role_id_1" />
        图书管理员</label>
      <br />
      <label>
        <input type="radio" name="role_id" value="4" <?php if($role=='4') echo 'checked'?> id="role_id_2" />
        公共管理员</label>
      <br />
      <label>
        <input type="radio" name="role_id" value="5" <?php if($role=='5') echo 'checked'?> id="role_id_3" />
        主管</label>
      <br />
      <input type="submit" name="button" id="button" value="提交" />
    </p>
	角色说明：<br />普通用户，可以浏览图书目录——opac；签到；积分商城；预约座位；我的借阅；我的积分；修改密码。
	<br />图书管理员，在普通用户基础上，可以编目、借书、还书；
	<br />公共管理员，与普通用户基本一样，只是能够被主管添加积分而已；
	<br />主管，在普通用户基础上增加了为图书管理员和公共管理员添加积分的功能。
  </form>
  <p>&nbsp;</p>
</div>



<?php 
}
include_once("footer.php");
?>


  </body>
</html>
