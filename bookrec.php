
<?php 
include_once("header.php");		
include_once('inc/conn.php');
include_once('inc/common_function.php');
if($islogin>0){
	$isbn=$_SESSION['isbn'];



?>

<div class="container-fluid">
	<div class="page-header">
		<h1>荐购平台</h1>
		
	</div>
	
	
	<form class="well form-search" name='form_isbn' action="" method="post"  onsubmit="return test()">

		输入ISBN号码：
		<input type="text" id="f_name" class="input-medium search-query" name="kewords" value="<?php  echo $_POST['kewords']; ?>" 
		onmouseover="selectInputContent(this.id)"/>
		
		<input class="btn" type="submit" value="搜 索"/>
		<br />
	</form>

	<?php 
	if($_POST['kewords'] or !empty($_SESSION['isbn'])){
		//进入catalog如果没有输入isbn则	中间什么都不显示。

		if(!empty($_SESSION['isbn'])){
			$isbn=$_SESSION['isbn'];
		}
		else{
			$isbn= $_POST[kewords];
		}
		if(!empty($_POST['kewords']))
			$isbn= $_POST[kewords];
		//echo $isbn;
		//
		//查询bookrec表，看当前用户是否荐购过这本书。
		$sql="SELECT * FROM bookinfo a,bookrec b WHERE a.isbn='$isbn' and b.rec_id='$u_id' and b.bookinfo_id=a.id";
		$result=mysql_query($sql); 
		if($row = mysql_fetch_array($result)){
			//当前用户荐购过
			$sign_rec=='cannot';
			$message='你已经荐购过此书，请不要重复荐购。';
			//还需把此书的书目信息加载过来
			$sql="SELECT * FROM bookinfo WHERE isbn='$isbn'";
			$result=mysql_query($sql); 
			$row = mysql_fetch_array($result);
			$book_name=$row['name'];
			$book_publisher=$row['publisher'];
			$book_pubyear=$row['pubyear'];
			$book_price=$row['price'];
			$book_pages=$row['pages'];
			$book_author=$row['author'];
			$book_category=$row['category'];
			$book_abstract=$row['b_abstract'];//中国图书网的简介
			$book_abstract2=$row['bookinfo'];
			$book_authorinfo=$row['b_authorinfo'];
			$book_catalog=$row['b_catalog'];
			if(!empty($row['img'])){
					$img=$row['img'];
				}
			//上面代码有冗余，先不解决。

		}
		else{
			$sql="SELECT * FROM bookinfo WHERE isbn='$isbn'";
			$result=mysql_query($sql); 
			if($row = mysql_fetch_array($result)){
			//在bookinfo中找到了，则不用到其他网站爬取数据，直接把现有数据列出来。
				$bookinfo_have='y';//标记，表明bookinfo表中有此书。
				$message='本地图书总库中有此书信息';

				$bookinfo_id=$row['id'];
				if(!empty($row['img'])){
					$img=$row['img'];
				}
				//循环显示已有图书的索书号以及图书编号
				$sql= "select * from book where bookinfo_id='$bookinfo_id'";
				$result_tmp=mysql_query($sql) or die(mysql_error());

				if (mysql_num_rows($result_tmp) < 1) {
					//echo '记录集为空'
					//空记录集合，设置标记，说明在bookinfo中有，但book中没有，可以荐购。
					$book_have='n';
					$message.="，但实验室中还没有采购，您可以进行荐购操作。";
					$sign_rec='can';//标记——是否可以进行荐购操作
				}
				else{
					$book_have='y';	
					$message.="，且实验室中已有此书，暂不接受已有图书的荐购。";
					while($row_tmp = mysql_fetch_array($result_tmp)){
						$message.="<br />索书号：".$row_tmp['findcode']."，图书编号：".$row_tmp['bookcode'];
					}
					
				}	
				//把库中要用到的字段值，都赋值给变量
				$book_name=$row['name'];
				$book_publisher=$row['publisher'];
				$book_pubyear=$row['pubyear'];
				$book_price=$row['price'];
				$book_pages=$row['pages'];
				$book_author=$row['author'];
				$book_category=$row['category'];
				$book_abstract=$row['b_abstract'];//中国图书网的简介
				$book_abstract2=$row['bookinfo'];
				$book_authorinfo=$row['b_authorinfo'];
				$book_catalog=$row['b_catalog'];
			}
			else{
				if($isbn==''){
					echo "isbn空的";
					
				}else{
					//	echo "在bookinfo中没找到。";
					$bookinfo_have='n';//标记，表明bookinfo表中没有此书。
				
					$img='bookcover/'.$isbn.'.jpg';
					$pa=getdata($isbn);

					$book_name=$pa[0];
					$book_publisher=$pa[3];
					$book_pubyear=$pa[4];
					$book_price=$pa[5];
					$book_pages=$pa[6];
					$book_author=$pa[1];
					$book_category=$pa[11];
					$book_abstract=$pa[7];//中国图书网的简介
					$book_abstract2=$pa[12];
					$book_authorinfo=$pa[8];
					$book_catalog=$pa[9];
					$book_cover=$pa[2];
					$book_source=$pa[10];

					if(!empty($book_name) and !empty($book_author)){	
						$message='本地图书总库中没有此书，在'.$book_source.'抓取到了以下数据。';
						if(!empty($book_cover)){
							$img=$book_cover;
						}
						//这里开始进行入库bookinfo的自动操作。
						$sql="insert into bookinfo (name,isbn,img,publisher,pubyear,author,category,price,pages,num,restnum,b_abstract,bookinfo,b_authorinfo,b_catalog,ruku_id,ruku_date) values ('$book_name','$isbn','$img','$book_publisher','$book_pubyear','$book_author','$book_category','$book_price','$book_pages',0,0,'$book_abstract','$book_abstract2','$book_authorinfo','$book_catalog','$u_id',now())";
						mysql_query($sql);
							//插入后得到新插入记录的id
							$query="SELECT LAST_INSERT_ID()";
							$result=mysql_query($query);
							$rows=mysql_fetch_row($result);
							
							$bookinfo_id= $rows[0];
						$sign_rec='can';
					}
					else{
						$message='本地图书总库及国图、豆瓣和中国图书网都没有找到此书。<br>请确认ISBN是否正确。暂不接受无法查询到书目信息的图书荐购。';
						$sign_not_show='y';
					}
					
				}
			}
			$_SESSION['isbn']=$_POST['kewords'];
		}
	?>

	<legend><?php echo $message?></legend>


	<?php if($sign_not_show!='y'){?>
		<form class="form-horizontal" action="bookrec_post.php" method="post" name="myform" >
			<input type="hidden" name="bookinfo_id"  value= "<?php echo $bookinfo_id;?>">
			<input type="hidden" name="isbn"  value= "<?php echo $isbn;?>">
			<fieldset>

				<div class="controls">
					<div class="nodata"></div>
					<img  src="<?php echo $img; ?>" width="150"/>
					<p>书名：<?php echo $book_name ." ". $book_author ?></p>
					<p>出版社：<?php echo $book_publisher ?> <?php echo $book_pubyear ?></p>
					<p>分类号：<?php echo $book_category ?></p>
					<p>价格：<?php echo $book_price ."元 ".$book_pages."页"?></p>
				</div>

				
				<?php

				if($sign_rec=='can'){
					//查询bookrec表，看此书一共被推荐了几次。
					$sum_rec=0;
					$sql="select * from bookrec where bookinfo_id='$bookinfo_id'";
					$result_tmp=mysql_query($sql) or die(mysql_error());
					$sum_rec=mysql_num_rows($result_tmp);
					if($sum_rec>0){
						$str_sum="本书已经被推荐了".$sum_rec."次!";}
					else{$str_sum='';}
					?>
					<div class="control-group">
						<label for="input01" class="control-label">荐购原因：</label>
						<div class="controls">
								<textarea  name="rec_reason"  rows="6" cols="32" ></textarea>
						</div>
					</div>
					<p class="controls"><?php echo $str_sum;?></p>
					<div class="form-actions">
						<input class="btn btn-primary" type="submit" name="submit" value="荐购" />
					</div>
					<?php }
					?>
			</fieldset>
		</form>		

		<?php		
		}
}

?>



</div>



<?php 
}else{

	echo "<br><br><br><br>请先登录，再完成相关操作！";
}
include_once("footer.php");
?>

  </body>
</html>
<script language="javascript"> 
	function test() 
	{ 
		if(document.form_isbn.kewords.value.length!=13) 
		{ 
		alert("需输入13位的ISBN号码！"); 
		document.form_isbn.kewords.focus(); 
		return false; 
		}
	} 
</script>
