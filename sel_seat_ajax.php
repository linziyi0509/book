<?php
include_once('inc/conn.php');
date_default_timezone_set('ETC/GMT-8');
// $ssuid=$_GET['ssuid']; 
// $sql="select * from (select a.id as seatid,a.bianhao,a.miaoshu from seat a  where a.status='Y' ) b where seatid!=(select vseatid from (select * from (SELECT  * FROM `vip`   order by id desc ) as base  group by vuserid  ) as base2 where isvip=1 and vseatid>0) order by seatid" ;
// $sql="select * from seat  where status='Y' and seat.id not in (select vseatid from vip where isvip=1)" ;
//这个查询是为了把所有未被绑定的可以正常使用的座位列出来，但现在的查询效率太低.
//应该是使用了not in的问题。再找找其他的查询语句。
$sql="select a.id as seatid,a.miaoshu,a.bianhao from (select * from seat  where status='Y') a left join (select vseatid from vip where isvip=1) b on a.id=b.vseatid where b.vseatid is null order by a.id" ;
$result=mysql_query($sql) or die(mysql_error());
//这里通过前面的sql检索出了所有可以显示的预约座位信息列表
//result中存储了17条记录的信息；
while($row = mysql_fetch_array($result))
	{
		//通过while循环，将17条记录依次取出，并进行变量赋值；
		$button_str='';//button_str是显示在座位编号中的提示文字，可以对这个字符串进行编辑，使其可以显示取消预约的按钮
		$sid=$row['seatid'];//sid是座位的id
		$miaoshu=$row['miaoshu'];
		$bianhao=$row['bianhao'];
		$sign_show_button='y';

   $arr[] = array(
   		'sid'=>$sid,
   		'bianhao'=>$bianhao,
   		'miaoshu'=>$miaoshu,
   		'sign_show_button'=>$sign_show_button,
   		'button_str'=>$button_str,
    ); 
} 

// print_r($arr);
echo json_encode($arr);  //转换为json数据输出  

?>