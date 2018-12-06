<?php
include_once('inc/conn.php');	
$id=$_GET["id"];
$oldPwd=$_POST["oldPassword"];
$newPwd=$_POST["newPassword"];

if($oldPwd!=$newPwd)	
{
	$sql="UPDATE user SET  userpass=password('".$newPwd."') WHERE id='$id'";
	mysql_query($sql);
}
echo "<script>alert('修改成功!');location.href='my.php';</script>";
?>



