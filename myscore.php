<?php 
include_once("header.php");	
if($islogin>0){
include_once('inc/conn.php');
//计算当前用户的总积分
$sql="SELECT sum(score) as sum_score FROM score where user_id='$u_id' ";
$result=mysql_query($sql) or die(mysql_error());
$row = mysql_fetch_array($result);
$sum_score=$row['sum_score'];	
//echo $sum_score;		

$num=mysql_query("SELECT * FROM score where user_id='$u_id' order by id desc ");//当前频道条数
$total=mysql_num_rows($num);//总条数	
$totalpage=round($total/10)+2;

?>

<div class="container-fluid">
	<div class="page-header">
	</div>

	
		<div id="show_num"></div>
		<div id="lists"></div>
		<div class="nodata"></div>
	
</div>



<?php 
}else{

	echo "<br><br><br><br>请先登录，再完成相关操作！";
}
include_once("footer.php");
?>


  </body>
<script type="text/javascript">
	i = 1; //设置当前页数 
	total=-1;
	totalpage=-1;
	$(function() {
		var str_num="你的总积分为："+<?php echo $sum_score ?>+"分<br>共有"+<?php echo $total?>+"条积分记录";
		$("#show_num").append(str_num);	
		if(total<0){
			totalpage = <?php echo $totalpage;?>; 
			}
		var winH = $(window).height(); //页面可视区域高度 
		$(window).scroll(function() {
			if (i < totalpage) { // 当滚动的页数小于总页数的时候，继续加载
			//if (1) { // 当滚动的页数小于总页数的时候，继续加载
				var pageH = $(document.body).height();
				var scrollT = $(window).scrollTop(); //滚动条top 
				var aa = (pageH - winH - scrollT) / winH;
				//if(scrollT+winH==pageH){
				if (aa <0.01) {
				   nb_getJson(i)
				}
			} else { //否则显示无数据
				showEmpty();
			}
		});
		//j=0;
		nb_getJson(0); //加载第一页

	});

		 
	function nb_getJson(page) {
		$(".nodata").show().html("<img src='img/loading.gif'/>");
		$.getJSON("myscore_ajax.php", {page:i}, function(json){
			$('.nodata').hide();
				console.log(json.length);
					if (json.length>0) {
					var str = "";
					$.each(json, function(index, array) {
						//下面这块是css代码来显示样式，数据是通过array数组传回来动态显示在这里
					var str="<div class=list_td><div class='score_xuhao'>序号："+array['xuhao']+"</div>"+
						"<div class='score_remark'>类别——"+array['remark']+"</div>"+
						"<div class='score_score'>分数——"+array['score']+"</div>"+
						"<div class='score_operator'>操作员——"+array['operator']+"</div></div>";
					
						$("#lists").append(str);
						total=array['total'];
						totalpage=array['totalpage'];
						
					});
					$(".nodata").hide()

		
				} else {
					total=0;
					totalpage=0;
					showEmpty();
				}
					


				
		
			});
		i++;
	}
				
	function showEmpty() {
		 $(".nodata").show().html("我是有底线的......");
	} 	


</script>
</html>
