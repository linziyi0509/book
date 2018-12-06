<?php 
include_once("header.php");	
if($islogin>0){
include_once('inc/conn.php');

$num=mysql_query("SELECT *,c.name uname,b.name bname FROM bookrec a,bookinfo b,user c where a.bookinfo_id=b.id and a.rec_id=c.id and a.rec_id='$u_id' order by a.id desc ");//当前频道条数
$total=mysql_num_rows($num);//总条数	
$totalpage=round($total/10)+2;

?>

<div id="opac_jPanel5" class="">
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
		var str_num="你共推荐了"+<?php echo $total?>+"本图书";
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
		$.getJSON("mybookrec_ajax.php", {page:i}, function(json){
			$('.nodata').hide();
				console.log(json.length);
					if (json.length>0) {
					var str = "";
					$.each(json, function(index, array) {
						//下面这块是css代码来显示样式，数据是通过array数组传回来动态显示在这里
					var str="<div class=bookrec_list_td><div class='bookrec_xuhao'>序号："+array['xuhao']+"</div>"+
						"<div class='bookrec_bname'>书名——"+array['bname']+"</div>"+
						"<div class='bookrec_isbn'>ISBN——"+array['isbn']+"</div>"+
						"<div class='bookrec_rec_reason'>荐购原因——"+array['rec_reason']+"</div>"+
						"<div class='bookrec_rec_date'>荐购时间——"+array['rec_date']+"</div></div>";
						
					
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
