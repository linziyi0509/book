<?php

function getM(){
	$sql="SELECT * FROM `valuem` WHERE DATEDIFF(date(now()), Mdate)=0";
	$result=mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_array($result);
	if($row){//若找到，说明今天已经存储过M值，不用重新计算。直接从数据库中调用数据。
		return($row['M']);
	}
	else{
		//没找到，说明当日还没有计算过M值
		// $sql="SELECT count(distinct u_id) as num, sum(tuichu_time-queren_time)/60/60/7/count(distinct u_id)  as M FROM `booklist` WHERE DATEDIFF(now(),queren_time)<=7 and bztuichu='正常退出'";

		// $result_zq=mysql_query($sql) or die(mysql_error());
		// if($row_zq = mysql_fetch_array($result_zq))	{
		// 	//$row_zq中的两个字段，num，与M 需要写入valuem表
		// 	$insert_num=$row_zq['num'];
		// 	$insert_M=$row_zq['M'];
		// 	$sql="insert into valuem (num,M,Mdate) values ($insert_num,$insert_M,now());";
		// 	$result=mysql_query($sql) or die(mysql_error());
		// 	return($insert_M);

		// }
		// 
		//上面的是均值算法，下面是修改后的算法，按照固定最少18个VIP来进行排序计算。
		$sql="SELECT * FROM `booklist` WHERE DATEDIFF(now(),queren_time)<=7 and DATEDIFF(now(),queren_time)>=1  and bztuichu='正常退出' group by u_id ";
		$result=mysql_query($sql) or die(mysql_error());
		// $row = mysql_fetch_array($result);
		$suma=mysql_num_rows($result);
		$insert_num=$suma;
		if($suma<10){
			$insert_M=0.000001;

		}
		else{
			$sql="select suma from (select * from (SELECT u_id,sum(UNIX_TIMESTAMP(tuichu_time)-UNIX_TIMESTAMP(queren_time))/60/60/7 as suma FROM `booklist` WHERE DATEDIFF(now(),queren_time)<=7 and DATEDIFF(now(),queren_time)>=1 and bztuichu='正常退出' group by u_id order by suma desc) b limit 17) c order by suma limit 1";
			$result=mysql_query($sql) or die(mysql_error());
			$row = mysql_fetch_array($result);
			$insert_M=$row['suma'];
		}
		$sql="insert into valuem (num,M,Mdate) values ($insert_num,$insert_M,now());";
		$result=mysql_query($sql) or die(mysql_error());
		return($insert_M);

	}
}

function check_vip(){//考虑如何做到每天只执行一次！
	$sql="select * from vip where vipdate <curdate() and isvip=1 ";
	$result=mysql_query($sql) or die(mysql_error());
	
	//找到所有需要进行更新的vip信息后，进行循环更新
	while($row = mysql_fetch_array($result)){
		$updateid=$row['id'];
		// $vuserid=$row['vuserid'];
		// $sql="insert into vip (vuserid,isvip,riqi) values('$vuserid',0,curdate()) ";
		// $result=mysql_query($sql) or die(mysql_error());
		$sql1="update vip set isvip=0, riqi=curdate() where id='$updateid' ";
		$result1=mysql_query($sql1) or die(mysql_error());
		
	}
}
function get_s_m($uid){
	//准备增加返回值，目前的返回值是m值，再增加排名数值，周日均时长值
	// $sql="SELECT sum(UNIX_TIMESTAMP(tuichu_time)-UNIX_TIMESTAMP(queren_time))/60/60/7 as m FROM `booklist` WHERE DATEDIFF(now(),queren_time)<=7 and DATEDIFF(now(),queren_time)>=1 and bztuichu='正常退出' and u_id=$uid";
	// $result=mysql_query($sql) or die(mysql_error());
	// $row = mysql_fetch_array($result);
	// if($row['m']===NULL) 
	// 	$return_m=0;
	// else 
	// 	$return_m=$row['m'];
	$sql="select * from 
(
select (@i:=@i+1) as 排名,d.姓名,d.用户编号,d.日均时长 from
(SELECT b.name 姓名,b.id 用户编号,sum(UNIX_TIMESTAMP(tuichu_time)-UNIX_TIMESTAMP(queren_time))/60/60/7 as 日均时长 FROM `booklist` a ,user b,(select (@i:=0)) c WHERE a.u_id=b.id and DATEDIFF(now(),queren_time)<=7 and DATEDIFF(now(),queren_time)>=1 and bztuichu='正常退出' group by u_id order by 日均时长 desc) d
) e where 用户编号=$uid";
	$result=mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_array($result);
	if($row['用户编号']===NULL) {
		$return_rank=0;
		$return_m=0;
	}
	else {
		$return_rank=$row['排名'];
		$return_m=$row['日均时长'];
	}
	return array($return_m,$return_rank);

}


