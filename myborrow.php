<?php 
include_once("header.php");		
include_once('inc/conn.php');	
if($islogin>0){
 
//准备在正在借阅中的图书中，增加显示新信息，能够对有权限的用户 2367，增加还书的按钮。		
?>



<div class="container-fluid">
	<div class="page-header">
		
	</div>
	
	<div id="mycss_booklist">

			<?php
			$sql="SELECT * FROM borrow where user_id='$u_id' and status='未归还' ";
			$result=mysql_query($sql) or die(mysql_error());
			$total=mysql_num_rows($result);
			?>
		<div id="show_num">正借阅中的图书有<?php echo $total;?>本</div>
		<div id="lists">			
			<?php
			$i=1;
			while($row = mysql_fetch_array($result))
				{
					//得到剩余的续借次数；	
									//查询角色权限表，找到此用户的可续借次数以及续借天数。
									$sql="select * from user where id='$u_id'";
									$result_tmp=mysql_query($sql) or die(mysql_error());
									if($row_tmp = mysql_fetch_array($result_tmp)){
										if($row_tmp['type']!='自定义'){//是正常的角色的话，直接从user_type表获取此用户对应的借阅权限；
											$type=$row_tmp['type'];
											$sql="select * from user_type where name='$type'";
											$result_tp=mysql_query($sql) or die(mysql_error());
											if($row_tp = mysql_fetch_array($result_tp)){
												//找到了对应的角色后，提出此用户的借阅权限信息；
												$bookrenewtimes=$row_tp['bookrenewtimes'];
												$bookrenewdays=$row_tp['bookrenewdays'];
											}
										}
										else{//自定义的话，从user_special表获取此用户对应的借阅权限；
											$sql="select * from user_special where user_id='$u_id' and type='自定义'";
											$result_tp=mysql_query($sql) or die(mysql_error());
											if($row_tp = mysql_fetch_array($result_tp)){
												//找到了对应的角色后，提出此用户的借阅权限信息；
												$bookrenewtimes=$row_tp['bookrenewtimes'];
												$bookrenewdays=$row_tp['bookrenewdays'];
											}
										}
									}
									//查询当前记录对应的已经续借的次数；
									$bookalready_renewtimes=$row['renewtimes'];
									$remain_times=$bookrenewtimes-$bookalready_renewtimes;
									//根据$remain_times判断是否还有剩余的续借次数；
								//在这个处理逻辑中也顺便将exceedday计算出来，并update数据库，return_post中有相关代码。
								//但考虑到有的图书虽然逾期，但还有续借次数，可以使用续借功能，弥补一下。所以暂时不在这里加入计算逾期的代码。
					//根据book_id查询书名
					$book_id=$row['book_id'];
					$borrow_id=$row['id'];
					
					$sql_tmp="select a.id,a.name,b.bookinfo_id from bookinfo a left join (select bookinfo_id from book c where id='$book_id') b on a.id=b.bookinfo_id where a.id=b.bookinfo_id  ";
					$result_tmp=mysql_query($sql_tmp) or die(mysql_error());
					$row_tmp = mysql_fetch_array($result_tmp);
					
			?>			
			<div class=bookrec_list_td>
				<div class='bianhao'>序号：<?php echo $i?></div>
				<div class='list_td_con'>书名——<?php echo $row_tmp['name']?></div>
				<div class='list_td_con'>应还时间——<?php echo $row['returndate']?></div>
				<div class='list_td_con'>可续借数——<?php echo $remain_times ?></div>
				<form action="renew.php" method="post">
					<input type="hidden" value="<?php echo $row['id']?>"  name="borrow_id" >
					<input type="hidden" value="<?php echo $row['returndate']?>"  name="returndate" >
					<input type="hidden" value="<?php echo $bookrenewdays?>"  name="bookrenewdays" >
					 <button  type="submit"  <?php if($remain_times<1) echo 'disabled'?> >续借</button>
				</form>					
			<?php 
				if(in_array($islogin,array(2,3,6,7))){
					//是2367权限的用户
			?>
					
						<form action="return_zizhu_post.php" method="post">
							<input type="hidden" value="<?php echo $book_id?>"  name="book_id" >
							<input type="hidden" value="<?php echo $borrow_id?>"  name="borrow_id" >
							<input type="hidden" value="<?php echo $row['returndate']?>"  name="returndate" >
							 <button  type="submit"  >还书</button>
						</form>
					
			<?php
				}
			?>

			</div>
		  <?php 
		  $i+=1;
		  }?>
		</div>
		
	</div>


	<div class="pagination">

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
