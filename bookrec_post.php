<?php
error_reporting(0);
date_default_timezone_set('ETC/GMT-8');
session_start();

$islogin=$_SESSION['islogin'];
$name=$_SESSION['name'];
$username=$_SESSION['username'];
$u_id=$_SESSION['u_id'];
$isbn=$_SESSION['isbn'];

if($islogin>0){
	include_once('inc/conn.php');	
	$bookinfo_id=$_POST["bookinfo_id"];
	$rec_reason=$_POST["rec_reason"];
	$isbn=$_POST["isbn"];
	//荐购操作的数据库处理过程：
	//在bookrec表中添加记录 
	$sql="insert into bookrec (bookinfo_id,rec_id,rec_reason,rec_date) values ('$bookinfo_id','$u_id','$rec_reason',now())";
	mysql_query($sql);
	if(mysql_affected_rows()){
		echo "<script>alert('荐购成功!');location.href='bookrec.php';</script>";
		
	}else{
		echo "<script>alert('荐购失败!');location.href='bookrec.php';</script>";
	}
	$_SESSION['isbn']='';
}
?>



