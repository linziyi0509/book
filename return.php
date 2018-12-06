<?php 
include_once("header.php");
include_once('inc/conn.php');
date_default_timezone_set('ETC/GMT-8');

if($islogin!=3 and $islogin!=9){
	echo '<script>';
	echo "location='index.php'";
	echo '</script>';
}
?>

<body onload="document.all.username.select()">
 <div class="container-fluid" >
    <div class="row-fluid">
      <div class="span10"> 

		
        <div class="page-header">
			<h1>还书操作</h1>
		</div>
		
		<form action="return_post.php" method="post">
			
			
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