function auto_code_102($cat){
	//设计102实习室图书的索书号以及图书编号 根据分类号的字段 category 进行自动编号，
	//大致的内容是 分类号/流水号 如： F34.1/003
	//简单的做法就是根据分类号进行查询。
	if($cat!='')
	{
		$sql="select count(*) as sumcat from bookinfo a,(SELECT bookinfo_id,guancangdi FROM `book` ) b where a.id=b.bookinfo_id and category='$cat' and guancangdi='102'";

		$result=mysql_query($sql); 
		$row = mysql_fetch_array($result);
		$sumcat=$row['sumcat']+1;
		//这里计算出目前书库中这个类别图书的总数；
		//再计算一下书库中数量的流水号
		$sql="select count(*) as sumall from book where guancangdi='102'";
		$result=mysql_query($sql); 
		$row = mysql_fetch_array($result);
		$sumall=$row['sumall']+1;

		$findcode=$cat.'/'.sprintf("%03d", $sumcat);
		$bookcode='102'.sprintf("%05d", $sumall);
		return array($findcode,$bookcode);		
	}
}

function auto_code($l_pos, $s_pos){
	if(empty($l_pos) and empty($l_pos)){
		//这就是完全自动计算编码的过程
		//先计算大架位和大架位中的新序号
			// $sql="select max(l_num) as max_l, l_pos from book a group by l_pos order by max_l limit 0,1";
			$sql="select max(l_num) as max_l, l_pos from book a where guancangdi='123' and l_pos !='' and locate(l_pos,'12345')>0 group by l_pos order by max_l limit 0,1";
			$result=mysql_query($sql); 
			$row = mysql_fetch_array($result);
			$l_pos=$row['l_pos'];
			$l_num=$row['max_l']+1;
		
		//再计算小架位
			$sql="select max(s_num) as max_s,s_pos from book where guancangdi='123' and l_pos='$l_pos' group by s_pos order by max_s desc";
			$result=mysql_query($sql); 
			//有的小架位没有书，则在进行上面查询的时候，只显示有书架位的信息。其记录数会少于4；正常情况如果架位全都有书，则会显示4条记录。空架位一定是可以摆放书籍的，因此使用$num来做计数，看看到底有几个架位的信息。
			$num=0;
			while($row = mysql_fetch_array($result)){
				if($row['max_s']<20){
					$s_pos=$row['s_pos'];
					$s_num=$row['max_s']+1;
					break;}//满足条件跳出，并且有赋值的参数。
				$num++;//每次循环，num累加一次
			}
			//循环外，要么是满足条件跳出，要么是没有满足条件自动结束循环。可以根据num判断是哪种情况
			if(!isset($s_pos)){//循环后跳出
				$s_pos=$num+1;
				$s_num=1;
			}
			//echo "函数内部".$s_pos."<br>";
			$findcode=$l_pos.'-'.$s_pos.'-'.sprintf("%03d", $s_num);
			$bookcode='122'.$l_pos.sprintf("%04d", $l_num);
			return array($findcode,$bookcode,$l_pos,$l_num,$s_pos,$s_num);
			//return($findcode);
		
		
	}
	elseif(empty($s_pos)){
		//半自动编码暂时没写代码
		return($l_pos);
	}
}


