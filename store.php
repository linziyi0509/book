<?php 
include_once("header.php");	
include_once('inc/conn.php');	
$sql="SELECT sum(score) as sum_score FROM score where user_id='$u_id' ";
$result=mysql_query($sql) or die(mysql_error());
$row = mysql_fetch_array($result);
$sum_score=$row['sum_score'];	
if($islogin>0){			
?>

<div class="container-fluid">
	<div class="page-header">
	</div>

	<div id="mycss_booklist">
		<div id="show_num">你的可用积分为：<?php echo $sum_score ?>分</div>
		<div class="container-fluid">
			<h4>积分获得途径：</h4>

			<br />1、<a href='attend.php'>签到</a>。每个用户每天可签到一次，积分获取规则：签到初始值为1分，连续签到每天积分额外加1，第7天开始不再额外增加。若中断签到，将从初始值开始重新计算。<br />
			2、借还图书。每次超过5天的正常借还完整的过程完成后积分增加20分。
			<br />3、公共座位扫码使用。每次正常使用（正常退出释放座位）超过10分钟会有积分。具体积分为：10分钟——60分钟，增加4分；60分钟——120分钟，增加10分；120分钟以上 增加12分。
			<br />4、每日的早起之星（最早进入实验室使用公共座位学习）增加10分。
			<br />5、每日的勤奋之星（当天在实验室公共座位学习最长时间）增加10分。
			<br />6、捐赠图书。捐赠的图书经过管理员筛选并正式入库上架的图书，按照价格*10的方式给捐赠者增加积分。
			<br />
			<br />
		</div>
		<div id="lists">	
		<?php
		$sql="SELECT * FROM gift ";
		$result=mysql_query($sql) or die(mysql_error());
		$i=1;
		
		while($row = mysql_fetch_array($result))
			{
				//因为下面的兑换按钮是循环出现，所以每个兑换按钮的name上的差别，用于jquery传递gift_id
				//而cang_id则用于，在返回json之后，在其中显示的内容。
			$gift_id=$row['id'];
			$cang_id='cang'.$row['id'];
			
			//要计算出当前用户，当前商品的已兑换次数，需要根据$gift_id在exchange表中查询
			$sql="SELECT count(*) as times FROM `exchange` where user_id='$u_id' and gift_id='$gift_id'";
			$result_tmp=mysql_query($sql) or die(mysql_error());
			$row_tmp = mysql_fetch_array($result_tmp);
			$times=$row_tmp['times'];
			
		?>

				
					
						
			<div class=bookrec_list_td>
				<div class='bianhao'>商品<?php echo $i?></div>
				<div class='list_td_con'>商品名称——<?php echo $row['gift_name']?></div>
				<div class='list_td_con'>所需积分——<?php echo $row['gift_score']?></div>
				<div class='list_td_con'>可兑上限——<?php echo $row['gift_times']?></div>
				<div class='list_td_con'>已兑次数——<?php echo $times?></div>
				<form action="" method="post">
					<input type="hidden" value="<?php echo $row['id']?>"  name="gift_id" id="gift_id" >
					 <input  type="button" name='<?php echo $gift_id ?>' class="exchange" value="兑换" />
					 <div id='<?php echo $cang_id?>'></div>
				</form>
		</div>
	  <?php
			$i++;
			}
		?>
	</div>


</div>

<?php 
}else{

	echo "<br><br><br><br>请先登录，再完成相关操作！";
}
include_once("footer.php");
?>


  </body>
<script type="text/javascript">
//$('.exchange').on('click', function() {
$('.exchange').on('click', function() {
	//var gift_id = $('#gift_id').val();
	var gift_id = $(this).attr('name');
	var data = "action=getlink&gift_id="+gift_id;
	$.getJSON("exchange_ajax.php", data, function(response){
	//$('#sub_deduction').hide();
	var cang_id=response.cang_id;
	//alert(cang_id);
	$('#'+cang_id).text(response.mess);
	//$('#cang1').text(response.mess);
	});
});
</script>
</html>
