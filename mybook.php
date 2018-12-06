<?php 
include_once("header.php");	
include_once('inc/conn.php');	
if($islogin>0){			
?>

<div class="container-fluid">
	<div class="page-header">
	</div>

	<div id="mycss_booklist">

			<?php
			$sql="select * FROM booklist where u_id='$u_id' and status='等待确认'  order by id desc ";
			$result=mysql_query($sql) or die(mysql_error());
			$total=mysql_num_rows($result);
			?>
			
			<?php

			if($row = mysql_fetch_array($result))
				{?>
		<div id="show_num">你的当前预约为：</div>
		<div id="lists">					
					
						
			<div class=bookrec_list_td>
				<div class='bianhao'>座位编号——<?php echo $row['s_id']?></div>
				<div class='list_td_con'>剩余待确认时间——
					<?php 
					$cha_time=time()-strtotime($row['yuyue_time']);
					$cha_m=30-floor($cha_time/60);
					if($cha_m>0){
						echo '还有'.$cha_m."分钟进行现场确认";
					}
					?>
				</div>
				<form action="del_book.php" method="post">

					<input type="hidden" value="<?php echo $row['id']?>"  name="cang_id" >
					 <button  type="submit">取消此次预约</button>
				</form>
			</div>
		</div>
		<?php
		}
		else{
			?>
			<div id="show_num">目前没有正在预约的信息！</div>
		<?php
		}
		?>
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