function ystar(){
	$score=10;
	$str_message="获得额外".$score."积分！";
	//判断y_star表是否存在昨天的数据；
	$sql="SELECT * FROM `y_star` WHERE DATEDIFF(date(now()), riqi)=1";
	$result=mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_array($result);
	if($row){
	//若找到昨日之星，直接从数据库中调用数据。 as c left join (select id,name,nianji,cengci from user) as d on c.u_id=d.id
		$sql="select * from(
	SELECT * FROM `y_star` WHERE DATEDIFF(date(now()), riqi)=1 and star_type='早起之星') as a left join (select id,name,nianji,cengci from user) as b on a.u_id=b.id";
		$result_zq=mysql_query($sql) or die(mysql_error());
		$row_zq = mysql_fetch_array($result_zq);
		
		$sql="select * from(
	SELECT * FROM `y_star` WHERE DATEDIFF(date(now()), riqi)=1 and star_type='勤奋之星') as a left join (select id,name,nianji,cengci from user) as b on a.u_id=b.id";
		$result_qf=mysql_query($sql) or die(mysql_error());
		$row_qf = mysql_fetch_array($result_qf);	
		
		$sql="select * from(
	SELECT * FROM `y_star` WHERE DATEDIFF(date(now()), riqi)=1 and star_type='毅力之星') as a left join (select id,name,nianji,cengci from user) as b on a.u_id=b.id";
		$result_yl=mysql_query($sql) or die(mysql_error());
		$row_yl = mysql_fetch_array($result_yl);	
		}
	else{
		//早起之星
		$sql="SELECT a.u_id,time(a.queren_time) first_time,b.name,b.nianji,b.cengci from booklist a,user b where   b.id=a.u_id and DATEDIFF(now(),queren_time)=1 and bztuichu='正常退出' order by queren_time limit 1";
		$result_zq=mysql_query($sql) or die(mysql_error());
		if($row_zq = mysql_fetch_array($result_zq))	{
			//$row_zq中的两个字段，u_id，与time 需要写入y_star表
			$insert_uid=$row_zq['u_id'];
			$insert_ftime=$row_zq['first_time'];
			$riqi=date("Y-m-d",strtotime("-1 day"));//昨天的日期
			$sql="insert into y_star (u_id,first_time,riqi,star_type) values ('$insert_uid','$insert_ftime','$riqi','早起之星');";
			$result=mysql_query($sql) or die(mysql_error());
			//写入y_star之后，同时写入score
			$sql="insert into score (user_id,score,time,remark,operator) values('$insert_uid','$score',now(),'早起之星','system') ";
			$result=mysql_query($sql) or die(mysql_error());
			
		}
		
		//勤奋之星
		$sql="select * from (select u_id,count(*) as times,sum(cha_t) as sum_miao from
		(SELECT id,u_id,time_to_sec(timediff(tuichu_time,queren_time)) as cha_t from booklist as a where DATEDIFF(now(),queren_time)=1 and bztuichu='正常退出') as b group by u_id order by sum_miao desc limit 1) as c left join (select id,name,nianji,cengci from user) as d on c.u_id=d.id ";
		$result_qf=mysql_query($sql) or die(mysql_error());
		if($row_qf = mysql_fetch_array($result_qf)){
			//$row_qf中的三个字段，u_id,times,sum_miao需要写入y_star表
			$insert_uid=$row_qf['u_id'];
			$insert_times=$row_qf['times'];
			$insert_smiao=$row_qf['sum_miao'];
			$sql="insert into y_star (u_id,times,sum_miao,riqi,star_type) values ('$insert_uid','$insert_times','$insert_smiao','$riqi','勤奋之星');";
			$result=mysql_query($sql) or die(mysql_error());
			//写入y_star之后，同时写入score
			$sql="insert into score (user_id,score,time,remark,operator) values('$insert_uid','$score',now(),'勤奋之星','system') ";
			$result=mysql_query($sql) or die(mysql_error());
			
		}
			
		//毅力之星	
	/* 	$sql="select * from (SELECT u_id, max(days) lianxu_days, min(login_day) start_date,max(login_day) end_date 
		  FROM (SELECT u_id,
					   @cont_day :=
					   (CASE
						 WHEN (@last_u_id = u_id AND DATEDIFF(login_dt, @last_dt)=1) THEN
						  (@cont_day + 1)
						 WHEN (@last_u_id = u_id AND DATEDIFF(login_dt, @last_dt)<1) THEN
						  (@cont_day + 0)
						 ELSE
						  1
					   END) AS days,
					   (@cont_ix := (@cont_ix + IF(@cont_day = 1, 1, 0))) AS cont_ix,
					   @last_u_id := u_id,
					   @last_dt := login_dt login_day
				  FROM (SELECT u_id, DATE(queren_time) AS login_dt
						  FROM booklist where bztuichu='正常退出'
						 ORDER BY u_id, queren_time) AS t,
					   (SELECT @last_u_id := '',
							   @last_dt  := '',
							   @cont_ix  := 0,
							   @cont_day := 0) AS t1) AS t2
		 GROUP BY u_id, cont_ix order by lianxu_days desc,end_date desc limit 1 ) as c left join (select id,name,nianji,cengci from user) as d on c.u_id=d.id ";
		$result_yl=mysql_query($sql) or die(mysql_error());
		if($row_yl = mysql_fetch_array($result_yl)){
			//$row_yl中的两个字段u_id与lianxu_days需要写入y_star表。
			$insert_uid=$row_yl['u_id'];
			$insert_lxdays=$row_yl['lianxu_days'];
			$sql="insert into y_star (u_id,lianxu_days,riqi,star_type) values ('$insert_uid','$insert_lxdays','$riqi','毅力之星');";
			$result=mysql_query($sql) or die(mysql_error());
		} */
		
	}
	$zq_name=substr($row_zq['nianji'],0,4).$row_zq['cengci']."*".mb_substr($row_zq['name'],1,50,"utf-8");
	$qf_name=substr($row_qf['nianji'],0,4).$row_qf['cengci']."*".mb_substr($row_qf['name'],1,50,"utf-8");
	//$yl_name=substr($row_yl['nianji'],0,4).$row_yl['cengci']."*".mb_substr($row_yl['name'],1,50,"utf-8");
	//
	$return_str="<h2>昨日之星!</h2><br><h3>早起之星：</h3>". $zq_name."昨天".$row_zq['first_time']."进入实验室学习,". $str_message."<br><h3>勤奋之星：</h3>".$qf_name."昨天在".$row_qf['times']."个时间段中进入实验室，共学习了".round($row_qf['sum_miao']/3600,1)."小时".$str_message."<br><br><h4>积分可以兑换VIP了！</h4><a href='store.php'>积分商店</a>";
	
	check_vip();//检验vip超期，不需要用户登录就应该进行检验。所以放在这里。
	
	if(isset($_SESSION['u_id'])){
		$uid=$_SESSION['u_id'];
		$user_info=get_s_m($_SESSION['u_id']);
		$m=$user_info[0];
		$M=getM();
		// echo "<br><br><br><br><br>".$m;
		$_SESSION['vip']='N';
		$_SESSION['vseatid']='0';
		//在vip表中查询此用户的最新信息、且具有vip有效期的记录是否存在
		$sql="select * from (SELECT * FROM `vip`  WHERE  vuserid='$uid' order by id desc limit 1) as b where isvip=1 and  vipdate >=curdate()";
		$result=mysql_query($sql) or die(mysql_error());
		$row = mysql_fetch_array($result);

		//查询目前VIP总人数
		$sqltemp="SELECT count(*) as num_vip FROM vip where isvip=1";
		$resulttemp=mysql_query($sqltemp) or die(mysql_error());
		$rowtemp = mysql_fetch_array($resulttemp);
		$num_vip=$rowtemp['num_vip'];
		// echo $num_vip;
		// exit;

		//SELECT count(*) as num_vip FROM vip where isvip=1
		// echo $user_info[0].$user_info[1]."时长：".round($user_info[2],0);
		// exit;
		$vip_add_days=round($user_info[0],0)+3;
		// echo $vip_add_days;
		
		if($m>$M){
			//准备在这里进行两个操作，
			//操作1是延长vip时间；
			//另一个操作2是判断 是否可以新增vip
			//
			//本次查询发现此用户又具备了新的VIP资格，需要在VIP表中进行查询
			//如果最新的记录中并没有其vip信息，则新增；
			//如果查询到了原来记录中存在有效期内的vip信息，则查询是否有绑定座位，并追加新的VIP记录。
			
			$newdate = date('Y-m-d', strtotime($vip_add_days.' days'));
			// $sql="insert into vip (vuserid,vseatid,vipdate,isvip,riqi) values ($uid,0,DATE_SUB(CURDATE(), INTERVAL -$vip_add_days DAY) ,1,curdate())";
			// echo $sql;
			// exit;
			if($row['vuserid']===NULL and ($user_info[1]<=3 or  ($user_info[1]>3 and $num_vip<30)) ){
				//原来没有vip有效的信息；
				//只有排名前三 或者排名非前三且总VIP数<30 这种类型的用户，才可以新增VIP
				$_SESSION['vip']='Y';
				$vip_str="<h2><br>VIP资格提醒<br></h2>恭喜，你已经拥有".$vip_add_days."天的VIP资格，有效期到：".$newdate."。<br>期间你可以指定一个专有座位。<br>点击链接进行选座<a href='sel_seat.php'>点击选座</a>";
				$sql="insert into vip (vuserid,vseatid,vipdate,isvip,riqi) values ($uid,0,DATE_SUB(CURDATE(), INTERVAL -$vip_add_days DAY) ,1,curdate())";
				$result=mysql_query($sql) or die(mysql_error());
			}
			else{
				$_SESSION['vip']='Y';
				//原来还有有效的vip记录
				$vseatid=$row['vseatid'];
				$_SESSION['vseatid']=$vseatid;
				//这里加入判断，看原来的有效期时间是否比newdate还要长，然后使newdate得到新的有效期。
				$olddate=$row['vipdate'];
				// echo "<br><br><br><br><br><br>".$olddate;
				// exit;
				if($olddate>$newdate){
					$newdate=$olddate;	
					// echo "大";
					$vip_str="<h2><br>VIP资格提醒<br></h2>你的VIP资格有效期为：".$newdate."。<br>";
				}
				else {
					// echo "小";
					$vip_str="<h2><br>VIP资格提醒<br></h2>恭喜，你的VIP资格有效期延长至：".$newdate."。<br>";
				}
				// exit;
				// $vip_str="<h2><br>VIP资格提醒<br></h2>恭喜，你的VIP资格有效期延长至：".$newdate."。<br>";
				if($vseatid>0){
					//原来选定过座位
					$vip_str.="你绑定的座位编号是S".$vseatid."<br>点击链接变更绑定的座位<a href='sel_seat.php'>变更座位</a><br>绑定座位后，不能预约座位，请直接去专用桌位扫码使用！";
				}
				else{
					//虽然原来就有VIP资格，但还没有绑定座位
					$vip_str.="但你还没有绑定座位。<br>在VIP资格有效期内，你可以指定一个专有座位。<br>点击链接进行选座<a href='sel_seat.php'>点击选座</a>";
				}



				$sql="update vip set vipdate='$newdate',riqi=curdate() WHERE  vuserid='$uid'";
				$result=mysql_query($sql) or die(mysql_error());
			}


		}

		else{
			if($row['vuserid']===NULL){}
			else{
				//这次检查并没有具备新VIP功能，但曾经是VIP且还没有过期
				//需要显示此用户的到期时间，以及是否有绑定座位
				$_SESSION['vip']='Y';
				$vseatid=$row['vseatid'];
				$vipdate=$row['vipdate'];
				$_SESSION['vseatid']=$vseatid;
				$vip_str="<h2><br>VIP资格提醒<br></h2>你的VIP资格有效期至：".$vipdate."。<br>";
				if($vseatid>0){
					//原来选定过座位
					$vip_str.="你绑定的座位编号是S".$vseatid."<br>点击链接变更绑定的座位<a href='sel_seat.php'>变更座位</a><br>绑定座位后，不能预约座位，请直接去专用桌位扫码使用！";
				}
				else{
					//虽然原来就有VIP资格，但还没有绑定座位
					$vip_str.="但你还没有绑定座位。<br>在VIP资格有效期内，你可以指定一个专有座位。<br>点击链接进行选座<a href='sel_seat.php'>点击选座</a>";
				}
			}
		}
		$vip_str.="<br><br><h3>目前的VIP资格计算方式：</h3>根据前七天日均时长排名，排名前三则一定会获得VIP资格；排名第4——第17则根据总VIP人数以及相应排名进行计算。新增加VIP天数由日均时长决定。<br>本计算模型会根据实际情况进行调整。<br>你的7日数据：排第".$user_info[1]."名，日均时长".round($user_info[0],1)."小时。";
	}

	return($return_str.$vip_str);
}


