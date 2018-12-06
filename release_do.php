<?php
error_reporting(0);

//获取IP地址
if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
$ip = getenv("HTTP_CLIENT_IP");
} else
if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
$ip = getenv("HTTP_X_FORWARDED_FOR");
} else
if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
$ip = getenv("REMOTE_ADDR");
} else
if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
$ip = $_SERVER['REMOTE_ADDR'];
} else {
$ip = "unknown";
}

$bid=$_GET["id"];	//这里的id是需要进行释放确认的booklist表中的id

include_once("header.php");
include_once("inc/common.inc.php");
// if($ip==ROOM_IP and isset($bid)){
// 

// echo "<br><br><br><br><br><br>".$islogin;
// echo "<br><br><br><br><br><br>".$ip;
// echo "<br><br><br><br><br><br>".ROOM_IP;
// echo "<br><br><br><br><br><br>".strpos($ip,ROOM_IP);

// exit;

if(substr($ip,0,11)==ROOM_IP ){
	// echo "<br><br><br><br><br><br>".$islogin;
	// exit;
// if(isset($bid)){

//实验室的IP地址，基本固定，如果发生变化，需要修改这里的代码，否则不能正常确认了。
	//判断是否登录
	if($islogin<1){
		echo "<script>alert('如果遇到这个提示1，说明操作者没有使用正常途径而来！');location='index.php'</script>";
		exit;
	}
	include_once('inc/conn.php');
	
	//根据bid验证u_id是否是登录用户
	$sql="select * from booklist where id='$bid'";
	$result=mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_array($result);
	$uu_id=$row['u_id'];//从bid中查询到的用户id
	$queren_time=$row['queren_time'];
	
	//如果uu_id与u_id相等，则认为是有效用户，否则就是非法进入执行。
	if($u_id==$uu_id){
		// $sql="update booklist set tuichu_time= now(),status='空闲',beizhu='正常退出' where id='$bid'";
		$sql="update booklist set tuichu_time= now(),status='空闲',bztuichu='正常退出' where id='$bid'";
		mysql_query($sql);
		//这里准备进行score的操作
		//根据$queren_time 和now() 计算时间差，这个时间就是用户使用座位的时长，按分钟计算
			$nowdate=date("Y-m-d H:i:s");
			$minute=floor((strtotime($nowdate)-strtotime($queren_time))%86400/60);
			if($minute<10){
				$score=0;
			}elseif($minute<60){
				$score=4;
			}elseif($minute<120){
				$score=10;
			}else{
				$score=12;
			}
		//追加score表		
			$sql="insert into score (user_id,score,time,remark,operator) values('$u_id','$score',now(),'规范用座','system') ";
			$result=mysql_query($sql) or die(mysql_error());
			
		$str="，本次规范用座，积分+".$score;
			
		echo "<script>alert('释放成功！".$str."');location.href='index.php';</script>";
		//echo "<script>alert('释放成功！');location.href='index.php';</script>";
	}
	else{
		echo "<script>alert('如果遇到这个提示2，说明操作者没有使用正常途径而来！');location='index.php'</script>";
		exit;
	}



}
else{
	echo "<script>alert('如果遇到这个提示3，说明操作者没有使用正常途径而来！');location='index.php'</script>";
	exit;
}



?>



