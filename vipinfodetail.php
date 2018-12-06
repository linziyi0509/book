<?php 
include_once("header.php");		

$vipid=$_GET["vipid"];
$name=$_GET["name"];
include_once('inc/conn.php');
//$sql="select * from bookinfo where id='$vid'";
$sql= "select * from vipinfodetail where vipid='$vipid' order by createtime asc";
echo $sql;
$result=mysql_query($sql) or die(mysql_error());
$list = [];
while($res = mysql_fetch_assoc($result)){
	$list[] = $res;
}
?>
<div class="container-fluid">
	<div class="page-header">
		<h1>用户：<?=$name?>-vip详情：</h1>
	</div>
	<table class="table table-striped table-bordered table-condensed">
		<thead>
          <tr>
            <th width="6%">ID</th>
            <th width="10%">创建时间</th>
			<th width="20%">类型</th>
            <th width="10%">天数</th>
          </tr>
        </thead>
        <tbody id="lists">
		<?php
		if(!empty($list) && is_array($list)){
			foreach($list as $key=>$val){
				echo "<tr><td>".$val['id']."</td><td>".date("Y-m-d H:i:s",$val['createtime'])."</td><td>".$val['typename']."</td><td>".$val['days']."</td></tr>";
			}
		}else{
			echo "<tr><td colspan='4'>暂无数据</td></tr>";
		}
		?>
		</tbody>
	</table>
</div>
<?php 
include_once("footer.php");
?>
  </body>
</html>
