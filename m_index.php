<?php 
include_once("header.php");
include_once('inc/conn.php');
if($islogin<1){
	//显示未登录的信息
	$show_str="为了使用系统更多功能，<br />请先<span id='example'>登录</span>哦！";
}
else{
	$weekarray=array("日","一","二","三","四","五","六");
	$h=date('G');
	if ($h<11) $str_tmp= '早上好';
	else if ($h<13) $str_tmp='中午好';
	else if ($h<17) $str_tmp= '下午好';
	else $str_tmp= '晚上好';
	
	$show_str="<div id='index_jLabel2'>亲爱的".$name."，".$str_tmp."</div><br />今天是".date('Y-m-d')."，星期".$weekarray[date("w")];
}

?>

<body data-curpagename="index" >
<header id="index_jPanel1" class="mui-bar mui-bar-nav">
	<div id="index_jPanel4" class=" mui-pull-right">
	
	</div>
	<div id="index_jPanel3" class="vjcenterpanel Panel_c">
		<div id="index_title">BbookK系统</div>
	</div>
</header>
<div id="index_jPanel2" class="mui-content">
	<div id="index_jPanel5" class="">
		<div id="index_jPanel6" class="">
			<div id="mycss_index_bg" class="">
				<a href="attend.php"><div id="attend">签到</div></a>
				<div id="mycss_index_str">
					<?php echo $show_str;?>
				</div>

			</div>
		</div>

			
				<div class="mui-slider-item mui-slider-item-duplicate">
				</div>
				<div >
					<div id="index_jNavigation2" class=" mui-segmented-control-inverted  mui-segmented-control navbar navimg_circlefill" data-col="3">
						<a id="index_jNavigation2_item1" class="mui-control-item" href="m_opac.php">
							<span class="mui-tab-label"><span class="mui-icon mui-icon-search"></span><div>书目检索</div></span>
						</a>
						<a id="index_jNavigation2_item2" class="mui-control-item" href="bookrec_list.php">
							<span class="mui-tab-label"><span class="mui-icon mui-icon-plus"></span><div>荐购列表</div></span>
						</a>
						<a id="index_jNavigation2_item3" class="mui-control-item" href="yuyue_list.php">
							<span class="mui-tab-label"><span class="mui-icon mui-icon-compose"></span><div>预约座位</div></span>
						</a>
						<a id="index_jNavigation2_item4" class="mui-control-item" href="help.php">
							<span class="mui-tab-label"><span class="mui-icon-extra mui-icon-extra-order"></span><div>预约说明</div></span>
						</a>
						<a id="index_jNavigation2_item5" class="mui-control-item" href="book_manager.php">
							<span class="mui-tab-label"><span class="mui-icon-extra mui-icon-extra-xiaoshuo"></span><div>图书借还</div></span>
						</a>
						<a id="index_jNavigation2_item6" class="mui-control-item" href="catalog.php">
							<span class="mui-tab-label"><span class="mui-icon-extra mui-icon-extra-class"></span><div>图书编目</div></span>
						</a>
						<a id="index_jNavigation2_item7" class="mui-control-item" href="store.php">
							<span class="mui-tab-label"><span class="mui-icon-extra mui-icon-extra-regist"></span><div>积分商城</div></span>
						</a>
						<a id="index_jNavigation2_item8" class="mui-control-item" href="#">
							<span class="mui-tab-label"><span class="mui-icon mui-icon-contact"></span><div>主管管理</div></span>
						</a>
						<a id="index_jNavigation2_item9" class="mui-control-item" href="#">
							<span class="mui-tab-label"><span class="mui-icon-extra mui-icon-extra-heart"></span><div>测试变身</div></span>
						</a>
					</div>
					
				</div>

		


	</div>

</div>


<nav id='index_jNavigation1' class='navbar_style3 mui-slider-indicator  mui-segmented-control navbar' data-col='4'>
	<a id='index_jNavigation1_item1' class='mui-control-item mui-active' href='m_index.php'>
		<span class='mui-tab-label'><span class='mui-icon mui-icon-home'></span><div>首页</div></span>
	</a>
	<a id='index_jNavigation1_item2' class='mui-control-item' href='m_bookrec.php'>
		<span class='mui-tab-label'><span class='mui-icon mui-icon-chatboxes'></span><div>荐购</div></span>
	</a>
	<a id="index_jNavigation1_item3" class="mui-control-item" href="yuyue_list.php">
		<span class="mui-tab-label"><span class="mui-icon-extra mui-icon-extra-cate"></span><div>预约</div></span>
	</a>
	<a id='index_jNavigation1_item4' class='mui-control-item' href='m_my.php'>
		<span class='mui-tab-label'><span class='mui-icon mui-icon-person'></span><div>我的</div></span>
	</a>

</nav>
<?php 
include_once("footer.php");
?>


</body>


<script type="text/javascript" src="m_index.js"></script>
<script type="text/javascript">
</script>
</html>
