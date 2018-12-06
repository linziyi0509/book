<?php
error_reporting(0);
session_start();
$islogin=$_SESSION['islogin'];
$name=$_SESSION['name'];
$u_id=$_SESSION['u_id'];
date_default_timezone_set('ETC/GMT-8');

include_once('inc/conn.php');

$role=$_POST['role_id'];
//echo $role;


$sql="update user set quanxian='$role' where id='$u_id'";

$result=mysql_query($sql) or die(mysql_error());
echo "<script>alert('更改角色成功，需要退出重新登录后，体验新角色功能！');location='action/action.loginOut.php'</script>";
	



?>



