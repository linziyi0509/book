<?php
	 error_reporting(0);
include_once('../inc/conn.php');	
session_start();
$u_id=$_SESSION['u_id'];
// $sql="insert into user_login (user_id,out_time,login_in) values ('$u_id',now(),'退出') ";
// $result=mysql_query($sql);

$sql="insert into user_login (user_id,out_time,login_in) values ('$u_id',now(),'退出系统') ";
$result=mysql_query($sql);
	
	//删除客户端的在COOKIE中的Sessionid
	if(isset($_COOKIE[session_name()])){
	setCookie(session_name(), '', time()-3600, '/');
	}
    $_SESSION = array();
	session_destroy();

	
	echo '<script>';
	echo "location='../index.php'";
	echo '</script>';
?>