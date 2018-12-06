<?php
include_once('inc/conn.php');
date_default_timezone_set('ETC/GMT-8');
$ssuid=$_GET['ssuid']; 
$cang_id=$_GET['cang_id']; 
 //$sql="SELECT a.id sid,a.bianhao,a.miaoshu,b.id bid,b.s_id,b.u_id,b.yuyue_time,b.queren_time,b.status from seat  as a left join (select * from (select * from booklist order by id desc) tmp group by s_id)as b on b.s_id=a.id order by a.id" ;
$sql="SELECT * from (SELECT a.id sid,a.bianhao,a.miaoshu,a.status asta,b.id bid,b.s_id,b.u_id,b.yuyue_time,b.queren_time,b.status from seat  as a left join (select * from (select * from booklist order by id desc) tmp group by s_id)as b on b.s_id=a.id ) as c where c.asta='Y' order by sid" ;
$result=mysql_query($sql) or die(mysql_error());
//这里通过前面的sql检索出了所有可以显示的预约座位信息列表
//result中存储了17条记录的信息；
while($row = mysql_fetch_array($result))
	{
		//通过while循环，将17条记录依次取出，并进行变量赋值；
		$button_str='';//button_str是显示在座位编号中的提示文字，可以对这个字符串进行编辑，使其可以显示取消预约的按钮
		$sid=$row['sid'];//sid是座位的id
		$bid=$row['bid'];//bid是预约流水事务的id
		$bianhao= $row['bianhao'];
		$miaoshu=$row['miaoshu'];
		$s_status= $row['status'];
		$user_id=$row['u_id'];

		//echo $s_status;
		//exit;


		if(!isset($s_status) or $s_status=='空闲' ){
			//这里要显示预约按钮了
			$sign_show_button='y';
			}

		elseif($s_status=="等待确认"){
			//echo "做判断，是否确认超时，如没有超时，则显示预约时间，若超时，修改此记录为空闲";
			 $cha_time=time()-strtotime($row['yuyue_time']);
			 //echo "aaa".strtotime($row['yuyue_time']);
			 //exit;
			 $cha_m=30-floor($cha_time/60);
			 	//首先确认日期是当天的才有进一步比较的价值，否则就直接可以判断出是超时未确认的
				// echo "<br>是当天的预约，还没有确认";
				// echo $now_time."----";
				// echo $row['yuyue_time']."----";
				// echo '差'.floor($cha/60)."分";
				if($cha_m>0){
					$sign_show_button='n';
					$button_str= '还有'.$cha_m."分钟进行现场确认!";
					// $button_str= '还有'.$cha_m."分钟进行现场确认，<br/>取消预约请点击上方‘我的预约’!";
					//取消预约的相关代码需要加入这里
					//想想处理的逻辑，只有预约这个座位的用户，才可以在这里看到“取消预约”的按钮，而其他人都是正常显示的；
					//所以，我先在这里做一个伪代码的处理；
					// if(预约这个座位的人是目前登录系统的用户){
					// 	显示“取消预约”的按钮
					// }
					if($user_id==$ssuid){
						//先加入一些测试代码吧；
						$button_str.="<form action='del_book.php' method='post'><input type='hidden' value='".$cang_id."'  name='cang_id' ><button  type='submit'>取消此次预约</button></form>";
						// $button_str.="<form action='del_book.php' method='post'><input type='hidden' value='"+cang_id+"'  name='cang_id' ><button  type='submit'>取消此次预约</button></form>";

					}
				}
				else{
					//echo "<br>超期未确认";
					$sql="update  booklist  set status='空闲',bzyuyue='预约后确认超时' where id='$bid'";
					// $sql="update  booklist  set status='空闲',beizhu='预约后确认超时' where id='$bid'";
					mysql_query($sql);
					//这里也要显示预约按钮
					$sign_show_button='y';
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
					//这里第三次要显示预约按钮
					$sign_show_button='y';
				}
					else{
						$sign_show_button='n';
						$button_str= "当前座位已经使用了".$cha_queren_h."小时。<br>";
					}
				}

		//准备在这里加入VIP绑定座位的提示信息。因为现在是在所有有效座位的大循环中
		//每一次拿一个座位，我们要看这个座位是不是被VIP绑定了。
		$sql1="select * from vip where vseatid='$sid' and isvip=1" ;
		$result1=mysql_query($sql1) or die(mysql_error());
		$row1 = mysql_fetch_array($result1);
		if(mysql_num_rows($result1)){
			//找到了存在绑定关系
			// $row1 = mysql_fetch_array($result1);
			$sign_show_button='n';
			$button_str.="此座位是vip专属座位，vip有效期至：".$row1['vipdate'];
		}		
		
//到这里，得到了要返回的变量，分别为：
//$sid、$bid、$bianhao、$miaoshu、$sign_show_button、$button_str

   $arr[] = array(
   		'sid'=>$sid,
   		'bid'=>$bid,
   		'bianhao'=>$bianhao,
   		'miaoshu'=>$miaoshu,
   		'sign_show_button'=>$sign_show_button,
   		'button_str'=>$button_str,
    ); 
} 

// print_r($arr);
echo json_encode($arr);  //转换为json数据输出  

?>