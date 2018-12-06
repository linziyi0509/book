<?php 
include_once("header.php");
include_once('inc/conn.php');
date_default_timezone_set('ETC/GMT-8');

if($islogin!=5 and $islogin!=9){
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
			<h1>登记分数</h1>
		</div>
		
		<form action="admin_regscore_post.php" method="post">
			<table class="table table-striped table-bordered table-condensed">
				<thead>
				  <tr>
					<th width="6%">姓名</th>
					<th width="10%">积分</th>
				  </tr>
				</thead>
				<tbody>
					<?php
					$sql="SELECT *  FROM user where quanxian='3' or quanxian ='4' or quanxian ='5' ";
					$result=mysql_query($sql) or die(mysql_error());
					$xuhao=1;
					while($row = mysql_fetch_array($result)){
						$input_name="in".$xuhao;
						$input_id="id".$xuhao;
					?>	
					  <tr>
						<td><?php echo $row['name']?></td>
						<td>
							<input type="text" value=""  name="<?php echo $input_name?>" >
							<input type="hidden" value="<?php echo $row['id']?>"  name="<?php echo $input_id?>" >
						</td>
					  </tr>
					<?php
						$xuhao++;
					}
					?>
					<input type="hidden" value="<?php echo $xuhao?>"  name="xuhao" >
					
				</tbody>
			</table>
			

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
