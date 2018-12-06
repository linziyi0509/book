<?php
include_once("header.php");	
$s_id=$_GET["id"];	
if($_SESSION['vip']=='Y') {
	include_once('inc/conn.php');

	$sql="update vip set vseatid='$s_id' where vuserid='$u_id' order by id desc limit 1";
	$result=mysql_query($sql) or die(mysql_error());
	$rowcount=mysql_num_rows($result);
	echo "<script>location.href='yuyue_list.php';</script>";
}
include_once("footer.php");
?>



