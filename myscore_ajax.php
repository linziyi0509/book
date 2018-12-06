<?php 
include_once('inc/conn.php');
date_default_timezone_set('ETC/GMT-8');	
session_start();
$islogin=$_SESSION['islogin'];
$name=$_SESSION['name'];//用户姓名
$username=$_SESSION['username'];//用户学号
$u_id=$_SESSION['u_id'];




$page = intval($_GET['page']);  //获取请求的页数  
$pagenum = 10; //每页数量 
$start = ($page - 1) * $pagenum; 

$sql=  "SELECT * FROM score where user_id='$u_id' order by id desc  ";
$num=mysql_query($sql);//当前频道条数
$total=mysql_num_rows($num);//总条数	
$totalpage=round($total/10)+2;



$sqlajax="SELECT * FROM score where user_id='$u_id' order by id desc limit $start,$pagenum";
$resultajax=mysql_query($sqlajax);
		  
$arr = array(); //定义一个数组先
$xuhao=($page-1)*$pagenum+1;
while ($rowajax = mysql_fetch_array($resultajax)) { 
	$time=$rowajax['time'];
	$remark=$rowajax['remark'];
	$score=$rowajax['score'];
	$operator=$rowajax['operator'];
  	$arr[] = array(
		'xuhao'=>$xuhao++,
		'time'=>$time,
        'remark' => $remark ,
        'score' => $score ,
        'operator' => $operator ,
        'total'=>$total,
		'totalpage'=>$totalpage,
		
    ); 
} 
mysql_free_result($resultajax);
//array_push($arr,"total"=>2);
if (1) { 
    //print_r($arr);
	echo json_encode($arr);  //转换为json数据输出  
}




