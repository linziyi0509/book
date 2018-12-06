<?php 
include_once("header.php");		
include_once('inc/conn.php');
$num=mysql_query("SELECT * FROM bookinfo a ,book b where a.id=b.bookinfo_id  group by bookinfo_id  ");//当前频道条数
//$num=mysql_query("SELECT * FROM bookinfo a ,book b where a.id=b.bookinfo_id  group by bookinfo_id  ");//当前频道条数
//$num=mysql_query("SELECT * FROM bookinfo where 1 $search order by id  ");//当前频道条数
$total=mysql_num_rows($num);//总条数	
$totalpage=round($total/10)+2;
//$str_num="目前库中共有".$total."本图书。";
	//echo $totalpage;	
?>

<div class="container-fluid">
	<div class="page-header">
		<h1>用户vip列表信息：</h1>
	</div>
	
	
<div class="well form-search" >
	用户名：<input type="text" id="kw" class="input-medium search-query" name="kw" value="" />
	<input class="btn" type="button" value="搜 索"/>
	<br />
	<div id="show_num"></div>
</div>

<div>

</div>

	<table class="table table-striped table-bordered table-condensed">
		<thead>
          <tr>
            <th width="6%">ID</th>
            <th width="10%">用户名</th>
			<th width="20%">开始时间</th>
            <th width="10%">结束时间</th>
            <th width="10%">总天数</th>
          </tr>
        </thead>
        <tbody id="lists">
		</tbody>
	</table>
	
	<div class="nodata"></div>
	
</div><!--/span-->



<?php 
include_once("footer.php");
?>
<script type="text/javascript">
	kw='';
	search_kw='';
	i = 1; //设置当前页数 
	total=-1;
	totalpage=-1;
	$(function() {
		if(total<0){
			totalpage = <?php echo $totalpage;?>;
		}
		var winH = $(window).height(); //页面可视区域高度 
		$(window).scroll(function() {
			if (i < totalpage) { // 当滚动的页数小于总页数的时候，继续加载
				var pageH = $(document.body).height();
				var scrollT = $(window).scrollTop(); //滚动条top 
				var aa = (pageH - winH - scrollT) / winH;
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

$('.btn').on('click', function() {
	btn_click();
});
	
 $("#kw").keyup(function(event) {
	 if (event.keyCode == "13") {//keyCode=13是回车键
		btn_click();
	 }
 });
function btn_click(){
	kw = $('#kw').val();
	$("#lists").empty();
	i=1;
	nb_getJson(0);
}
		 
	function nb_getJson(page) {
		$(".nodata").show().html("<img src='img/loading.gif'/>");
		$.getJSON("ajaxvipinfo.php", {page:i,kw:kw}, function(json){
			$('.nodata').hide();
				console.log(json.length);
					if (json.length>0) {
					var str = "";
					$.each(json, function(index, array) {
						//下面这块是css代码来显示样式，数据是通过array数组传回来动态显示在这里
						var str="<tr><td>"+array['id']+
								"</td><td><a href ='vipinfodetail.php?vipid="+
								array['id']+"&name="+array['name']+"'>"+array['name']+
								"</a></td><td>"+
								array['starttime']+
								"</td><td>"+
								array['endtime']+
								"</td><td>"+
								array['totalday']+
								"</td></tr>";
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
</body>
</html>
