<?php 
include_once("header.php");
include_once('inc/conn.php');
?>
<div class="container-fluid">
	<div class="page-header">
		<h1>我的</h1>
	</div>
	<div>
		<ul>
			<li><a href="mypurview.php">借阅权限</a></li>
			<li><a href="myborrow.php">在借图书</a></li>
			<li><a href="myborrow_his.php">借阅历史</a></li>
			<li><a href="myfund.php">我的欠费</a></li>
			<li><a href="mybookrec.php">我的荐购</a></li>
			<li><a href="mybook.php">当前预约</a></li>
			<li><a href="mybook_his.php">预约历史</a></li>
			<li><a href="myscore.php">我的积分</a></li>
			<li><a href="edit_user.php?id=<?php echo $u_id;?>">修改密码</a></li>
		</ul>
	</div>
	
</div>

<?php 
include_once("footer.php");
?>


  </body>
</html>