function dlfile($file_url, $save_to){
	$content = file_get_contents($file_url);
	file_put_contents($save_to, $content);
}

function getdata($isbn){
	// ini_set('max_execution_time','15');
	//先从国图获得数据，页码、出版年、简介、分类号，再到中国图书网获取，若没有则再到豆瓣获取，后两者，只要发现没有从国图获得出版年数据，则使用自己获取的数据替换页码和出版年信息。
	//对于简介的处理，准备返回2个简介，其中一个是参考，正式的简介是在opac中显示的。
	
	//获取国图检索页面的action地址
	//$url='http://opac.nlc.cn/F/';
	$url='http://202.106.125.101/F/';
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_TIMEOUT,3);//设置连接超时 10秒钟
	curl_setopt($ch, CURLOPT_HEADER, false); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果把这行注释掉的话，就会直接输出 
	$result=curl_exec($ch); 
	curl_close($ch); 
	$text=str_replace(array("\r","\n","\t","\s"), '', $result); 

	//在内容中查找 action的属性值
	$pattern = '/action="(.*?)"/';
	preg_match($pattern, $text, $match);
	$action =  trim($match['1']);

	//com_str是待组合的查询字符串
	$com_str='?func=find-b&find_code=ISB&request='.$isbn.'&local_base=NLC01&filter_code_1=WLN&filter_request_1=&filter_code_2=WYR&filter_request_2=&filter_code_3=WYR&filter_request_3=&filter_code_4=WFM&filter_request_4=&filter_code_5=WSL&filter_request_5=';
	$com_str=$action.$com_str;
	// echo $com_str."<br>";
	// exit;
	//进到检索详情页面，得到页面内容：text2
	$ch2 = curl_init(); 
	curl_setopt($ch2, CURLOPT_URL, $com_str); 
	curl_setopt($ch2, CURLOPT_TIMEOUT,3);//设置连接超时 10秒钟
	curl_setopt($ch2, CURLOPT_HEADER, false); 
	curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1); //如果把这行注释掉的话，就会直接输出 
	$result2=curl_exec($ch2); 
	curl_close($ch2); 
	$text2=str_replace(array("\r","\n","\t","\s"), '', $result2); 
	//在内容text2中查找 特定td值
	//$pattern2 = '/\>*通用数据(.*)\<\/td[\s\S]/';
	$pattern2 ='/\>*通用数据.*?<td[^>]*>([^<]*)[\s\S]*\>*题名与责任.*?<A[^>]*>([^<]*)[\s\S]*\>*出版项.*?<A[^>]*>([^<]*)[\s\S]*\>*载体形态项.*?<td[^>]*>([^<]*)[\s\S]*\>*中图分类号.*?<A[^>]*>([^<]*)/';
	preg_match($pattern2, $text2, $match);
	//print_r($match);
	$arr = explode("&nbsp;",$match[2]);
	$title=$arr[0];
	$author=$arr[3];
	$arr = explode("&nbsp;",$match[3]);
	$publisher=str_replace(',','',$arr[2]);
	$pubyear_nlc=$arr[3];
	$arr = explode("&nbsp;",$match[4]);
	//$pages=$arr[0];
	$pages_nlc=trim(substr( $arr[0], strpos($arr[0],',')+1));
	$category_nlc=$match[5];
	
	$pattern3 ='/\>*内容提要.*?<td[^>]*>([^<]*)/';
	preg_match($pattern3, $text2, $match3);
	$abstract_nlc=str_replace("'",'"',$match3[0]);

	if(empty($title)){
		//在国图没有找到数据
		$nlc='n';
	}
	else{
		$nlc='y';
		$book_source='“国图”' ;
		$book_title=$title;
		$book_author=$author;
		$book_publisher=$publisher;
		$book_pubyer=$pubyear_nlc;
		$book_pages=$pages_nlc;
		$book_abstract2=$abstract_nlc;
		$book_category=$category_nlc;
	}
	
