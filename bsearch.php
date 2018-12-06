<?php 
include_once("header.php");		
session_start();
include_once('inc/conn.php');
$num=mysql_query("SELECT *,c.name as bookname,d.name as username FROM borrow a, book b, bookinfo c,user d 
where a.user_id=d.id and a.book_id=b.id and b.bookinfo_id=c.id and status='未归还' ");//当前频道条数
$total=mysql_num_rows($num);//总条数	
$totalpage=round($total/10)+2;
//$str_num="目前外借中的图书共有".$total."条记录。";
	//echo $totalpage;	
?>

<div class="container-fluid">
	<div class="page-header">
		<h1>借还查询：</h1>
	</div>
	
	
<div class="well form-search" >
	<input type="text" id="kw" class="input-medium search-query" name="kw" value="" />
	<input class="btn" type="button" value="搜 索"/>
	<input type="radio" name="status_id" value="1" checked class="status_id" />未还
	<input type="radio" name="status_id" value="2"  class="status_id" />已还
	<input type="radio" name="status_id" value="3"  class="status_id" />全部

     
	<br />
	<div id="show_num"></div>
</div>

<div>

</div>

	<table class="table table-striped table-bordered table-condensed">
		<thead>
          <tr>
            <th width="5%">ID</th>
            <th width="5%">状态</th>
			<th width="6%">图书编号</th>
            <th width="10%">图书名称</th>
            <th width="6%">读者编号</th>
            <th width="6%">读者姓名</th>
            <th width="5%">借书日期</th>
            <th width="5%">续借日期</th>
            <th width="5%">应还日期</th>
            <th width="5%">还书日期</th>
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
	status_id=1;
	i = 1; //设置当前页数 
	total=-1;
	totalpage=-1;
	str_tmp='';
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
		nb_getJson(0); //加载第一页
	});
	
$('input:radio').on('click', function() {
	status_id=$('input:radio:checked').val();
	
	btn_click();
	
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
	//search_kw=" and (bookname like '%"+kw+"%' or user_name like '%"+kw+"%' or username like '%"+kw+"%' or bookcode like '%"+kw+"%' or findcode like '%"+kw+"%') ";
	console.log(search_kw);
	$("#lists").empty();
	i=1;
	nb_getJson(0);
}
		 
function nb_getJson(page) {
console.log(kw);
console.log(status_id);
	$(".nodata").show().html("<img src='img/loading.gif'/>");
	$.getJSON("bsearch_ajax.php", {page:i,kw:kw,id:status_id}, function(json){
		$('.nodata').hide();
			if (json.length>0) {
				var str = "";
				$.each(json, function(index, array) {
					//下面这块是css代码来显示样式，数据是通过array数组传回来动态显示在这里
					var str="<tr><td>"+array['xuhao']+
							"</td><td>"+array['status']+
							"</td><td>"+array['bookcode']+
							"</td><td>"+array['bookname']+
							"</td><td>"+array['usercode']+
							"</td><td>"+array['username']+
							"</td><td>"+array['borrowdate']+
							"</td><td>"+array['renewdate']+
							"</td><td>"+array['returndate']+
							"</td><td>"+array['factreturndate']+
							"</td></tr>";
					$("#lists").append(str);
					total=array['total'];
					totalpage=array['totalpage'];
					
				});
				$(".nodata").hide();
			}
			else {
				total=0;
				totalpage=0;
				showEmpty();

			}
			
			
			if(status_id==1)str_tmp="未还";
			if(status_id==2)str_tmp="已还";
			if(status_id==3)str_tmp="全部";
			if(kw!==''){
			var str_num="关键词为'<font color=red>"+kw+"</font>'的'<font color=red>"+str_tmp+"</font>'记录，共有"+total+"条";
			}else{
			var str_num="<font color=red>"+str_tmp+"</font>的借还记录共有"+total+"条";
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
