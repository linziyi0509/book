<?php

//error_reporting(0);
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

$s_id=$_GET["id"];	//这里的id是二维码中包含的是座位号
// echo "<br><br><br><br><br>ip:".$ip;
// echo "<br>".$s_id;
// echo "<br>login:".$islogin;
include_once("header.php");
include_once("inc/common.inc.php");
include_once('inc/conn.php');


//先把booklist表中不规范退出的记录更新好。
$sql="SELECT * from (SELECT a.id sid,a.bianhao,a.miaoshu,a.status asta,b.id bid,b.s_id,b.u_id,b.yuyue_time,b.queren_time,b.status from seat  as a left join (select * from (select * from booklist order by id desc) tmp group by s_id)as b on b.s_id=a.id ) as c where c.asta='Y' order by sid" ;
$result=mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($result))
	{
		//通过while循环，将17条记录依次取出，并进行变量赋值；

		$sid=$row['sid'];//sid是座位的id
		$bid=$row['bid'];//bid是预约流水事务的id
		$bianhao= $row['bianhao'];
		$miaoshu=$row['miaoshu'];
		$s_status= $row['status'];
		$user_id=$row['u_id'];

		//echo $s_status;
		//exit;
		if( $s_status=='空闲' ){

			}

		elseif($s_status=="等待确认"){
			 $cha_time=time()-strtotime($row['yuyue_time']);
			 $cha_m=30-floor($cha_time/60);
				if($cha_m>0){

				}
				else{
					//echo "<br>超期未确认";
					$sql="update  booklist  set status='空闲',bzyuyue='预约后确认超时' where id='$bid'";
					// $sql="update  booklist  set status='空闲',beizhu='预约后确认超时' where id='$bid'";
					mysql_query($sql);

				}
				
				
			}
			else{
				//echo "使用中";
				//为了避免出现忘记退出而闲置座位的情况，检查座位的确认时间
				$cha_queren_time=time()-strtotime($row['queren_time']);
				$cha_queren_h=round($cha_queren_time/3600,1);
				//echo $cha_queren_time."<br>";
				
				if($cha_queren_h>6){
					//功能继续设计，暂时不考虑这里。
					//超时未归还的，系统直接update找个记录状态为空闲。
					//可以在booklist中新加一个wenti字段，对于个人遗忘退出、确认超时等情况进行记录，方便后期筛选。
					//对于超过6小时未推出的情况，设置wenti字段的值为2；确认超时的wenti值为1.
					// $sql="update  booklist  set status='空闲',beizhu='使用后退出超时' where id='$bid'";
					$sql="update  booklist  set status='空闲',bztuichu='使用后退出超时' where id='$bid'";
					mysql_query($sql);

				}

				}		

} 