// echo $book_author.$book_title.$book_publisher.$book_pubyer;
// exit;
	
	//根据isbn查询中国图书网，得到检索的第一个结果页
	//$url='http://www.bookschina.com/book_find2/?stp='.$isbn.'&sCate=2';
	$url='http://59.151.36.165/book_find2/?stp='.$isbn.'&sCate=2';
	$ch = curl_init(); 
	//curl_setopt($ch, CURLOPT_URL, "http://www.bookschina.com/book_find2/?stp=9787544225632&sCate=2"); 
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_TIMEOUT,3);//设置连接超时 10秒钟
	curl_setopt($ch, CURLOPT_HEADER, false); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果把这行注释掉的话，就会直接输出 
	$result=curl_exec($ch); 
	//echo $result;
	curl_close($ch); 
	
	 //取得指定URL的內容，並储存至text
	//$text=file_get_contents($url);
	 
	//去除換行及空白字元（序列化內容才需使用）
	$text=str_replace(array("\r","\n","\t","\s"), '', $result); 
	


	
	//从中国图书馆第一个结果页中的cover信息
	//取出div标签且id為cover的內容，並储存至阵列match
	preg_match('/<div[^>]*class="cover"[^>]*>(.*?) <\/div>/si',$text,$match);
	//打印match[0]
	$str_cover= $match['0'];
	
	//获取了div cover之后，从中找到第一个链接地址。
		$pattern = '/a href="(.*?)"/';
		preg_match($pattern, $str_cover, $match);
		$url2 =  trim($match['1']);//url2就是详情页的相对地址

		$pattern = '/title="(.*?)"/';
		preg_match($pattern, $str_cover, $match);
		$title =  trim($match['1']);//title是书名 不过是gb2312编码
		$title =iconv('GB2312', 'UTF-8//IGNORE', $title);//转码
		//echo $title;
		//exit;
		
	//获取otherInfor的内容	
	$pattern='/<div class=\"otherInfor\".*?>.*?<\/div>/ism';
	preg_match($pattern,$text,$match);
	$str_otherInfor= $match['0'];
	//print_r($match);
	
	//$pattern ='/<span.*>(.*)<\/span>/isU';
	
	//获取otherInfor的两个a标签的内容 作者、出版社
	$pattern = '/<a.*?>(.*?)<\/a>/is';
	preg_match_all($pattern, $str_otherInfor ,$match);
	//print_r($match);
	$author=iconv('GB2312', 'UTF-8//IGNORE',$match[1][0]);
	$publisher=iconv('GB2312', 'UTF-8//IGNORE',$match[1][1]);
	//echo $publisher;
