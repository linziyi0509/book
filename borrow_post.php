<?php
error_reporting(0);
session_start();
$islogin=$_SESSION['islogin'];
$name=$_SESSION['name'];//用户姓名
$username=$_SESSION['username'];//用户学号
$u_id=$_SESSION['u_id'];//用户ID
date_default_timezone_set('ETC/GMT-8');

if($islogin!=3 and $islogin!=9 and $islogin!=2 and $islogin!=6 and $islogin!=7){
	echo '<script>';
	echo "location='index.php'";
	echo '</script>';
}
	
	include_once('inc/conn.php');

	if(!empty($_POST['username'])){ //如果传递过来了学号，则更改$username值，否则进行当前登录用户的借书操作。
		$username=$_POST['username'];
	}
	//这里有一个小bug，就是正常的图书管理员，在用户编号中如果不输入号码，则会默认进行自己的借阅操作。
	//是bug也可以看做是便捷的小操作。
	
	$bookcode=$_POST['bookcode'];
	
//开始做检验，对于username的检验，先看user表中的type是否为自定义，若不是，则查看user_special表中是否有此用户的欠费信息
	$sql="select * from user where username='$username'";
	$result=mysql_query($sql) or die(mysql_error());
	if($row = mysql_fetch_array($result)){
		
		$user_id=$row['id'];//这个user_id就是借阅图书的用户ID
		
		if($row['type']!='自定义'){//是正常的角色的话，直接从user_type表获取此用户对应的借阅权限；
			$type=$row['type'];
			$sql="select * from user_type where name='$type'";
			$result_tp=mysql_query($sql) or die(mysql_error());
			if($row_tp = mysql_fetch_array($result_tp)){
				//找到了对应的角色后，提出此用户的借阅权限信息；
				$booknum=$row_tp['booknum'];//借阅册数
				$bookdatenum=$row_tp['bookdatenum'];//借期
			}
		}
		else{//自定义的话，从user_special表获取此用户对应的借阅权限；
			$sql="select * from user_special where user_id='$user_id' and type='自定义'";
			$result_tp=mysql_query($sql) or die(mysql_error());
			if($row_tp = mysql_fetch_array($result_tp)){
				//找到了对应的角色后，提出此用户的借阅权限信息；
				$booknum=$row_tp['booknum'];//册数
				$bookdatenum=$row_tp['bookdatenum'];//借期
			}
		}
		//再检查此用户是否有欠费
			$sql="select * from user_special where user_id='$user_id' and type='欠费' and ispay='否'";
			$result_tp=mysql_query($sql) or die(mysql_error());
			if($row_tp = mysql_fetch_array($result_tp)){
				echo "<script>alert('用户有欠费信息，请先办理缴费再借阅！');location='borrow.php'</script>";
				exit;
			}
		//再检查此用户的可借册数是否已满
			$sql="select * from borrow where user_id='$user_id' and status='未归还'";
			$result_tp=mysql_query($sql) or die(mysql_error());
			$rowcount=mysql_num_rows($result_tp);
			//echo "用户可借".$booknum."用户已经借了"."$rowcount";
			
			if($rowcount == $booknum){
				echo "<script>alert('用户可借册数已满，无法再次借阅！');location='borrow.php'</script>";
				exit;
			}	
		//再检查此用户未归还的图书中是否有超期的图书
			$sql="select * from borrow where user_id='$user_id' and status='未归还' and DATEDIFF(date(now()), returndate)>=1";
			$result_tp=mysql_query($sql) or die(mysql_error());
			if($row_tp = mysql_fetch_array($result_tp)){
				echo "<script>alert('查出用户有欠费信息，请先办理缴费再借阅！');location='borrow.php'</script>";
				exit;
			}
			
			if($rowcount == $booknum){
				echo "<script>alert('用户可借册数已满，无法再次借阅！');location='borrow.php'</script>";
				exit;
			}		
	}
	else{
		//没有检索到此用户信息，说明录入有错误。
		echo "<script>alert('用户信息有误，请重新输入！');location='borrow.php'</script>";
		exit;
	}
//用户的检验结束	
	
//再来进行bookcode的检验，图书的检验，主要看book表中对应的bookinfo_id在bookinfo表中的restnum是否>1
//注意：上面的检查方法是错误的！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！
//因为bookcode是唯一的，在进行外借的时候，只需查询bookcode所对应的book_id在borrow表中是否有status为’未归还‘的状态

//先做个bookcode是否输入错误的检验
	$sql="select * from book where bookcode='$bookcode'";
	$result_code=mysql_query($sql) or die(mysql_error());
	if($row_code = mysql_fetch_array($result_code))	{
		//说明输入的bookcode是正确的
		$book_id=$row_code['id'];
		$bookinfo_id=$row_code['bookinfo_id'];
		//开始进行判断是否可借
		$sql="select a.id,a.bookcode,a.bookinfo_id as bookinfo_id,b.book_id as book_id,b.status from book a, borrow b where a.bookcode='$bookcode' and a.id=b.book_id and b.status='未归还'";

		$result=mysql_query($sql) or die(mysql_error());
		if($row_tmp = mysql_fetch_array($result)){
			//借阅状态存在未归还，则说明这本书还不能外借。
			echo "<script>alert('此书外借中，不能再次借阅，请扫码借书！');location='borrow.php'</script>";
			exit;

		}
	}
	else{
		//没有检索到此图书信息，说明录入有错误。
		echo "<script>alert('图书信息有误，请重新输入！');location='borrow.php'</script>";
		exit;	
	}

//程序运行到这里，说明读者和图书信息都无误，可以办理借阅手续了。
//向borrow表插入记录
//需要添加的信息有：username bookcode borrowdate returndate status operator 
//其中returndate需要计算

	$days='+'.$bookdatenum.' days';
	$returndate=date("Y-m-d",strtotime($days,time()));
	//echo $returndate;
	$sql="insert into borrow (user_id,book_id,borrowdate,returndate,status,borrow_op_id) values('$user_id','$book_id',now(),'$returndate','未归还','$u_id')";
	//echo $sql;
	$result=mysql_query($sql) or die(mysql_error());

	//修改完borrow表后，还需要修改bookinfo表中的restnum字段。
	$sql="update bookinfo set restnum=restnum-'1' where id='$bookinfo_id'";
	$result=mysql_query($sql) or die(mysql_error());

	echo "<script>alert('借阅完成！');location='borrow.php'</script>";

//完成借阅后，还发现：在borrow表中的exceedday不能自动生成，也就是说超期天数的生成，需要有个触发动。
//borrow表里的超期天数与user_special中的fund相对应。
//正常情况下，在还书的时候计算超期天数，并计算罚金。
//但如果读者未归还超期图书，又要办理新借阅，为了避免这类现象，在借书操作中加入一个检验
//检验当前读者未归还图书中是否有超期的。
?>



