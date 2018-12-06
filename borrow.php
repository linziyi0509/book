<?php 
include_once("header.php");
include_once('inc/conn.php');
date_default_timezone_set('ETC/GMT-8');

if($islogin!=3 and $islogin!=9 and $islogin!=2 and $islogin!=6 and $islogin!=7){
	echo '<script>';
	echo "location='index.php'";
	echo '</script>';
}
?>
<script language="javascript">
function changeEnter(){
	if(event.keyCode==13){event.keyCode=9;}
}
</script>
<body onload="document.all.username.select()">
 <div class="container-fluid" >
    <div class="row-fluid">
      <div class="span10"> 

		
        <div class="page-header">
			<h1>借书操作</h1>
		</div>
		
		<form action="borrow_post.php" method="post">
			<?php 
				if($islogin==3 or $islogin==9){//quanxian3 9 可以显示用户编号的界面。
			?>	
					用户编号：<br />
					<input class="input-medium search-query" type="text" value="" placeholder="不输入则是你自己" name="username"  onkeydown="changeEnter()" />
					<br />
			<?php
				}
			?>
			图书编号：<br />
			<input class="input-medium search-query" type="text" value=""  name="bookcode" >
			<br />
			 <button  type="submit">确认</button>
		</form>  

    </div><!--/span-->


      </div><!--/row-->
</div>

<?php 
include_once("footer.php");
?>


  </body>
</html>
