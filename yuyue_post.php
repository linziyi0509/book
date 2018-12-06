<?php
include_once("header.php");	
$s_id=$_GET["id"];	
$_SESSION['F_URL'] ='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];

if($islogin>0){

//$s_id=$_POST["cang_id"];	//这里的cang_id传递过来的是座位号
//$s_id=$_GET["id"];	
//if(!empty($_GET["id"]))$s_id=$_GET["id"];

include_once('inc/conn.php');
//检验：u_id是否有资格预约，不能存在重复预约的情况。
//检验的依据是，u_id在booklist中是否存在不是“空闲”的记录。若有则不能预约。
$sql="select * from booklist where u_id='$u_id' and status!='空闲'";
$result=mysql_query($sql) or die(mysql_error());
$rowcount=mysql_num_rows($result);
if($rowcount<1){
	//检验通过后，在booklist表中追加新记录，分别set u_id、s_id、预约时间、状态信息。
	$sql="insert into booklist (s_id,u_id,yuyue_time,status) values('$s_id','$u_id',now(),'等待确认')";
	mysql_query($sql);
	echo "<script>location.href='yuyue_list.php';</script>";
}
else{
	echo "<script>alert('每人只可以预约一次，且有座位时不能建立新预约！');location.href='yuyue_list.php';</script>";
}




}
include_once("footer.php");
?>



