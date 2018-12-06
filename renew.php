<?php 
error_reporting(0);
session_start();
$islogin=$_SESSION['islogin'];
$name=$_SESSION['name'];
$username=$_SESSION['username'];
$u_id=$_SESSION['u_id'];
date_default_timezone_set('ETC/GMT-8');
if($islogin<1){
 //未登录用户不能使用续借功能
 $_SESSION['F_URL'] ='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
echo "<script>location='index.php'</script>";
exit;
}
include_once('inc/conn.php');

$borrow_id=$_POST['borrow_id'];
$bookrenewdays=$_POST['bookrenewdays'];	
$returndate=$_POST['returndate'];	
//echo "code:".$bookcode."! days:".$bookrenewdays."应还日期：".$returndate."<br>";

//续借过程，需要更新borrow表中 对应bookcode的记录
//修改其中的returndate renewdate renewtimes
//先计算 加上续借天数后的应还日期
$days='+'.$bookrenewdays.' days';
$returndate=date("Y-m-d",strtotime($days,strtotime($returndate)));
//echo $returndate;

$sql="update borrow set returndate='$returndate', renewdate=now(), renewtimes=renewtimes+'1' where id='$borrow_id'";
//echo $sql;
$result=mysql_query($sql) or die(mysql_error());
echo "<script>alert('续借完成！');location='myborrow.php'</script>";

?>
