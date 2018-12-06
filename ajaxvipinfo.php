<?php
include_once('inc/conn.php');
$page = intval($_GET['page']);  //获取请求的页数  
$kw = $_GET['kw'];
if(!empty($kw)){
	$search = " and (name like '%$kw%' or username like '%$kw%')";
}else{
	$search = " and 1=1";
}
$search.= " and endtime>=".mktime(0,0,0,date("m"),date("d"),date("Y"));
//$page = 1;  //获取请求的页数  
$pagenum = 10; //每页数量 
$start = ($page - 1) * $pagenum; 
$sql=  "SELECT v.*,u.name FROM user u ,vipinfo v where u.id=v.userid $search";

$num=mysql_query($sql);//当前频道条数
$total=mysql_num_rows($num);//总条数	
$totalpage=round($total/10)+2;

$sqlajax="SELECT v.*,u.name FROM user u ,vipinfo v where u.id=v.userid $search limit $start,$pagenum";
$resultajax=mysql_query($sqlajax); 
$arr = array(); //定义一个数组先
$xuhao=($page-1)*$pagenum+1;
while ($rowajax = mysql_fetch_array($resultajax)) { 
	$vipid=$rowajax['id'];
	$sql= "select * from vipinfodetail where vipid='$vipid'";
	$result = mysql_fetch_assoc(mysql_query($sql));
	$arr[] = array(
		'id' => $vipid,
		'name' => str_ireplace($kw,'<font color=red>'.$kw.'</font>',$rowajax['name']) ,
		'starttime' => date("Y-m-d H:i:s",$rowajax['starttime']),
		'endtime' => date("Y-m-d H:i:s",$rowajax['endtime']),
		'totalday'=> intval(($rowajax['endtime']-$rowajax['starttime'])/(60*60*24)),
		'detail' => $result,
		'total'=>$total,
		'totalpage'=>$totalpage
	); 
} 
mysql_free_result($resultajax);
echo json_encode($arr);  //转换为json数据输出  
?>