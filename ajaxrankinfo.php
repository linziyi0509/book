<?php
include_once('inc/conn.php');
$page = intval($_GET['page']);  //获取请求的页数  
$name = $_GET['name'];
if(!empty($name)){
	$search = " and (name like '%$name%' or username like '%$name%')";
}else{
	$search = " and 1=1";
}
//十天的排行榜
$search.= " and createtime>=".strtotime("-10 days");
//$page = 1;  //获取请求的页数  
$pagenum = 10; //每页数量 
$start = ($page - 1) * $pagenum; 
$sql=  "SELECT d.*,u.name FROM user u ,dailyranking d where u.id=d.userid $search";

$num=mysql_query($sql);//当前频道条数
$total=mysql_num_rows($num);//总条数	
$totalpage=round($total/10)+2;

$sqlajax="SELECT d.*,u.name FROM user u ,dailyranking d where u.id=d.userid $search limit $start,$pagenum";
$resultajax=mysql_query($sqlajax); 
$arr = array(); //定义一个数组先
$xuhao=($page-1)*$pagenum+1;
while ($rowajax = mysql_fetch_array($resultajax)) { 
	$vipid=$rowajax['id'];
	$arr[] = array(
		'id' => $vipid,
		'name' => str_ireplace($name,'<font color=red>'.$name.'</font>',$rowajax['name']) ,
		'createtime' => date("Y-m-d H:i:s",$rowajax['createtime']),
		'rank' => $rowajax['rank'],
		'duration'=> $rowajax['duration'],
		'total'=>$total,
		'totalpage'=>$totalpage
	); 
} 
mysql_free_result($resultajax);
echo json_encode($arr);  //转换为json数据输出  
?>