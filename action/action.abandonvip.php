<?php
error_reporting(0);
include_once('../inc/conn.php');	
session_start();
$u_id=$_SESSION['u_id'];
$sql="insert into abandonvip(userid,createtime,abandontime) values ($u_id,".time().",".time().")";
$result=mysql_query($sql);
$sql1="update vipinfo set starttime=0,endtime=0,status=0 where userid=$u_id";
$result1=mysql_query($sql1);
if($result && $result1){
	$_SESSION['vip'] = 0;
	echo '<script>location="../index.php"</script>';
}else{
	echo '<script>alert("放弃失败，请联系管理员！");location="../index.php";</script>';
}
?>