<?php 
error_reporting(0);
ini_set('max_execution_time','0');
include_once('inc/conn.php');
//date_default_timezone_set('ETC/GMT-8');
//$name='admin';//暂时没有通过登录获取用户名
//$isbn='9787113146849';

$sql="select * from book where pages='' ";
$result_nlc=mysql_query($sql) or die(mysql_error());
$num_y=0;
$num_x=0;
while($row = mysql_fetch_array($result_nlc)){
	 $isbn=$row['isbn'];
	 //$isbn='9787508633299';
	//获取国图检索页面的action地址
	$url='http://opac.nlc.cn/F/';
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_HEADER, false); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果把这行注释掉的话，就会直接输出 
	$result=curl_exec($ch); 
	curl_close($ch); 
	$text=str_replace(array("\r","\n","\t","\s"), '', $result); 

	//在内容中查找 action的属性值
	$pattern = '/action="(.*?)"/';
	preg_match($pattern, $text, $match);
	$action =  trim($match['1']);

	//com_str是待组合的查询字符串
	$com_str='?func=find-b&find_code=ISB&request='.$isbn.'&local_base=NLC01&filter_code_1=WLN&filter_request_1=&filter_code_2=WYR&filter_request_2=&filter_code_3=WYR&filter_request_3=&filter_code_4=WFM&filter_request_4=&filter_code_5=WSL&filter_request_5=';
	$com_str=$action.$com_str;
	//echo $com_str."<br>";
	//进到检索详情页面，得到页面内容：text2
	$ch2 = curl_init(); 
	curl_setopt($ch2, CURLOPT_URL, $com_str); 
	curl_setopt($ch2, CURLOPT_HEADER, false); 
	curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1); //如果把这行注释掉的话，就会直接输出 
	$result2=curl_exec($ch2); 
	curl_close($ch2); 
	$text2=str_replace(array("\r","\n","\t","\s"), '', $result2); 
	//在内容text2中查找 特定td值
	//$pattern2 = '/\>*通用数据(.*)\<\/td[\s\S]/';
	$pattern2 ='/\>*通用数据.*?<td[^>]*>([^<]*)[\s\S]*\>*题名与责任.*?<A[^>]*>([^<]*)[\s\S]*\>*出版项.*?<A[^>]*>([^<]*)[\s\S]*\>*载体形态项.*?<td[^>]*>([^<]*)[\s\S]*\>*中图分类号.*?<A[^>]*>([^<]*)/';
	preg_match($pattern2, $text2, $match);
	//print_r($match);
	$arr = explode("&nbsp;",$match[2]);
	$title=$arr[0];
	$author=$arr[3];
	$arr = explode("&nbsp;",$match[3]);
	$publisher=str_replace(',','',$arr[2]);
	$pubyear=$arr[3];
	$arr = explode("&nbsp;",$match[4]);
	//$pages=$arr[0];
	$pages=trim(substr( $arr[0], strpos($arr[0],',')+1));
	$category=$match[5];
	
	$pattern3 ='/\>*内容提要.*?<td[^>]*>([^<]*)/';
	preg_match($pattern3, $text2, $match3);
	$abstract=str_replace("'",'"',$match3[0]);
	//exit;
	if(empty($title)){
		$num_x++;
		$message = $message.$isbn.' ';
	}
	else{
		$num_y++;
		$sql="update book set pages='$pages',category='$category',g_pubyear='$pubyear',bookinfo='$abstract' where isbn='$isbn'";
		//echo "<br>".$sql;
		// echo "作者：".$author."<br>书名：".$title;
		// echo "<br>出版社：".$publisher."<br>出版时间：".$pubyear;
		// echo "<br>页码：".$pages;
		// echo "<br>分类号：".$category."<br>简介：".$abstract;
		// exit;
		mysql_query($sql);
	}
}
 $numxy=$num_x+$num_y;
echo "<br>共进行了".$numxy."次查询"."，有".$num_y."次成功"."，有".$num_x."次失败"."<br>没查询到的：".$message;

?> 