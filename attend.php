<?php 
include_once("header.php");		

if($islogin>0){
//查询数据库，看看这次签到能得几分
//逻辑：先根据用户id，到score表中查询remark字段是“签到”的最近一条记录，比对日期，如果今天已经签到，则显示已签到，若没有今天的日期，则查询上一次此用户的签到日期是否是昨天，如果是昨天，则查看昨天对应的签到分数是多少，再算出这次签到应得的分数。

include_once('inc/conn.php');
$sql="select * from score where  user_id='$u_id' and remark='签到' order by id desc limit 1";
// echo $sql;
// exit;
$result=mysql_query($sql) or die(mysql_error());
if($row = mysql_fetch_array($result)){
	//到这里，只是查询到有此用户的签到记录
	//先判断，是否是今天的签到
	$Date_1 = date("Y-m-d");
	$Date_2 = date("Y-m-d",strtotime($row['time']));
	$d1 = strtotime($Date_1);
	$d2 = strtotime($Date_2);
	$cha_days= round(($d1-$d2)/3600/24);
	//echo $cha_days."天";
	//exit;
	//查询此条 日期是否是昨天，还要看score是几分。
	$score=$row['score'];
	
	if($cha_days>=1){
		if($cha_days==1 and $score<7){
			$score++;
		}
		if($cha_days>1){
			$score=1;
		}
		//开始签到流程
		//向score表中追加记录 user_id,score,operator(签到无需填写此项),time,remark
		$sql="insert into score (user_id,score,time,remark,operator) values('$u_id','$score',now(),'签到','system') ";
		$result=mysql_query($sql) or die(mysql_error());
		$str="签到成功，积分+".$score;
	}else{
		//今天已经签到
		$str="今天已经签到！积分已经+".$score;
	}

}
else{
	//首次打卡
	$score=1;
	$sql="insert into score (user_id,score,time,remark,operator) values('$u_id','$score',now(),'签到','system') ";
	$result=mysql_query($sql) or die(mysql_error());
	$str="恭喜，首次签到成功，积分+".$score;
	
}

?>

<div class="container-fluid">
	<div class="page-header">
		<h4><?php echo $str ?></h4>
	</div>

</div>
<div class="container-fluid">
<h4>积分用途：<a href='store.php'>积分商店</a></h4>
<h4>积分获得途径：</h4>

<br />1、签到。每个用户每天可签到一次，积分获取规则：签到初始值为1分，连续签到每天积分额外加1，第7天开始不再额外增加。若中断签到，将从初始值开始重新计算。<br />
2、借还图书。每次超过5天的正常借还完整的过程完成后积分增加20分。
<br />3、公共座位扫码使用。每次正常使用（正常退出释放座位）超过10分钟会有积分。具体积分为：10分钟——60分钟，增加4分；60分钟——120分钟，增加10分；120分钟以上 增加12分。
<br />4、每日的早起之星（最早进入实验室使用公共座位学习）增加10分。
<br />5、每日的勤奋之星（当天在实验室公共座位学习最长时间）增加10分。
<br />6、捐赠图书。捐赠的图书经过管理员筛选并正式入库上架的图书，按照价格*10的方式给捐赠者增加积分。
<br />
<br />
</div>



<?php 
}else{

	echo "<br><br><br><br>请先登录，再完成相关操作！";
}
include_once("footer.php");
?>


</body>
</html>
