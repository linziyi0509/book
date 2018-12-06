<?php 
include_once("header.php");
include_once('inc/conn.php');
$bookinfo_id=$_GET[bookinfo_id];
$sql= "select * from bookinfo where id='$bookinfo_id'";
$result=mysql_query($sql) or die(mysql_error());
$row = mysql_fetch_array($result);
?>

<header id="opac_jPanel1" class="mui-bar mui-bar-nav">
	<span id="opac_jIconfont1" class="mui-icon mui-icon-arrowleft"></span>
	<h1 id="opac_jLabel1" class="mui-title">图书详情</h1>
</header>
<div id="book_detail">
	<img src="<?php echo $row['img']?>" />
	<?php
	echo "<br />书名：".$row['name'];

	echo "<br />isbn：".$row['isbn'];
	echo "<br />出版社：".$row['publisher'];
	echo "<br />作者：".$row['author'];
	echo "<br />分类号：".$row['category'];
	echo "<br />出版时间：".$row['pubyear'];
	echo "<br />单价：".$row['price'];
	echo "<br />页码：".$row['pages'];
	echo "<br />总册数：".$row['num'];
	echo "<br />可借册数：".$row['restnum'];
	echo "<br />作者简介：".$row['b_authorinfo'];
	echo "<br />图书简介：".$row['b_abstract'];
	echo "<br />图书简介2:".$row['bookinfo'];
	echo "<br />目录:".str_replace(' ','<br>',$row['b_catalog']);
	echo "<br />";
	$sql= "select * from book where bookinfo_id='$bookinfo_id'";
	//$sql="select a.id abook_id,a.findcode,a.bookcod, a.bookinfo_id,b.book_id, b.status from book a, borrow b where a.bookinfo_id='$bookinfo_id' and a.id=b.book_id and status='未归还'";
	$result=mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		
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
			echo "<br />索书号：".$row['findcode']." 图书编号：<font color=red>".$row['bookcode']."外借中</font>".$tmpstr;
		}
		else{
			echo "<br />索书号：".$row['findcode']." 图书编号：".$row['bookcode'].$tmpstr;
		}
		
	}

	?>
</div>


<?php 
include("footer.php");
?>

</body>

<script type="text/javascript" src="js/mui.min.js"></script>
<script type="text/javascript" src="m_opac_detail.js"></script>
<script type="text/javascript">
</script>

</html>
