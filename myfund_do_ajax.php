<?php
error_reporting(0);
date_default_timezone_set('ETC/GMT-8');
session_start();
$islogin=$_SESSION['islogin'];
$name=$_SESSION['name'];
$username=$_SESSION['username'];
$u_id=$_SESSION['u_id'];
include_once('inc/conn.php');

$action=$_GET['action']; 
$borrow_id=intval($_GET['id']); 
if($action=="getlink"){
	$sql="select * FROM user_special where borrow_id='$borrow_id'";
	$result=mysql_query($sql);
	$row = mysql_fetch_array($result);
		$fund=$row['fund'];
		$user_id=$row['user_id'];
		// 先计算一下此用户的积分是否够本次的抵扣
		$needed_score=$fund/0.5*40;
			$sql="SELECT sum(score) as sum_score FROM score where user_id='$user_id' ";
			$result_tmp=mysql_query($sql) or die(mysql_error());
			$row_tmp = mysql_fetch_array($result_tmp);
			$sum_score=$row_tmp['sum_score'];//此用户的总积分

		if($needed_score<=$sum_score){

			//积分足够本次抵扣，可以进行抵扣操作
			//首先，在score表中加入新记录
			$sql="insert into score (user_id,score,operator,op_id,time,remark) values ('$user_id',0-$needed_score,'$name','$u_id',now(),'罚金抵扣')";
			$result_tmp=mysql_query($sql);
			
			//update user_special表中的ispay
			$sql="update user_special set ispay='是', pay_date=now(), pay_op_id='$u_id'";
			$result_tmp=mysql_query($sql);
			
			//积分抵扣完成
			$return_mess='积分抵扣成功！';
		}else{

			$return_mess='积分不足无法抵扣！';
		}
		//echo $return_mess;
		$arr=array("mess"=>$return_mess); 

	$jarr=json_encode($arr); 
	echo $jarr;

}
?>


