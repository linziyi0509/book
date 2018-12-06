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

$sql=  "select * from borrow a, user_special b,book c,bookinfo d where b.user_id='$u_id' and b.borrow_id=a.id and a.book_id=c.id and c.bookinfo_id=d.id  order by b.id desc  ";
$num=mysql_query($sql);//当前频道条数
$total=mysql_num_rows($num);//总条数	
$totalpage=round($total/10)+2;



$sqlajax="select * from borrow a, user_special b,book c,bookinfo d where b.user_id='$u_id' and b.borrow_id=a.id and a.book_id=c.id and c.bookinfo_id=d.id  order by b.id desc limit $start,$pagenum";
$resultajax=mysql_query($sqlajax);
		  
$arr = array(); //定义一个数组先
$xuhao=($page-1)*$pagenum+1;
while ($rowajax = mysql_fetch_array($resultajax)) { 
	$bookname=$rowajax['name'];
	$borrowdate=$rowajax['borrowdate'];
	$returndate=$rowajax['returndate'];
	$factreturndate=$rowajax['factreturndate'];
	$fund=$rowajax['fund'];
	$ispay=$rowajax['ispay'];
	$borrow_id=$rowajax['borrow_id'];
  	$arr[] = array(
		'xuhao'=>$xuhao++,
		'bookname'=>$bookname,
        'borrowdate' => $borrowdate ,
        'returndate' => $returndate ,
        'factreturndate' => $factreturndate ,
        'fund' => $fund ,
        'ispay' => $ispay ,
        'borrow_id' => $borrow_id ,
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




