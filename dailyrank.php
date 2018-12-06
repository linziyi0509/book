<?php
/*
 * 统计每日的排名 并将统计的数据入库以及更新数据
 * 首先验证如果今天已经统计过 则不执行
 * 1.根据需求看 如果现在还是vip会员 不做操作
 * 2.不是vip 如果是前三名 直接成为vip 有效时长 加7天
 * 3.4-17名 则根据现有VIP人数是否超过30人，如果没达到30人，则获得VIP资格 当然这里 我没计算前三名 只计算当前库总的vip个数
 */
set_time_limit(0);
include_once('inc/conn.php'); 
//剔除不符合规则的数据后进行计算排名
$ranksql = "select * from dailyranking ORDER BY createtime desc limit 1";
$res = mysql_query($ranksql);
while ($rowajax = mysql_fetch_assoc($res)) { 
	if(mktime(0,0,0,date("m"),date("d"),date("Y"))<$rowajax["createtime"]){
		exit('今天已经获取过排行榜');
	}
}
//strtotime("-1 week")
$ranksql = "select id,u_id,sum(endtime-starttime) as duration from (SELECT
	id,
	u_id,
	unix_timestamp(queren_time) starttime,
	unix_timestamp(tuichu_time) endtime,
	`status`,
	bztuichu,
	queren_time,
	tuichu_time
FROM
	booklist
WHERE
	queren_time >= '".date("Y-m-d 00:00:00",1539100800)."'
AND tuichu_time IS NOT NULL
AND tuichu_time != '00:00:00 00:00:00'
ORDER BY
	queren_time DESC) datares GROUP BY u_id ORDER BY duration desc limit 30";
	//闭包
	$rankData = function() use($ranksql) {
		$arr = [];
		$res = mysql_query($ranksql);
		while ($rowajax = mysql_fetch_assoc($res)) { 
			$arr[] = $rowajax;
		}
		return ($arr);
	};
	$rankDataRes = $rankData($ranksql);
	//获取现有vip信息 只获取用户的id即可 获取未过期的
	$vipinfosql="SELECT v.userid FROM user u ,vipinfo v where u.id=v.userid and v.endtime>".time();
	$vipinfoData = function() use($vipinfosql) {
		$arr = [];
		$res = mysql_query($vipinfosql);
		while ($rowajax = mysql_fetch_assoc($res)) { 
			$arr[] = $rowajax["userid"];
		}
		return ($arr);
	};
$vipinfoDataRes = $vipinfoData($vipinfosql);
mysql_query("START TRANSACTION");//开启事务
$rankTopThree = array_slice($rankDataRes,0,3);//取出前三个数据
$rankTopThreeRes = resultVip($vipinfoDataRes, $rankTopThree);
$rankFourToSeventeen = array_slice($rankDataRes,3,14);//取出4~17的数据
$rankFourToSeventeenRes = resultVip($vipinfoDataRes, $rankFourToSeventeen);
//批量插入排行榜
$sql = "INSERT INTO dailyranking(`createtime`,`userid`,`rank`,`duration`) VALUES ";
foreach($rankDataRes as $key=>$val){
    $sql .= "(".time().",".$val['u_id'].",".($key+1).",".$val['duration']."),";
}
$sql = substr($sql,0, strlen($sql)-1);
$resBatch = mysql_query($sql);
if(!in_array(false,$rankTopThreeRes) && !in_array(false,$rankFourToSeventeenRes) && $resBatch){
	mysql_query("COMMIT");
	exit('提交成功。');
}else{
	mysql_query("ROLLBACK");
	exit('数据回滚。');
}
/**
 * 处理排行榜前几名vip信息
 * type = 1 前三
 * type = 2 4~17名 如果当前vip用户信息达到30 不在添加
 */
function resultVip($vipinfoData,$rankData,$type=1){
	$resArr = [];
	$vipinfoCount = count($vipinfoData);
	$rankcount = count($rankData);
	if($type == 2){
		if($vipinfoCount>30){
			return ;
		}else{
			$rankcount = 30 - $vipinfoCount;
		}
	}
	for($j=0;$j<$rankcount;$j++){
		$flag = true;
		for($i=0;$i<$vipinfoCount;$i++){
			if($rankData[$j]["u_id"] == $vipinfoData[$i]){
				$flag = false;
			}
		}
		if($flag == true){
			//没有值 插入 没有排除已过期的
			$vipid = getVipInfo($rankData[$j]["u_id"]);
			if($vipid>0){
				$sql = "UPDATE vipinfo set starttime = ".time().",endtime = ".strtotime("+1 week").", status=1 where userid=".$rankData[$j]["u_id"];
				$res = mysql_query($sql);
				$resArr[] = $res;
				$sql1 = "INSERT INTO vipinfodetail(vipid, createtime, typename, days, type) VALUES(".$vipid.",".time().",'排名',7,1)";
				$res1 = mysql_query($sql1);
				$resArr[] = $res1;
			}else{
				$sql = "REPLACE INTO vipinfo(userid, starttime, endtime, status, createtime) VALUES (".$rankData[$j]["u_id"].",".time().",".strtotime("+1 week").",1,".time().")";
				$res = mysql_query($sql);
				$resArr[] = $res;
				$sql1 = "INSERT INTO vipinfodetail(vipid, createtime, typename, days, type) VALUES(".mysql_insert_id().",".time().",'排名',7,1)";
				$res1 = mysql_query($sql1);
				$resArr[] = $res1;
			}
		}
	}
	return $resArr;
}
//获取vipinfo中是否有过期的会员 并且返回相对应的id
function getVipInfo($userid){
	$ranksql = "select * from vipinfo where userid = ".$userid;
	$res = mysql_query($ranksql);
	while ($rowajax = mysql_fetch_assoc($res)) { 
		if(isset($rowajax['id']) && $rowajax['id']){
			return $rowajax['id'];
		}else{
			return 0;
		}
	}
}
?>