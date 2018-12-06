<?php     
error_reporting(0);         
include_once('../inc/conn.php');	
session_start();
$name=$_POST["username"];
$password=$_POST["password"];
$url=$_SESSION['F_URL'] ;
$sql="SELECT * FROM user WHERE username='$name' and userpass =password('$password') ";
$result = mysql_query($sql);	
$row = mysql_fetch_array($result);
//quanxian	0——普通用户；3——图书管理员；5——超级管理员；
//islogin	1——普通用户；3——图书管理员；5——超级管理员；

//20180102 新的权限设计
//quanxian	1——普通用户；3——图书管理员；4——共同管理员；5——主管；9——超级管理员；
//islogin	1——普通用户；3——图书管理员；4——共同管理员；5——主管；9——超级管理员；

//20180116 新的权限设计
//quanxian	1——普通用户；2——普通用户自助借还；3——图书管理员；4——共同管理员；5——主管；6——共同管理员自助借还；7——主管自助借还；9——超级管理员；
//islogin	1——普通用户；2——普通用户自助借还；3——图书管理员；4——共同管理员；5——主管；6——共同管理员自助借还；7——主管自助借还；9——超级管理员；

if($row){
	$_SESSION['islogin']=$row['quanxian'];//直接让islogin同步quanxian，避免多个赋值的问题。
	$_SESSION['name'] = $row['name'];
	$_SESSION['u_id'] =$row['id'];
	$_SESSION['username'] =$row['username'];
	$u_id=$row['id'];
	if(isset($url)){
		echo "<script language='javascript' type='text/javascript'>";  
		echo "window.location.href='$url'";  
		echo "</script>";  
		exit;
	}
	//跳转前，将用户的登录信息记录到user_login表
	//这里是成功的信息
	$sql="insert into user_login (user_id,in_time,login_in) values ('$u_id',now(),'登录成功') ";
	$result=mysql_query($sql);
	
	echo "<script>location.href='../opac.php';</script>";
}
else 
{
	//将没有登录成功的信息也记录到user_login表
	//这里是不成功的信息
	$sql="insert into user_login (user_id,in_time,login_in) values ('$u_id',now(),'登录失败') ";
	$result=mysql_query($sql);
	
	echo "<script>alert('登陆失败!!!!!，请检查密码是否正确!');location.href='../index.php';</script>";
}		
?>



