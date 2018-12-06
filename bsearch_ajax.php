<?php
include_once('inc/conn.php');
$page = intval($_GET['page']);  //获取请求的页数  
$kw = $_GET['kw'];  
// echo "<br>前<br><br><br>".$kw;
$kw=iconv("GB2312","UTF-8//IGNORE",$kw); 
// echo "<br>后<br><br><br>".$kw;
// exit;
$id = intval($_GET['id']); 
//$search = $_GET['search'];   
$pagenum = 10; //每页数量 
$start = ($page - 1) * $pagenum; 
if($id==1){$search=" and status='未归还'";}
if($id==2){$search=" and status='已还'";}
if($id==3){$search="";}

$search.="and (bookcode like '%$kw%' or findcode like '%$kw%' or username like '%$kw%' or d.name like '%$kw%' or c.name like '%$kw%' )";
$sql=  "SELECT *,c.name as bookname,d.name as user_name FROM borrow a, book b, bookinfo c,user d 
where a.user_id=d.id and a.book_id=b.id and b.bookinfo_id=c.id $search";
$num=mysql_query($sql);//当前频道条数
$total=mysql_num_rows($num);//总条数	
$totalpage=round($total/10)+2;


$sqlajax="SELECT *,c.name as bookname,d.name as user_name FROM borrow a, book b, bookinfo c,user d 
where a.user_id=d.id and a.book_id=b.id and b.bookinfo_id=c.id $search limit $start,$pagenum";
$resultajax=mysql_query($sqlajax);
//echo "<br>".$sqlajax;		  
$arr = array(); //定义一个数组先
$xuhao=($page-1)*$pagenum+1;
$row = mysql_num_rows($resultajax);
if(!empty($row)){
while ($rowajax = mysql_fetch_array($resultajax)) { 
   $arr[] = array(
		'xuhao'=>$xuhao++,
        'status' => $rowajax['status'] ,
        'bookcode' => str_ireplace($kw,'<font color=red>'.$kw.'</font>',$rowajax['bookcode']) ,
        'bookname' => str_ireplace($kw,'<font color=red>'.$kw.'</font>',$rowajax['bookname']) ,
        'usercode' => str_ireplace($kw,'<font color=red>'.$kw.'</font>',$rowajax['username']) ,
        'username' => str_ireplace($kw,'<font color=red>'.$kw.'</font>',$rowajax['user_name']) ,
        'borrowdate' => $rowajax['borrowdate'] ,
        'renewdate' => $rowajax['renewdate'] ,
        'returndate' => $rowajax['returndate'] ,
        'factreturndate' => $rowajax['factreturndate'] ,
		'total'=>$total,
		'totalpage'=>$totalpage,
    ); 
}
}
else{
    //    $arr[] = array(

    //     'total'=>'0',
    //     'totalpage'=>'0',
    // );
 //$arr[]=array();
}
mysql_free_result($resultajax);
if (1) { 
    //print_r($arr);
	echo json_encode($arr);  //转换为json数据输出  
}
?>