// echo $book_author.$book_title.$book_publisher.$book_pubyer;
// exit;	
	if(!isset($author) or $author==''){//这里已经可以判断出在中国图书网并没有找到这个书目信息。
		//现在看看能否继续在这里追加获取豆瓣的数据。
		//如果在中国图书网抓取到了数据，则不再到豆瓣中抓取。
		$bsc='n';
		//$url = "https://api.douban.com/v2/book/isbn/:".$isbn;
		$url = "https://api.douban.com/v2/book/isbn/:".$isbn;
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_TIMEOUT,3);//设置连接超时 10秒钟
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//这个是重点。
		$result = curl_exec($curl);
		curl_close($curl);

		$book_array = (array) json_decode($result, true);
		
		$db_book_title = $book_array["title"];
        $db_book_author = $book_array["author"][0];

		//echo "!!!!!!!!".$book_title."<br>";
		
		  if(empty($db_book_title) or $db_book_title=='' or !isset($db_book_author) or $db_book_author==''){
		  	// echo "进入判断";
		  	// exit;
			  //豆瓣也没找到。
			 // echo "!!!!<br><br>!!!!!";
// echo $book_author.$book_title.$book_publisher.$book_pubyer;
// exit;		
			$db='n';	

			 return array($book_title, $book_author, $book_cover, $book_publisher,$book_pubyer,$book_price,$book_pages,$book_abstract, $book_authorinfo,$book_catalog,$book_source,$book_category,$book_abstract2);

		  }
		  else{
		  	$book_source.='“豆瓣”' ;
		  	// echo "其他";
		  	// exit;
			$book_title=$db_book_title;
			$book_author=$db_book_author;
			$book_publisher = $book_array["publisher"];
			
			if($nlc=='n'){//只有在国图没找到，才需要重新赋值。
				$book_pubyer = $book_array["pubdate"];
				$book_pages = $book_array["pages"];
			}
			
			$book_price = $book_array["price"];
			$book_abstract = $book_array["summary"]; 
	  
			  //下载书封
			$save_to='bookcover/'.$isbn.'.jpg';
			/* if(file_exists($save_to)){
				//文件已经存在
				//echo "书封原来就存在，<img src='$save_to' />";
			}
			else */
			{

				$ch = curl_init ();
				curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
				curl_setopt($ch, CURLOPT_TIMEOUT,3);//设置连接超时 10秒钟
				curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );//跳过ssl验证
				curl_setopt ( $ch, CURLOPT_URL, $book_array["image"] );
				ob_start ();
				curl_exec ( $ch );
				$return_content = ob_get_contents ();
				ob_end_clean ();
				
				$fp= fopen($save_to,"a");
				fwrite($fp,$return_content);
				//echo "下载完成，<img src='$save_to' />";
			}
		$book_cover=$save_to;
		  // $book_source="豆瓣读书";	
        return array($book_title, $book_author, $book_cover, $book_publisher,$book_pubyer,$book_price,$book_pages,$book_abstract, $book_authorinfo,$book_catalog,$book_source,$book_category,$book_abstract2);		  

		  }

		  //sleep(5);

	
        //$book = new Book($book_title, $book_author, $book_cover, $book_publisher,$book_pubyer,$book_price,$book_pages,$book_abstract, $book_authorinfo,$book_catalog,$book_source);

	}
	
	//这里 是在中国图书网找到了图书的信息
	$book_source.='“中国图书网”' ;
	
	//获取otherInfor的一个span标签的内容；出版时间
	$pattern ='/<span.*>(.*)<\/span>/isU';
	preg_match($pattern, $str_otherInfor ,$match);
	$pubyear=substr($match[1],0,10);
	//echo $pubyear;

	
	//获取priceWrap的内容	
	$pattern='/<div class=\"priceWrap\".*?>.*?<\/div>/ism';
	preg_match($pattern,$text,$match);
	$str_priceWrap= $match['0'];
	//获取priceWrap的del标签的内容
	$pattern = '/<del.*?>(.*?)<\/del>/is';
	preg_match($pattern, $str_priceWrap ,$match);
	$price=substr($match[1],5);
	//echo $price;
	
		//下载书封
	$save_to='bookcover/'.$isbn.'.jpg';
	/* 	if(file_exists($save_to)){
		//文件已经存在
		//echo "书封原来就存在，<img src='$save_to' />";
	}
	else */
	{//不管原来是否存在，都直接覆盖。
		$pattern = '/data-original="(.*?)"/';
		preg_match($pattern, $text, $match);
		$fvalue =  trim($match['1']);
		//得到书封的链接地址
		dlfile($fvalue,$save_to);
		//echo "下载完成，<img src='$save_to' />";
	}

	//根据url2查询详情页面
	$url='http://www.bookschina.com/'.$url2;
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_TIMEOUT,3);//设置连接超时 10秒钟
	curl_setopt($ch, CURLOPT_HEADER, false); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果把这行注释掉的话，就会直接输出 
	$result=curl_exec($ch); 
	//echo $result;
	curl_close($ch); 
	$text=str_replace(array("\r","\n","\t","\s"), '', $result);
	
	//获取otherInfor的内容	
	$pattern='/<div class=\"otherInfor\".*?>.*?<\/div>/ism';
	preg_match($pattern,$text,$match);
	$str_otherInfor= $match['0'];
	//获取otherInfor的i标签的内容——页码信息
	$pattern = '/<i.*?>(.*?)<\/i>/is';
	preg_match($pattern, $str_otherInfor ,$match);	
	$pages=$match[1];
	
	//获取brief的内容	
	$pattern='/<div class=\"brief\".*?>.*?<\/div>/ism';
	preg_match($pattern,$text,$match);
	$str_brief= $match['0'];
	//获取brief的p标签的内容——简介
	$pattern = '/<p.*?>(.*?)<\/p>/is';
	preg_match($pattern, $str_brief ,$match);	
	$abstract=iconv('GB2312', 'UTF-8//IGNORE',$match[1]);
	
	//获取catalogSwitch的内容	——目录
	$pattern='/<div id=\"catalogSwitch\".*?>.*?<\/div>/ism';
	preg_match($pattern,$text,$match);
	$catalog= iconv('GB2312', 'UTF-8//IGNORE',$match[0]);
	$catalog=str_replace("'",'',$catalog);//有的目录中带有' 会造成无法更新数据库的错误。这里进行删除所有的'

	
	//获取作者简介的内容	 excerpt里的p
	$pattern='/<div class=\"excerpt\".*?>.*?<\/div>/ism';
	preg_match($pattern,$text,$match);
	$str_excerpt= $match['0'];
	//获取excerpt的p标签的内容——简介
	$pattern = '/<p.*?>(.*?)<\/p>/is';
	preg_match($pattern, $str_excerpt ,$match);	
	$authorinfo=iconv('GB2312', 'UTF-8//IGNORE',$match[1]);
	

	
	$book_title = $title;
	$book_author = $author;
	$book_cover = $save_to;
	$book_publisher = $publisher;
	if($nlc=='n'){
		$book_pubyer = $pubyear;
		$book_pages = $pages;
	}
	$book_price = $price;
	$book_abstract = $abstract;
	$book_authorinfo = $authorinfo;        
	$book_catalog = $catalog;
	// $book_source=$source;
		
    return array($book_title, $book_author, $book_cover, $book_publisher,$book_pubyer,$book_price,$book_pages,$book_abstract, $book_authorinfo,$book_catalog,$book_source,$book_category,$book_abstract2);
}

?>