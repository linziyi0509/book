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
$sub_name=$_GET['sub_name']; 
$gift_id=$_GET['gift_id']; 
if($action=="getlink"){
		//计算当前用户的总积分
		$sql="SELECT sum(score) as sum_score FROM score where user_id='$u_id' ";
		$result=mysql_query($sql) or die(mysql_error());
		$row = mysql_fetch_array($result);
		$sum_score=$row['sum_score'];
		
		//判断当前用户是否达到当前商品的兑换上限
		//需要查询exchange表。		
		$sql="select * FROM gift where id='$gift_id'";
		$result=mysql_query($sql);
		$row = mysql_fetch_array($result);
		$gift_name=$row['gift_name'];
		$remark='兑换'.$gift_name;
		$needed_score=$row['gift_score'];
		$limit_times=$row['gift_times'];
		//到exchange表查询当前用户的当前商品的兑换次数；
		$sql="select count(*) as exchange_times from exchange where user_id='$u_id' and gift_id='$gift_id'";
		$result=mysql_query($sql);
		$row = mysql_fetch_array($result);
		$exchange_times=$row['exchange_times'];
		if($exchange_times<$limit_times and $needed_score<=$sum_score){
			//这时候说明 当前用户有资格兑换当前商品


			//修改商品购买后的权限变化，这里根据不同的商品需要更改的权限不同。
			//这里直接根据gift_name进行判断吧，免得id数值发生变化，出现错误。
			
				//为了得到初始化的权限数值，需要先到user_type中读取信息，还需先到user表中查询此用户的type
					$sql="select * from user a, user_type b where a.id='$u_id' and a.type=b.name";
					$result_tmp=mysql_query($sql);
					$row_tmp = mysql_fetch_array($result_tmp);
					$booknum=$row_tmp['booknum'];
					$bookdatenum=$row_tmp['bookdatenum'];
					$bookrenewtimes=$row_tmp['bookrenewtimes'];
					$bookrenewdays=$row_tmp['bookrenewdays'];
			//设置一个标记，判断这些符合要求的用户是否最后兑换成功，因为存在自助借还的兑换，其兑换条件与其他不同，因此这里就这样设定了一个标记
			$sign_exchange='y';//先默认都是兑换成功的；当在兑换自助借还时候，判断不能兑换，则设置其值为n
					
			switch ($gift_name) {
			case "升级/延长VIP有效期3天":
				//如果要兑换vip时长，首先判断此用户是否为vip，如果是有效vip则直接增加vipdate就可以，如果不是vip用户，则需要新加入一条记录，设置isvip为1，vseatid=NULL，然后vipdate增加5天。
				//首先判断此用户是否为vip用户
				$sql="SELECT * FROM vip where vuserid='$u_id' and isvip=1";
				$result=mysql_query($sql);	
				if($row = mysql_fetch_array($result)){
					//说明此用户是vip
					$sql="update vip set vipdate=DATE_SUB(vipdate, INTERVAL -3 DAY),isvip=1,riqi=curdate() where vuserid='$u_id' order by id desc limit 1";
				}else{
					//不是vip用户，需要新加入一条记录
					$sql="insert into vip (vuserid,vipdate,isvip,riqi) values ($u_id,DATE_SUB(CURDATE(), INTERVAL -3 DAY) ,1,curdate())";
				}
				$result_tmp=mysql_query($sql);		

			break;
			case "续期+5天":
				//续期+5天需要的操作
				//1、查看user_special中是否有此用户且type='自定义'的记录，若有，则直接update新的内容个，若没有则新插入一条type='自定义'的记录。
				//2、在borrow表中，做出变化，暂时不考虑这个功能。
				
				//1的代码
				$sql="select * from user_special where user_id='$u_id' and type='自定义'";
				$result=mysql_query($sql);	
				if($row = mysql_fetch_array($result)){
					//说明有此用户的自定义记录，则直接update
					$sql="update user_special set bookrenewdays=bookrenewdays+5 where user_id='$u_id'";
					$result_tmp=mysql_query($sql);
				}else{
					//需要在user_special中插入新记录。
					$bookrenewdays=$bookrenewdays+5;
					$sql="insert into user_special (user_id,type,booknum,bookdatenum,bookrenewtimes,bookrenewdays,bookexceedfund) values ('$u_id','自定义','$booknum','$bookdatenum','$bookrenewtimes','$bookrenewdays',0.5)";
					$result_tmp=mysql_query($sql);
					
					//然后修改user表中，此用户的type为自定义
					$sql="update user set type='自定义' where id='$u_id'";
					$result_tmp=mysql_query($sql);	

				//2、本想做一个续期实时更新的功能，但是因为考虑到的相关问题比较复杂，而需求方面并不大。因此暂时不做这个功能。
				}
				
				
				break;
			case "借期+10天":
				//借期+10天需要的操作
				//查看user_special中是否有此用户且type='自定义'的记录，若有，则直接update新的内容个，若没有则新插入一条type='自定义'的记录。
				$sql="select * from user_special where user_id='$u_id' and type='自定义'";
				$result=mysql_query($sql);	
				if($row = mysql_fetch_array($result)){
					//说明有此用户的自定义记录，则直接update
					$sql="update user_special set bookdatenum=bookdatenum+10 where user_id='$u_id'";
					$result_tmp=mysql_query($sql);
				}else{
					//需要在user_special中插入新记录。
					$bookdatenum=$bookdatenum+10;
					$sql="insert into user_special (user_id,type,booknum,bookdatenum,bookrenewtimes,bookrenewdays,bookexceedfund) values ('$u_id','自定义','$booknum','$bookdatenum','$bookrenewtimes','$bookrenewdays',0.5)";
					$result_tmp=mysql_query($sql);
					
					//然后修改user表中，此用户的type为自定义
					$sql="update user set type='自定义' where id='$u_id'";
					$result_tmp=mysql_query($sql);	
				}				
				break;
			case "可借册数+1":
				//可借册数+1需要的操作
				//查看user_special中是否有此用户且type='自定义'的记录，若有，则直接update新的内容个，若没有则新插入一条type='自定义'的记录。
				$sql="select * from user_special where user_id='$u_id' and type='自定义'";
				$result=mysql_query($sql);	
				if($row = mysql_fetch_array($result)){
					//说明有此用户的自定义记录，则直接update
					$sql="update user_special set booknum=booknum+1 where user_id='$u_id'";
					$result_tmp=mysql_query($sql);
				}else{
					//需要在user_special中插入新记录。
					$booknum=$booknum+1;
					$sql="insert into user_special (user_id,type,booknum,bookdatenum,bookrenewtimes,bookrenewdays,bookexceedfund) values ('$u_id','自定义','$booknum','$bookdatenum','$bookrenewtimes','$bookrenewdays',0.5)";
					$result_tmp=mysql_query($sql);
					
					//然后修改user表中，此用户的type为自定义
					$sql="update user set type='自定义' where id='$u_id'";
					$result_tmp=mysql_query($sql);	
				}
				break;
			
			case "续借次数+1":
				//续借次数+1需要的操作
				//查看user_special中是否有此用户且type='自定义'的记录，若有，则直接update新的内容个，若没有则新插入一条type='自定义'的记录。
				$sql="select * from user_special where user_id='$u_id' and type='自定义'";
				$result=mysql_query($sql);	
				if($row = mysql_fetch_array($result)){
					//说明有此用户的自定义记录，则直接update
					$sql="update user_special set bookrenewtimes=bookrenewtimes+1 where user_id='$u_id'";
					$result_tmp=mysql_query($sql);
				}else{
					//需要在user_special中插入新记录。
					$bookrenewtimes=$bookrenewtimes+1;
					$sql="insert into user_special (user_id,type,booknum,bookdatenum,bookrenewtimes,bookrenewdays,bookexceedfund) values ('$u_id','自定义','$booknum','$bookdatenum','$bookrenewtimes','$bookrenewdays',0.5)";
					$result_tmp=mysql_query($sql);
					
					//然后修改user表中，此用户的type为自定义
					$sql="update user set type='自定义' where id='$u_id'";
					$result_tmp=mysql_query($sql);	
				}
				break;
			
			case "自助借还":
				//修改user表中的quanxian字段 为2
				// 1普通用户升级到2的话，直接进行替换；
				// 3图书馆管理员要升级的话，提示出错，不让升级；
				// 4共同管理员要升级到2的话，quanxian=quanxian+2
				// 5主管，要升级到2的话，quanxian=quanxian+2
					$quanxian=$islogin;
					//quanxian为2/3/6/7/9 则不能再兑换，其中的2/6/7应该走不到这一步，在前面的兑换次数那里就拦截下来了。所以能到这里的quanxian只有 1/3/4/5/9
					//3/9是不允许再兑换的；
					//1可以直接兑换，并且设置quanxian为2；
					//4/5可以兑换，但需要设置quanxian=quanxian+2
					
					if($quanxian==1){
						$sql="update user set quanxian='2' where id='$u_id'";
						$result_tmp=mysql_query($sql);	
						$_SESSION['islogin']=2;
					}
					if($quanxian==4 or $quanxian==5){
						$sql="update user set quanxian=quanxian+'2' where id='$u_id'";
						$result_tmp=mysql_query($sql);
						$_SESSION['islogin']=$quanxian+2;		
					}
					if($quanxian==3 or $quanxian==9){
						$sign_exchange='n';
					}
				break;
			}

			
			//判断sign_exchange的值，看是否真正的兑换成功
			if($sign_exchange=='y'){//真正的兑换成功了，进行后续数据库的操作！
				//首先，在score表中加入新记录
				$sql="insert into score (user_id,score,operator,op_id,time,remark) values ('$u_id',0-$needed_score,'$name','$u_id',now(),'$remark')";
				$result=mysql_query($sql);
				
				//在exchange表中加入新记录
				$sql="insert into exchange (user_id,gift_id,exchange_date) values ('$u_id','$gift_id',now())";
				$result=mysql_query($sql);	
				//商品兑换完成
				$return_mess='商品兑换成功！';
			}else{
				//应该是在兑换自助借还的时候角色问题，不让兑换
				$return_mess='角色无法兑换此商品！';
			}
			
			
			
		}elseif($exchange_times>=$limit_times){
			$return_mess='已达到当前最大兑换次数！';
		}else{
			$return_mess='积分不足无法兑换！';
		}
		//echo $return_mess;
		//exit;
		
		
		//$arr=array("mess"=>$return_mess); 
	

	// $sql="select * FROM user_special where borrow_id='$borrow_id'";
	// $result=mysql_query($sql);
	// $row = mysql_fetch_array($result);
		
	
$cang_id='cang'.$gift_id;	
$arr=array("cang_id"=>$cang_id,"mess"=>$return_mess); 
$jarr=json_encode($arr); 
echo $jarr;

}
?>


