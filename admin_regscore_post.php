<?php
include_once("header.php");	
if($islogin!=5 and $islogin!=9){
	echo '<script>';
	echo "location='index.php'";
	echo '</script>';
}

include_once('inc/conn.php');
$num=$_POST['xuhao']-1;//进行登分的人数
$xuhao=1;
//根据传递过来的$_POST[$input_id] 和 $_POST[$input_name] 追加score表，需进行循环得到所有要替换的值。
while($num>0){
	$input_name="in".$xuhao;
	$input_id="id".$xuhao;
	$user_id=$_POST[$input_id];
	$score=$_POST[$input_name];
	$sql="insert into score (user_id,score,operator,op_id,time,remark) values ('$user_id','$score','$name','$u_id',now(),'主管录入')";
	$result=mysql_query($sql) or die(mysql_error());
	//没循环一次插入一条新记录
	$xuhao++;
	$num--;
}

//所有人员积分处理完成后，为了友好，还是给出一定的反馈吧
//这里准备把本次（当天）操作的录入积分信息，列表显示出来。


?>

<div class="container-fluid">
	<div class="page-header">
		<h1><?php echo $name ?>当天录入的积分为：</h1>
	</div>
	<table class="table table-striped table-bordered table-condensed">
		<thead>
          <tr>
            <th width="6%">姓名</th>
            <th width="6%">时间</th>
            <th width="10%">类别</th>
            <th width="10%">分数</th>
            <th width="10%">操作员</th>
         
          </tr>
        </thead>
        <tbody>
			<?php
			$sql="SELECT a.user_id,a.time,a.remark,a.operator,a.op_id,a.score,b.id,b.name FROM score a, user b where a.op_id='$u_id' and DATEDIFF(now(),a.time)=0 and a.user_id=b.id";
			$result=mysql_query($sql) or die(mysql_error());
			while($row = mysql_fetch_array($result))
				{
			?>
						
					  <tr>
						<td><?php echo $row['name']?></td>
						<td><?php echo $row['time']?></td>
						<td><?php echo $row['remark']?></td>
						<td><?php echo $row['score']?></td>
						<td><?php echo $row['operator']?></td>
					  </tr>
					  
				  <?php 
				  }?>


		</tbody>
	</table>

</div><!--/span-->



<?php 
include_once("footer.php");
?>


  </body>
</html>

