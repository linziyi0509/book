<?php
error_reporting(0);
session_start();
$islogin=$_SESSION['islogin'];
$name=$_SESSION['name'];
$u_id=$_SESSION['u_id'];
date_default_timezone_set('ETC/GMT-8');
if(!in_array($islogin,array(2,3,6,7))){
	echo '<script>';
	echo "location='index.php'";
	echo '</script>';
}
include_once('inc/conn.php');

$score=20;//积分规则，正常还书后加20分，可以在这里更改分数。

$book_id=$_POST['book_id'];
$borrow_id=$_POST['borrow_id'];
$returndate=$_POST['returndate'];
$user_id=$u_id;

// echo "<br>book_id:".$book_id;
// echo "<br>borrow_id:".$borrow_id;
// echo "<br>user_id:".$user_id;
// exit;
	//先计算exceedday
	$exceedday=0;
	$Date_1 = date("Y-m-d");
	$Date_2 = $returndate;
	$d1 = strtotime($Date_1);
	$d2 = strtotime($Date_2);
	$Days = round(($d1-$d2)/3600/24);
	if($Days>0){//大于0才是真正的有超期时间的记录
		$exceedday=$Days;

		//有超期记录，则在user_special表中插入新记录
		//计算欠费，需要先查询日罚款金额，要么查询user_type表，要么是在user_special表。
		$sql="select * from user where id='$user_id'";
		$result_tmp=mysql_query($sql) or die(mysql_error());
		if($row_tmp = mysql_fetch_array($result_tmp)){
			if($row_tmp['type']!='自定义'){//是正常的角色的话，直接从user_type表获取此用户对应的借阅权限；
				$type=$row_tmp['type'];
				$sql="select * from user_type where name='$type'";
				$result_tp=mysql_query($sql) or die(mysql_error());
				if($row_tp = mysql_fetch_array($result_tp)){
					//找到了对应的角色后，提出此用户的借阅权限信息；
					$bookexceedfund=$row_tp['bookexceedfund'];//日罚款金额
				}
			}
			else{//自定义的话，从user_special表获取此用户对应的日罚款金额；
				$sql="select * from user_special where user_id='$user_id' and type='自定义'";
				$result_tp=mysql_query($sql) or die(mysql_error());
				if($row_tp = mysql_fetch_array($result_tp)){
					//找到了对应的角色后，提出此用户的罚款金额信息；
					$bookexceedfund=$row_tp['bookexceedfund'];
				}
			}
		}
		$fund=$bookexceedfund*$exceedday;
		$sql="insert into user_special (user_id,type,borrow_id,fund,ispay) values('$user_id','欠费','$borrow_id','$fund','否')";
		$result=mysql_query($sql) or die(mysql_error());
		$str_message="此图书逾期".$exceedday."天，"."欠费：".$fund."元。";
	}
	//echo "超期天数：".$exceedday;
	
	//更新borrow表
	$sql="update borrow set exceedday='$exceedday', status='已还', factreturndate=now(),return_op_id='$u_id' where id='$borrow_id'";
	$result=mysql_query($sql) or die(mysql_error());
	
	//查询借出是否超过5天，如果超过，则需修改score表
	//直接查询borrow_id中的 borrowdate 和factreturndate之间的差
	$sql="select * from borrow where id='$borrow_id' and DATEDIFF(factreturndate,borrowdate)>5";
	$result=mysql_query($sql) or die(mysql_error());
	if($row = mysql_fetch_array($result)){
		//说明借出超过5天归还，可以进行积分操作了
		$sql="insert into score (user_id,score,time,remark,operator) values('$user_id','$score',now(),'还书','system') ";
		$result=mysql_query($sql) or die(mysql_error());
		$str_message.="积分+".$score."分";
	}
	
	//修改完borrow表后，还需要修改bookinfo表中的restnum字段 +1。
	//根据book_id查找bookinfo_id,然后更新bookinfo表中的这个bookinfo_id的记录
	//$sql="update book set restnum=restnum+'1' where bookcode='$bookcode'";
	$sql="update bookinfo a,
(select bookinfo_id from book where id='$book_id') b set a.restnum=a.restnum+'1' where a.id=b.bookinfo_id";
	$result=mysql_query($sql) or die(mysql_error());
	
	
	
	
	$str_message.='还书完成！';
	echo "<script>alert('$str_message');location='myborrow.php'</script>";
	
	

?>



