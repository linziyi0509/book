<?php 
include_once("header.php");	
if($islogin>0){
include_once('inc/conn.php');

$num=mysql_query("select * FROM booklist where u_id='$u_id' and status='空闲'  order by id desc");//当前频道条数
$total=mysql_num_rows($num);//总条数	
$totalpage=round($total/10)+2;

?>

<div class="container-fluid">
	<div class="page-header">
	</div>

	<div id="mycss_booklist">
		<div id="show_num"></div>
		<div id="lists"></div>
		<div class="nodata"></div>
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
	i = 1; //设置当前页数 
	total=-1;
	totalpage=-1;
	$(function() {
		var str_num="你使用预约系统共"+<?php echo $total?>+"次";
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
		$.getJSON("mybook_his_ajax.php", {page:i}, function(json){
			$('.nodata').hide();
				console.log(json.length);
					if (json.length>0) {
					var str = "";
					$.each(json, function(index, array) {
						//下面这块是css代码来显示样式，数据是通过array数组传回来动态显示在这里
					var str="<div class=bookrec_list_td><div class='bianhao'>序号："+array['xuhao']+"</div>"+
						"<div class='list_td_con'>座位编号——"+array['s_id']+"</div>"+
						"<div class='list_td_con'>预约时间——"+array['yuyue_time']+"</div>"+
						"<div class='list_td_con'>确认时间——"+array['queren_time']+"</div>"+
						"<div class='list_td_con'>退出时间——"+array['tuichu_time']+"</div>"+
						"<div class='list_td_con'>预约信息——"+array['bzyuyue']+"</div>"+
						"<div class='list_td_con'>退出信息——"+array['bztuichu']+"</div></div>";
						// "<div class='list_td_con'>状态——"+array['beizhu']+"</div></div>";
						
					
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
