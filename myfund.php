<?php 
include_once("header.php");	
if($islogin>0){
include_once('inc/conn.php');

$num=mysql_query("select * from borrow a, user_special b,book c,bookinfo d where b.user_id='$u_id' and b.borrow_id=a.id and a.book_id=c.id and c.bookinfo_id=d.id  ");//当前频道条数
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
		var str_num="你共有"+<?php echo $total?>+"此欠费记录";
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
		$.getJSON("myfund_ajax.php", {page:i}, function(json){
			$('.nodata').hide();
				console.log(json.length);
					if (json.length>0) {
					var str = "";
					$.each(json, function(index, array) {
						//下面这块是css代码来显示样式，数据是通过array数组传回来动态显示在这里
						//console.log(array['ispay']);
					var str="<div class=bookrec_list_td><div class='bianhao'>序号："+array['xuhao']+"</div>"+
						"<div class='list_td_con'>书名——"+array['bookname']+"</div>"+
						"<div class='list_td_con'>借阅时间——"+array['borrowdate']+"</div>"+
						"<div class='list_td_con'>应还时间——"+array['returndate']+"</div>"+
						"<div class='list_td_con'>实还时间——"+array['factreturndate']+"</div>"+
						"<div class='list_td_con'>欠费金额——"+array['fund']+"</div>";
					if(array['ispay']=='是'){
						str+="<div class='list_td_con'>已还清</div></div>";
					}
					else{
						console.log(array['borrow_id']);
						str+="<form action='' method='post'><input type='hidden' value='"+array['borrow_id']+"'  name='borrow_id' id='borrow_id' ><button  type='button'  id='sub_deduction' >积分抵扣</button><div id='cang'></div></form></div>";

				
					}	
					
						$("#lists").append(str);
						total=array['total'];
						totalpage=array['totalpage'];
						$('#sub_deduction').on('click', function() {
							var borrow_id = $('#borrow_id').val();
							var data = "action=getlink&id="+borrow_id;
							//alert(data);
							$.getJSON("myfund_do_ajax.php", data, function(response){
							$('#sub_deduction').hide();
							$('#cang').text(response.mess);
							});  
						});
						
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
