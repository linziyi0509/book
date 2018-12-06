<?php
error_reporting(0);
session_start();
$islogin=$_SESSION['islogin'];
$name=$_SESSION['name'];
$u_id=$_SESSION['u_id'];
$b_id=$_POST["cang_id"];

 if($islogin==0){
	echo '<script>';
	echo "location='index.php'";
	echo '</script>';
 }
							 
include_once('inc/conn.php');	


// $sql="UPDATE booklist SET  quxiao_time = now(), status='空闲',beizhu='取消预约' WHERE id='$b_id'";
$sql="UPDATE booklist SET  quxiao_time = now(), status='空闲',bzyuyue='取消预约' WHERE id='$b_id'";

//echo $sql;
mysql_query($sql);
// echo "<script>location.href='mybook.php';</script>";
echo "<script>location.href='yuyue_list.php';</script>";


?>



