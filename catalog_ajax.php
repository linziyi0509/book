<?php
// $action=$_GET['action'];
// echo $action;
include_once('inc/conn.php');

$action=$_GET['action']; 
$xuehao=intval($_GET['xuehao']); 
if($action=="getlink"){
$sql="select * FROM user where username='$xuehao'";
$result=mysql_query($sql);
	if($row = mysql_fetch_array($result)){
	
		$arr=array("name"=>$row['name'], "tishi"=>'','donor_id'=>$row['id']); 
	}else{
		$arr=array("name"=>"","tishi"=>'查无此人，请手动输入姓名','donor_id'=>0);
	}
$jarr=json_encode($arr); 
echo $jarr;

}
?>


