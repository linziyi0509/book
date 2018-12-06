<?php 
include_once('inc/conn.php');
session_start();
$islogin=$_SESSION['islogin'];
$name=$_SESSION['name'];//用户姓名
$username=$_SESSION['username'];//用户学号
$u_id=$_SESSION['u_id'];




$page = intval($_GET['page']);  //获取请求的页数  
$pagenum = 10; //每页数量 
$start = ($page - 1) * $pagenum; 

$sql=  "select * FROM booklist where u_id='$u_id' and status='空闲'  order by id desc  ";
$num=mysql_query($sql);//当前频道条数
$total=mysql_num_rows($num);//总条数	
$totalpage=round($total/10)+2;



$sqlajax="select * FROM booklist where u_id='$u_id' and status='空闲'  order by id desc limit $start,$pagenum";
$resultajax=mysql_query($sqlajax);
		  
$arr = array(); //定义一个数组先
$xuhao=($page-1)*$pagenum+1;
while ($rowajax = mysql_fetch_array($resultajax)) { 
	$s_id=$rowajax['s_id'];
	$yuyue_time=$rowajax['yuyue_time'];
	$queren_time=$rowajax['queren_time'];
	$tuichu_time=$rowajax['tuichu_time'];
	$bzyuyue=$rowajax['bzyuyue'];
	$bztuichu=$rowajax['bztuichu'];
  	$arr[] = array(
		'xuhao'=>$xuhao++,
		's_id'=>$s_id,
        'yuyue_time' => $yuyue_time ,
        'queren_time' => $queren_time ,
        'tuichu_time' => $tuichu_time ,
        'bzyuyue' => $bzyuyue ,
        'bztuichu' => $bztuichu ,
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




