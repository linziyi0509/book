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
		<h1>实验室的书目信息：</h1>
	</div>
	
	
<div class="well form-search" >
	<input type="text" id="kw" class="input-medium search-query" name="kw" value="" />
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
            <th width="10%">书名</th>
			<th width="20%">书封</th>
            <th width="10%">isbn</th>
            <th width="10%">出版社</th>
            <th width="10%">作者</th>
            <th width="5%">分类号</th>
            <th width="5%">出版时间</th>
            <th width="5%">单价</th>
            <th width="5%">可借册数</th>
            <th width="15%">编号</th>
            
         
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
			//var str_num = '<?php echo $str_num;?>'; 
			//$("#show_num").append(str_num);
			}
		else{
			//totalpage=totalp;

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
		
		
/* $("body").scrollTop(10);//控制滚动条下移10px
if( $("body").scrollTop()>0 ){
//alert("有滚动条");
$str_test="a";
}else{
//alert("没有滚动条");
$str_test="b";
}

if($str_test=='b'){
	nb_getJson(0);
} */


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
	//var kw = $(this).attr('name');
	//search_kw=" and (name like '%"+kw+"%' or isbn like '%"+kw+"%' or author like '%"+kw+"%' or publisher like '%"+kw+"%' or category like '%"+kw+"%' or pubyear like '%"+kw+"%' or findcode like '%"+kw+"%' or bookcode like '%"+kw+"%' or bookinfo like '%"+kw+"%') ";
	//var data = "search="+search_kw;
/* 	$.getJSON("opac_total_ajax.php", {search:search_kw}, function(response){
		//$('#sub_deduction').hide();
		total=response.total;
		totalpage=response.totalpage;
		
		var str_num="查询关键词为'<font color=red>"+kw+"'</font>的图书，共有"+total+"本";
		$("#show_num").empty();
		$("#show_num").append(str_num);	
	}); */
	

		
	$("#lists").empty();
	i=1;
	nb_getJson(0);
}
		 
	function nb_getJson(page) {
		$(".nodata").show().html("<img src='img/loading.gif'/>");
		//var kw='<?php echo $kw;?>';
		//var search="<?php echo $search;?>";
		//var data="page="+i;
		//alert(data);
		console.log(kw);
		$.getJSON("opac_ajax.php", {page:i,kw:kw}, function(json){
			$('.nodata').hide();
				console.log(json.length);
					if (json.length>0) {
					var str = "";
					$.each(json, function(index, array) {
						//下面这块是css代码来显示样式，数据是通过array数组传回来动态显示在这里
						var str="<tr><td>"+array['xuhao']+
								"</td><td><a href ='opac_detail.php?bookinfo_id="+
								array['bookinfo_id']+"'>"+array['name']+
								"</a></td><td><img src='"+
								array['img']+
								"' /></td><td>"+
								array['isbn']+
								"</td><td>"+
								array['publisher']+
								"</td><td>"+array['author']+
								"</td><td>"+array['category']+
								"</td><td>"+array['pubyear']+
								"</td><td>"+array['price']+
								"</td><td>"+array['restnum']+
								"</td><td>"+array['findcode']+
								"</td></tr>";
					
/* 						var str = "<div class=\"newsitem-mes\">";
						var str = str + "<div class=\"newsitem-mes-title\">";
						var str = str +  array['xuhao'];
						var str = str +  array['title'];
						var str = str + "<div class=\"newsitem-content\">";
						var str = str + "<div class=\"time\">" + array['isbn'] + "</div>";
						var str = str + "<div class=\"badge\">" + array['author'] + "</div>";
						var str = str + "</div>";
						var str = str + "</div>"; */
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
					
					if(kw!==''){
						var str_num="查询关键词为'<font color=red>"+kw+"</font>'的图书，共有"+total+"本";

					}
					else{
						var str_num="库中共有"+total+"本书";
					}
					$("#show_num").empty();
					$("#show_num").append(str_num);	
				
		
			});
		i++;
	}
				
	function showEmpty() {
		 $(".nodata").show().html("我是有底线的......");
	} 	


</script>

  </body>
</html>
