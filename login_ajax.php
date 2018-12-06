<?php
include_once('inc/conn.php');
session_start();
$action=$_GET['action']; 
$uname=$_GET['uname']; 
$pwd=$_GET['pwd']; 
 
if($action=="getlink"){
$sql="SELECT * FROM user WHERE username='$uname' and userpass =password('$pwd') ";
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
if($row){
	$_SESSION['islogin']=$row['quanxian'];//直接让islogin同步quanxian，避免多个赋值的问题。
	$_SESSION['name'] = $row['name'];
	$_SESSION['u_id'] =$row['id'];
	$_SESSION['username'] =$row['username'];
	$u_id=$row['id'];


	

	//登录信息的存储保存过程
	//这里是成功的信息
	$sql="insert into user_login (user_id,in_time,login_in) values ('$u_id',now(),'登录成功') ";
	$result=mysql_query($sql);
	//登录成功后 判断当前用户是否为vip
	$ssql="SELECT * FROM vipinfo WHERE userid=".$row['id']." and endtime>".time();
	$sresult=mysql_query($ssql);
	$srow = mysql_fetch_array($sresult);
	if($srow){
		$_SESSION['vip'] = 1;
	}else{
		$_SESSION['vip'] = 0;
	}
	$return_code=1;
}
else 
{
	//将没有登录成功的信息也记录到user_login表
	//这里是不成功的信息
	$u_id=0;
	$sql="insert into user_login (user_id,in_time,login_in) values ('$u_id',now(),'登录失败') ";
	$result=mysql_query($sql);
	
	$return_code=0;
}

$arr=array("rstr"=>$return_code);

$jarr=json_encode($arr); 
echo $jarr;

}
?>


