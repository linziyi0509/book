<?php
include_once('inc/conn.php');

$page = intval($_GET['page']);  //获取请求的页数  
$kw = $_GET['kw'];   
$kw=iconv("GB2312","UTF-8//IGNORE",$kw); 
$search = " and (name like '%$kw%' or isbn like '%$kw%' or author like '%$kw%' or publisher like '%$kw%' or category like '%$kw%' or pubyear like '%$kw%' or findcode like '%$kw%' or bookcode like '%$kw%' or bookinfo like '%$kw%') ";
//$page = 1;  //获取请求的页数  
$pagenum = 10; //每页数量 
$start = ($page - 1) * $pagenum; 

 // $kw= iconv('utf-8', 'GBK//IGNORE', $kw);
 // $search= iconv('utf-8', 'GBK//IGNORE', $search);
//$search = $_GET['search']; 
//$sql=  "SELECT * FROM bookinfo a ,book b where a.id=b.bookinfo_id $search group by bookinfo_id  ";
$sql=  "SELECT * FROM bookinfo a ,book b where a.id=b.bookinfo_id $search group by bookinfo_id  ";
//$sql=  "SELECT * FROM bookinfo a ,book b where a.id=b.bookinfo_id and pubyear like '%2017%' group by bookinfo_id  ";
// echo $sql."<br>";

$num=mysql_query($sql);//当前频道条数
$total=mysql_num_rows($num);//总条数	
$totalpage=round($total/10)+2;



$sqlajax="SELECT * FROM bookinfo a ,book b where a.id=b.bookinfo_id $search group by bookinfo_id   limit $start,$pagenum";
//$sqlajax="SELECT * FROM bookinfo a ,book b where a.id=b.bookinfo_id $search group by bookinfo_id   limit $start,$pagenum";
//$sqlajax="select * from bookinfo where 1 $search limit $start,$pagenum";
//$sqlajax="select * from bookinfo  limit $start,$pagenum";
//echo $sqlajax."<br>";
$resultajax=mysql_query($sqlajax);
		  
$arr = array(); //定义一个数组先
$xuhao=($page-1)*$pagenum+1;
while ($rowajax = mysql_fetch_array($resultajax)) { 
	$bookinfo_id=$rowajax['bookinfo_id'];
	$findcode='';
		$sql= "select * from book where bookinfo_id='$bookinfo_id'";
		$result=mysql_query($sql) or die(mysql_error());
		while($row = mysql_fetch_array($result)){
			$tmpstr='';
			//判断是否是捐赠的图书
			if(!empty($row['donor_name'])){
				$tmp_name="*".mb_substr($row['donor_name'],1,50,"utf-8");
				$tmpstr=" <font color=#05ABFF>此书由".$tmp_name.",于".$row['donor_date']."捐赠！</font>";
			}
			$book_id=$row['id'];
			$sql="select * from borrow where book_id='$book_id' and status='未归还'";
			$result_tmp=mysql_query($sql) or die(mysql_error());
			if($row_tmp = mysql_fetch_array($result_tmp)){
				//若找到，说明这个book_id正在外借中，
				$findcode.="<br />findcode:".$row['findcode']."<br /> bookcode:".$row['bookcode']."<font color=red>外借中</font>".$tmpstr;
				//$findcode.="<br />".$row['findcode']."<font color=red>外借中</font>".$tmpstr;
			}
			else{
				$findcode.= "<br />findcode:".$row['findcode']."<br /> bookcode:".$row['bookcode'].$tmpstr;
				//$findcode.="<br />".$row['findcode'].$tmpstr;
			}
			
		}

//echo $findcode;

   $arr[] = array(
		'xuhao'=>$xuhao++,
		'findcode'=>str_ireplace($kw,'<font color=red>'.$kw.'</font>',$findcode),
        'bookinfo_id' => $bookinfo_id ,
        'name' => str_ireplace($kw,'<font color=red>'.$kw.'</font>',$rowajax['name']) ,
        'img' => $rowajax['img'] ,
        'isbn' => str_ireplace($kw,'<font color=red>'.$kw.'</font>',$rowajax['isbn'])  ,
        'publisher' => str_ireplace($kw,'<font color=red>'.$kw.'</font>',$rowajax['publisher']) ,
        'author' => str_ireplace($kw,'<font color=red>'.$kw.'</font>',$rowajax['author']) ,
        'category' => str_ireplace($kw,'<font color=red>'.$kw.'</font>',$rowajax['category']),
        'pubyear' => str_ireplace($kw,'<font color=red>'.$kw.'</font>',$rowajax['pubyear']) ,
        'price' => str_ireplace($kw,'<font color=red>'.$kw.'</font>',$rowajax['price']) ,
        'restnum' => $rowajax['restnum'],
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
?>