<?php 
include_once("header.php");
include_once('inc/conn.php');
echo $_SESSION['vip'];
if($_SESSION['vip']!='Y') 
	echo "<br><br><br><br>未登录或还未获得VIP资格！";
else
{

	
?>
	<div class="container-fluid">

	        <div class="page-header">
				<?php echo "<h2>选择要绑定哪个座位吧！</h2><br>绑定座位后，你可以在VIP有效时间内独自拥有这个固定座位。（不能再使用其他非绑定的座位）<br>使用专用座位时，你依然需要正常扫码使用、扫码释放座位。<br>系统会根据你的使用数据判断是否给你延长VIP时间。<br>"?>
			</div>
		<div id="item_two">
			<a id="item_y" class="active" href="#">
				<span class="mui-tab-label"><label>有PC座位</label></span>
			</a>
			<a id="item_n" class='' href="#">
				<span class="mui-tab-label"><label>无PC座位</label></span>
			</a>
		</div>
		<div class="nodata"></div>
		<div id="lists"></div>
		
	</div>

<?php 
	if( $_SESSION['mobile']=='y'){
		//如果是移动端，显示底部导航
?>
<header id="myarrow_out" class="mui-bar mui-bar-nav" style="background-color:#1287f5;">
  
   <span id="example"></span>
  <h1 id="myarrow_zi" class="mui-title" style="color: #fff;">绑定座位</h1>
</header>

<nav id='index_jNavigation1' class='navbar_style3 mui-slider-indicator  mui-segmented-control navbar' data-col='4'>
	<a id='index_jNavigation1_item1' class='mui-control-item' href='m_index.php'>
		<span class='mui-tab-label'><span class='mui-icon mui-icon-home'></span><div>首页</div></span>
	</a>
	<a id='index_jNavigation1_item2' class='mui-control-item  ' href='m_bookrec.php'>
		<span class='mui-tab-label'><span class='mui-icon mui-icon-chatboxes'></span><div>荐购</div></span>
	</a>
	<a id="index_jNavigation1_item3" class="mui-control-item mui-active"  href="yuyue_list.php">
		<span class="mui-tab-label"><span class="mui-icon-extra mui-icon-extra-cate"></span><div>预约</div></span>
	</a>
	<a id='index_jNavigation1_item4' class='mui-control-item ' href='m_my.php'>
		<span class='mui-tab-label'><span class='mui-icon mui-icon-person'></span><div>我的</div></span>
	</a>

</nav>
<?php
}
?>


<?php 
include_once("footer.php");
}
?>
<script type="text/javascript">
		// var ssuid =0;
		// var ssuid =<?php echo $ssuid; ?>;
		// alert(ssuid);
	$(function() {
		$(".nodata").show().html("<img src='img/loading.gif'/>");
		// var ssuid=<?php echo $ssuid;?>;
		// var data = "ssuid="+ssuid;
		$.getJSON("sel_seat_ajax.php",function(json){
		// $.getJSON("yuyue_list_ajax.php",function(json){
			console.log(json.length);
			var str = "";
			myjson=json;
			$.each(json, function(index, array) {
				//下面这块是css代码来显示样式，数据是通过array数组传回来动态显示在这里
				//这里要根据座位PC状态进行筛选
				if(array['miaoshu']=='带PC座位'){
					var str="<div class=list_td><div class='bianhao'>座位编号："+array['bianhao']+"</div>"+
						"<div class='miaoshu'>——"+array['miaoshu']+"</div>"+
						"<div class='button_str'>"+array['button_str']+"</div>";
					if(array['sign_show_button']=='y'){
						str+="<form action='sel_seat_post.php?id="+array['sid']+"' method='post'><input class='yuyue_but' type='submit' value='绑定' /></form>";
					}
					str+="</div>";	
				}
				//console.log(str);
				//$("#lists").empty();
				$("#lists").append(str);
			});
			$(".nodata").hide()
		});



	});

$("#item_n").on('click', function () {
		//让其class的值为active
		//让item_y的class值为空
		$('#item_n').attr("class",'active');
		$('#item_y').attr("class",'');
		// $.getJSON("yuyue_list_ajax.php", function(json){
			console.log(myjson);
			var str = "";
			$("#lists").empty();
			$.each(myjson, function(index, array) {
				//下面这块是css代码来显示样式，数据是通过array数组传回来动态显示在这里
				//这里要根据座位PC状态进行筛选
				if(array['miaoshu']=='无PC座位'){
					var str="<div class=list_td><div class='bianhao'>座位编号："+array['bianhao']+"</div>"+
						"<div class='miaoshu'>——"+array['miaoshu']+"</div>"+
						"<div class='button_str'>"+array['button_str']+"</div>";
					if(array['sign_show_button']=='y'){
						str+="<form action='sel_seat_post.php?id="+array['sid']+"' method='post'><input class='yuyue_but' type='submit' value='绑定' /></form>";
					}
					str+="</div>";	
				}
				//console.log(str);
				
				$("#lists").append(str);
			});
		}); 			
// });

$("#item_y").on('click', function () {
		$('#item_y').attr("class",'active');
		$('#item_n').attr("class",'');
		// $.getJSON("yuyue_list_ajax.php", function(json){
			console.log(myjson);
			var str = "";
			$("#lists").empty();
			$.each(myjson, function(index, array) {
				//下面这块是css代码来显示样式，数据是通过array数组传回来动态显示在这里
				//这里要根据座位PC状态进行筛选
				if(array['miaoshu']=='带PC座位'){
					var str="<div class=list_td><div class='bianhao'>座位编号："+array['bianhao']+"</div>"+
						"<div class='miaoshu'>——"+array['miaoshu']+"</div>"+
						"<div class='button_str'>"+array['button_str']+"</div>";
					if(array['sign_show_button']=='y'){
						str+="<form action='sel_seat_post.php?id="+array['sid']+"' method='post'><input class='yuyue_but' type='submit' value='绑定' /></form>";
					}
					str+="</div>";	
				}
				//console.log(str);
				
				$("#lists").append(str);
			});
		}); 			
// });


</script>

  </body>
</html>
