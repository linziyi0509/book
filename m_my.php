<?php 
include_once("header.php");
include_once('inc/conn.php');
//当前用户的角色
$role=$islogin;
switch($role){
	case 1:
	$str="普通用户";
	break;
	case 2:
	$str="普通用户-升级为自助借还的用户";
	break;
	case 3:
	$str="图书管理员";
	break;
	case 4:
	$str="公共管理员";
	break;
	case 5:
	$str="主管";
	break;
	case 6:
	$str="公共管理员-升级自助借还的用户";
	break;
	case 7:
	$str="主管-开通了自助借还功能";
	break;
	case 9:
	$str="超级用户";
	break;
}
?>

<body data-curpagename="my" >
<header id="my_jPanel1" class="mui-bar mui-bar-nav">
	
	<h1 id="my_jLabel1" class="mui-title">我的</h1>
</header>
<div id="my_jPanel2" class="mui-content">
	<ul id="my_jList4" class="mui-table-view mui-table-view-chevron">	
		<li class="mui-table-view-cell">
			
			<span id="my_jIconfont5" class="mui-icon-extra mui-icon-extra-peoples mui-pull-left"></span>
			<div id="my_jPanel10" class=" mui-pull-left">
			
			</div>
			<?php
			 if($islogin>0)
				{
			?>
				<div id="my_jLabel10"><?php echo $name?></div>
				<div id="my_jLabel11">学号：<?php echo $username?></div>
				<div id="my_jLabel12">当前角色为：<?php echo $str ?></div>
			<?php
				}
			else{
				?>
				<div id="example">请登录</div>
			<?php
				}
			?>
			
		</li>
	</ul>
	<div id="my_jPanel3" class="">
	
	</div>
	<div id="my_jNavigation1" class="navbar_style3 mui-slider-indicator mui-table-view navbar navimg_circlefill" data-col="3">
		<a id="my_jNavigation1_item1" class="vj-tablecell" href="mybook.php">
			<span class="mui-tab-label"><span class="mui-icon-extra mui-icon-extra-topic"></span><div>当前预约</div></span>
		</a>
		<a id="my_jNavigation1_item2" class="vj-tablecell" href="myborrow.php">
			<span class="mui-tab-label"><span class="mui-icon-extra mui-icon-extra-dictionary"></span><div>在借图书</div></span>
		</a>
		<a id="my_jNavigation1_item3" class="vj-tablecell" href="mybookrec.php">
			<span class="mui-tab-label"><span class="mui-icon mui-icon-chatboxes"></span><div>我的荐购</div></span>
		</a>
	</div>

	<div id="my_jNavigation2" class="navbar_style3 mui-slider-indicator mui-table-view navbar navimg_circlefill" data-col="3">
		<a id="my_jNavigation2_item1" class="vj-tablecell" href="mybook_his.php">
			<span class="mui-tab-label"><span class="mui-icon mui-icon-reload"></span><div>预约历史</div></span>
		</a>
		<a id="my_jNavigation2_item2" class="vj-tablecell" href="myborrow_his.php">
			<span class="mui-tab-label"><span class="mui-icon-extra mui-icon-extra-classroom"></span><div>借阅历史</div></span>
		</a>
		<a id="my_jNavigation2_item3" class="vj-tablecell" href="myfund.php">
			<span class="mui-tab-label"><span class="mui-icon-extra mui-icon-extra-classroom"></span><div>我的欠费</div></span>
		</a>
	</div>

	<div id="my_jPanel11" class="">
	
	</div>
	<div id="my_jPanel12" class="">
	
	</div>
	<ul id="my_jList1" class="mui-table-view mui-table-view-chevron">	
		<li class="mui-table-view-cell">
			<a id="my_jLinkAarrow2" class="mui-navigate-right" href='myscore.php'>
				<span id="my_jIconfont2" class="mui-icon-extra mui-icon-extra-xiaoshuo mui-pull-left"></span>
				<div id="my_jPanel4" class=" mui-pull-left">
				
				</div>
				<div id="my_jLabel2" class="mui-pull-left">我的积分</div>
			</a>
		</li>
		<li class="mui-table-view-cell">
			<a id="my_jLinkAarrow3" class="mui-navigate-right" href='store.php'>
				<span id="my_jIconfont4" class="mui-icon-extra mui-icon-extra-cart mui-pull-left"></span>
				<div id="my_jPanel8" class=" mui-pull-left">
				
				</div>
				<div id="my_jLabel4" class="mui-pull-left">积分商城</div>
			</a>
		</li>
		<li class="mui-table-view-cell">
			<a id="my_jLinkAarrow3" class="mui-navigate-right" href="mypurview.php">
				<span id="my_jIconfont3" class="mui-icon-extra mui-icon-extra-hotel mui-pull-left"></span>
				<div id="my_jPanel6" class=" mui-pull-left">
				
				</div>
				<div id="my_jLabel3" class="mui-pull-left">借阅权限</div>
			</a>
		</li>
		<li class="mui-table-view-cell">
			<a id="my_jLinkAarrow3" class="mui-navigate-right" href='edit_user.php?id=<?php echo $u_id;?>'>
				<span id="my_jIconfont3" class="mui-icon-extra mui-icon-extra-calc mui-pull-left"></span>
				<div id="my_jPanel8" class=" mui-pull-left">
				
				</div>
				<div id="my_jLabel3" class="mui-pull-left">修改密码</div>
			</a>
		</li>
		<li class="mui-table-view-cell">
		
		</li>
	</ul>
	<div id="my_jPanel5" class="">
	
	</div>
	<div id="my_jPanel7" class="">
	
	</div>
	<div id="my_jPanel17" class="vjcenterpanel Panel_c" >
		<button id="my_jButton1" type="button" class="mui-btn mui-btn-success btnsize_default mui-btn-block" onclick="location='action/action.loginOut.php'">退出登录</button>
	</div>
</div>

<nav id='index_jNavigation1' class='navbar_style3 mui-slider-indicator  mui-segmented-control navbar' data-col='4'>
	<a id='index_jNavigation1_item1' class='mui-control-item' href='m_index.php'>
		<span class='mui-tab-label'><span class='mui-icon mui-icon-home'></span><div>首页</div></span>
	</a>
	<a id='index_jNavigation1_item2' class='mui-control-item  ' href='m_bookrec.php'>
		<span class='mui-tab-label'><span class='mui-icon mui-icon-chatboxes'></span><div>荐购</div></span>
	</a>
	<a id="index_jNavigation1_item3" class="mui-control-item" href="yuyue_list.php">
		<span class="mui-tab-label"><span class="mui-icon-extra mui-icon-extra-cate"></span><div>预约</div></span>
	</a>
	<a id='index_jNavigation1_item4' class='mui-control-item mui-active' href='m_my.php'>
		<span class='mui-tab-label'><span class='mui-icon mui-icon-person'></span><div>我的</div></span>
	</a>

</nav>
<?php 
include_once("footer.php");
?>
</body>

<script type="text/javascript" src="my.js"></script>
<script type="text/javascript">
</script>
</html>