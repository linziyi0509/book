<?php 
include_once("header.php");	
include_once('inc/conn.php');	
if($islogin>0){	
	$sql="SELECT type  FROM user where id='$u_id' ";
	$result=mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_array($result);
	$user_type=$row['type'];
	if($user_type=='自定义')
	{//自定义角色的用户
		$sql="select * from user_special  where user_id='$u_id' and type='自定义'";
		$result_tmp=mysql_query($sql);
		$row_tmp = mysql_fetch_array($result_tmp);
		$booknum=$row_tmp['booknum'];
		$bookdatenum=$row_tmp['bookdatenum'];
		$bookrenewtimes=$row_tmp['bookrenewtimes'];
		$bookrenewdays=$row_tmp['bookrenewdays'];
	}
	else{
		//echo "常规角色";
		$sql="select * from user_type where name='$user_type'";
		$result_tmp=mysql_query($sql);
		$row_tmp = mysql_fetch_array($result_tmp);
		$booknum=$row_tmp['booknum'];
		$bookdatenum=$row_tmp['bookdatenum'];
		$bookrenewtimes=$row_tmp['bookrenewtimes'];
		$bookrenewdays=$row_tmp['bookrenewdays'];
	}

	//判断是否为自助借还用户
	if(in_array($islogin,array(2,3,6,7))){
		$zizhu="已开通";}
	else{$zizhu="未开通";}


?>

<div class="container-fluid">
	<div class="page-header">
	</div>

	<div id="mycss_booklist">

		<div id="show_num">你的当前借阅权限为：</div>
		<div id="lists">					
			<div class=bookrec_list_td>
				<div class='list_td_con'>可借册数——<?php echo $booknum?>本</div>
				<div class='list_td_con'>借期天数——<?php echo $bookdatenum?>天</div>
				<div class='list_td_con'>可续借数——<?php echo $bookrenewtimes?>次</div>
				<div class='list_td_con'>续期天数——<?php echo $bookrenewdays?>天</div>
				<div class='list_td_con'>自助借还——<?php echo $zizhu?></div>
			</div>
		</div>

	</div>


</div>

<?php 
}else{

	echo "<br><br><br><br>请先登录，再完成相关操作！";
}
include_once("footer.php");
?>


  </body>
</html>
