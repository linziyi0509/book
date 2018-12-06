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

$sql=  "SELECT *,c.name uname,b.name bname FROM bookrec a,bookinfo b,user c where a.bookinfo_id=b.id and a.rec_id=c.id and a.rec_id='$u_id' order by a.id desc  ";
$num=mysql_query($sql);//当前频道条数
$total=mysql_num_rows($num);//总条数	
$totalpage=round($total/10)+2;



$sqlajax="SELECT *,c.name uname,b.name bname FROM bookrec a,bookinfo b,user c where a.bookinfo_id=b.id and a.rec_id=c.id and a.rec_id='$u_id' order by a.id desc  limit $start,$pagenum";
$resultajax=mysql_query($sqlajax);
		  
$arr = array(); //定义一个数组先
$xuhao=($page-1)*$pagenum+1;
while ($rowajax = mysql_fetch_array($resultajax)) { 
	$bname=$rowajax['bname'];
	$isbn=$rowajax['isbn'];
	$rec_reason=$rowajax['rec_reason'];
	$rec_date=$rowajax['rec_date'];
	$reply_content=$rowajax['reply_content'];
  	$arr[] = array(
		'xuhao'=>$xuhao++,
		'bname'=>$bname,
        'isbn' => $isbn ,
        'rec_reason' => $rec_reason ,
        'rec_date' => $rec_date ,
        'reply_content' => $reply_content ,
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