// if($ip==ROOM_IP and isset($s_id)){
if(substr($ip,0,11)==ROOM_IP and isset($s_id)){
 // if(isset($s_id)){
//实验室的IP地址，基本固定，如果发生变化，需要修改这里的代码，否则不能正常确认了。
	//判断是否登录
	if($islogin>0){
	//这里准备加入vip用户与vip绑定座位的判断
	//登录用户在扫码后，两个关键信息需要进行检验：用户id——uid和 座位ID——sid
	// 需要先验证一下这个用户是否是有绑定的座位。
		// 如果uid有绑定的座位
		// 这个sid是否与绑定座位相同
		// 如果相同 则正常进行扫码后续操作
		// 否则 提示错误信息，返回。
	// 如果uid没有绑定的座位
		// 这个uid是否被vip绑定
		// 如果存在有效的绑定，则提示错误信息，返回；
		// 否则正常进行扫码后续操作。
		$sql="select * from vip where vuserid='$u_id' and isvip=1" ;
		//查询当前用户是否是VIP用户
		$result=mysql_query($sql) or die(mysql_error());
		$row = mysql_fetch_array($result);
		if(mysql_num_rows($result)){
			//vip用户
			if($s_id!=$row['vseatid'] and $row['vseatid']!=0)
				{//有绑定座位，且当前座位与绑定座位不符合
					//返回错误信息；
					echo "<script>confirm('你是VIP用户且绑定了座位，座位编号是".$row['vseatid']."，只能到绑定座位扫码使用绑定的座位！');location.href='yuyue_list.php';</script>";
					// echo "<br><br><br><br><br>出错了";
					exit;
				}
			if($row['vseatid']==0){
				//vip用户，但没有绑定座位，则与普通非vip用户执行一样的验证。
				$sql1="select * from vip where vseatid='$s_id' and isvip=1" ;
				//查询当前座位是否被绑定
				$result1=mysql_query($sql1) or die(mysql_error());
				$row1 = mysql_fetch_array($result1);
				if(mysql_num_rows($result1)){
					//扫码了被绑定的座位
					//返回错误信息
						echo "<script>confirm('你扫码的座位是其他VIP专属座位，此座位的专属时间截止到".$row1['vipdate']."！你现在拥有vip权限，可以选定自己的专属座位！');location.href='yuyue_list.php';</script>";
						// echo "<br><br><br><br><br>出错了";
						exit;	
					}
				}

			}
		else{
			//非VIP用户
			$sql1="select * from vip where vseatid='$s_id' and isvip=1" ;
			//查询当前座位是否被绑定
			$result1=mysql_query($sql1) or die(mysql_error());
			$row1 = mysql_fetch_array($result1);
			if(mysql_num_rows($result1)){
				//非vip用户，扫码了被绑定的座位
				//返回错误信息
					echo "<script>confirm('你扫码的座位是VIP专属座位，此座位的专属时间截止到".$row1['vipdate']."！');location.href='yuyue_list.php';</script>";
					// echo "<br><br><br><br><br>出错了";
					exit;	
			}
		}	

	
	
	//根据s_id与u_id寻找到最后一条匹配的记录，只需读取匹配的最后一条记录就可以判断此用户、此座位是否可以被确认或者退出。
	//要在这里加入大判断，根据用户及座位的状态信息，确定目前扫码是可以进行确认还是退出，或者是什么都做不了。
	//echo "座位ID是：".$s_id."<br>用户id是：".$u_id;
	$sql="select * from booklist where s_id='$s_id' and u_id='$u_id' order by id desc limit 1";
	$result=mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_array($result);
	$status=$row['status'];
	//echo $status;
	$bid=$row['id'];
	//得到了所查询到记录的状态信息，存入了变量$status;
	//根据$status进行判断，
	//若“等待确认”则进行确认操作，
	//若“空闲”则直接办理确认，此种状态是没有预约的空闲座位，可以跳过预约过程直接确认,前提是需要检查此s_id的座位是否已经被其他人预约。
	//若“使用中”则提醒用户是否要现在释放座位，确认后完成释放座位的动作。
	
	if($status=='等待确认'){//“确认”的处理过程
		//根据$bid更新booklist表
		$sql="update booklist set queren_time= now(),status='使用中' where id='$bid'";
		mysql_query($sql);
		echo "<script>confirm('确认成功！');location.href='yuyue_list.php';</script>";
		// mysql_query($sql);
		// if(mysql_affected_rows()){
			// echo "<script>alert('确认成功！');location.href='index.php';</script>";
		// }
		// else{
			// echo "<script>alert('失败了，看看是不是哪里出错啦，有问题可以联系管理员！');location.href='index.php';</script>";
		// }
	}
	elseif($status=='使用中'){
		//提醒是否现在办理退出
		//如果是，则跳转到queren_ok.php，否则跳转到index.php
		echo "<script> if(confirm( '是否现在释放座位，是-释放  否-不释放？ '))  location.href='release_do.php?id=$bid'; else location.href='yuyue_list.php'; </script>"; 
	}
	
	else{//if($status=='空闲')；
		//空闲状态或者是没有查询到相关记录，说明这个u_id，与s_id没有曾经的记录。
		
		
		//检验座位是否被他人预约
		$sql="select * from booklist where s_id='$s_id'  order by id desc limit 1";
		$result_jy=mysql_query($sql) or die(mysql_error());
		$row_jy = mysql_fetch_array($result_jy);
		$status_jy=$row_jy['status'];
		
		//还需检验u_id这个用户是否有资格使用这个座位，是否存在曾经预约未确认或者正使用的座位。
		$sql="select * from booklist where u_id='$u_id' and status!='空闲'";
		$result_ujy=mysql_query($sql) or die(mysql_error());
		$rowcount_ujy=mysql_num_rows($result_ujy);
		if($rowcount_ujy<1){
			//u_id检验通过
			if($status_jy=='空闲' or $status_jy===NULL)
			{	
				//s_id检验通过
				//给一个是否的选择，根据判断决定是否执行确认操作。如果要实现这个功能由要进行新文件的挑战，暂时不加这个功能。
				
				//此时已经可以确认，这个座位时没有人预约的空闲座位
				//可以执行p_id与s_id的确认操作，完成非预约情况下的座位确认
				//因为之前并没有预约，所以需要在booklist中追加新记录。
				// $sql="insert into booklist (s_id,u_id,queren_time,status,beizhu) values('$s_id','$u_id',now(),'使用中','未预约现场确认') ";
				$sql="insert into booklist (s_id,u_id,queren_time,status,bzyuyue) values('$s_id','$u_id',now(),'使用中','未预约现场确认') ";
				mysql_query($sql);
				echo "<script>confirm('已经确认成功，请规范使用此座位，释放座位需再次扫描此二维码！');location.href='yuyue_list.php';</script>";
			}
			else{
				echo "<script>confirm('此座位已被他人预约或使用！');location.href='yuyue_list.php';</script>";
				
			}
		}
		else{
			echo "<script>confirm('你预约的座位与扫码座位不匹配，或者你已经有座位不能再扫码其他座位！');location.href='yuyue_list.php';</script>";
		}



	}

	}
	else{

		$_SESSION['QR_URL'] ='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];

		// echo "<script>alert('如果要现场确认，请先登录！');location='index.php'</script>";
		// exit;
	}
	
}
else{
	//echo "<br><br><br><br><br>请用实验室wifi扫码确认！";
	echo "<script>confirm('请用实验室wifi扫码确认！');</script>";
	exit;
}



?>
<script type="text/javascript">
	$(function ($) {
		qr_url="<?php echo $_SESSION['QR_URL']?>";
		// console.log(url);
		islogin='<?php echo $islogin?>';
		if(islogin<1){
			$("#example").click();
		}
		
	});
</script>


