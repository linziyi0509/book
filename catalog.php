<?php 
include_once("header.php");		
include_once('inc/conn.php');
include_once('inc/common_function.php');
if($islogin!=3 and $islogin!=9){
	echo '<script>';
	echo "location='index.php'";
	echo '</script>';
}
$isbn=$_SESSION['isbn'];
//echo $isbn;

?>

<div class="container-fluid">
	<div class="page-header">
		<h1>编目模块</h1>
		<h1><a href="catalog_output.php">导出数据</a></h1>
	</div>
	
	
<form class="well form-search" name='form_isbn' action="" method="post"  onsubmit="return test()">

	输入ISBN号码：
	<input type="text" id="f_name" class="input-medium search-query" name="kewords" value="<?php  echo $_POST['kewords']; ?>" 
	onmouseover="selectInputContent(this.id)"/>
	
	<input class="btn" type="submit" value="搜 索"/>
	
<br />
</form>

<?php 
if($_POST['kewords'] or !empty($_SESSION['isbn']))
	{//进入catalog如果没有输入isbn则	中间什么都不显示。

		$sign_have='y';//标记，标记库中有此书信息。

	if(!empty($_SESSION['isbn'])){$isbn=$_SESSION['isbn'];}
	else{$isbn= $_POST[kewords];}
	if(!empty($_POST['kewords']))$isbn= $_POST[kewords];
	//echo $isbn;
	
	$sql="SELECT * FROM bookinfo WHERE isbn='$isbn'";
	$result=mysql_query($sql); 
	if($row = mysql_fetch_array($result)){//在bookinfo中找到了，则不用到其他网站爬取数据，直接把现有数据列出来。
		
		$bookinfo_id=$row['id'];
		if(!empty($row['img'])){
			$img=$row['img'];
		}else{$img='bookcover/'.$isbn.'.jpg';}

		
		
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
		
		$message='本地库中有此书：'.$isbn;
	}
	else{
		if($isbn==''){
			echo "isbn空的";
			
		}else{
			//	echo "在bookinfo中没找到。";
			
			
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

						$query="SELECT LAST_INSERT_ID()";
						$result=mysql_query($query);
						$rows=mysql_fetch_row($result);
						$bookinfo_id= $rows[0];
					}
				else{
					$message='本地图书总库及国图、豆瓣和中国图书网都没有找到此书。<br>请确认ISBN是否正确。如无误需要进行原编操作。<br>系统目前不提供原编入口！';
					$sign_have='n';//标记，表明bookinfo表中没有此书。
				}
					
				}

			
		}

		//先自动在book中寻找比对各个架位的图书数量，以及小架位的数量，然后自动找出一个大架位和小架位号码，以便生成索书号和图书编号。
		//共有5个大架位，每个架位有4个小架位
		//思路是，先找5个架位中图书数量最少的架位，然后再找此架位中图书数量最多且少于20本书的小架位。
		//根据l_pos分组，找出l_num中的值
		$code=auto_code();
		$code2=auto_code_102($book_category);

		
	$_SESSION['isbn']=$_POST['kewords'];

?>

	<legend><?php echo $message?></legend>

<?php
	if($sign_have=='y'){
?>		
		
		<form class="form-horizontal" action="catalog_post.php" method="post" name="myform" >
			<input type="hidden" name="bookinfo_id"  value= "<?php echo $bookinfo_id;?>">
			<input type="hidden" name="isbn"  value= "<?php echo $isbn;?>">
			<input type="hidden" name="img"  value= "<?php echo $img;?>">
			<input type="hidden" name="sumcat"  value= "<?php echo $code2[0];?>">
			<input type="hidden" name="sumall"  value= "<?php echo $code2[1];?>">
			<input type="hidden" name="l_pos"  value= "<?php echo $code[2];?>">
			<input type="hidden" name="l_num"  value= "<?php echo $code[3];?>">
			<input type="hidden" name="s_pos"  value= "<?php echo $code[4];?>">
			<input type="hidden" name="s_num"  value= "<?php echo $code[5];?>">
			<input type="hidden" name="donor_id" id="donor_id" value= "">
			<fieldset>
			

			

				
			<div class="control-group"> 
				<label for="input01" class="control-label">书封</label>：
					
					<br /><img src="<?php echo $img; ?>" width="150"/> <br />  
					<div class="controls">
				<iframe src="catalog_cover_upload.php" width="300" height="120" frameborder="0"></iframe>
				</div>
			</div>
				
				<div class="control-group">
					<label for="input01" class="control-label">书名：</label>
					<div class="controls">
						<input type="text"  value="<?php echo $book_name ?>" id="input01" class="input-xlarge" name="name" >
					</div>
				</div>

				<div class="control-group">
					<label for="input01" class="control-label">出版社：</label>
					<div class="controls">
						<input type="text"  value="<?php echo $book_publisher ?>" id="input01" class="input-xlarge" name="publisher" >
					</div>
				</div>

				<div class="control-group">
					<label for="input01" class="control-label">出版年：</label>
					<div class="controls">
						<input type="text"  value="<?php echo $book_pubyear ?>" id="input01" class="input-xlarge" name="pubyear" >
					</div>
				</div>

				<div class="control-group">
					<label for="input01" class="control-label">作者：</label>
					<div class="controls">
						<input type="text"  value="<?php echo $book_author ?>" id="input01" class="input-xlarge" name="author" >
					</div>
				</div>

				<div class="control-group">
					<label for="input01" class="control-label">分类号：</label>
					<div class="controls">
						<input type="text"  value="<?php echo $book_category ?>" id="input01" class="input-xlarge" name="category" >
					</div>
				</div>

				<div class="control-group">
					<label for="input01" class="control-label">价格：</label>
					<div class="controls">
						<input type="text"  value="<?php echo $book_price ?>" id="input01" class="input-xlarge" name="price" >
					</div>
				</div>

				<div class="control-group">
					<label for="input01" class="control-label">页码</label>
					<div class="controls">
						<input type="text"  value="<?php echo $book_pages ?>" id="input01" class="input-xlarge" name="pages" >
					</div>
				</div>

				
				<div class="control-group">
					<label for="input01" class="control-label">图书简介(FROM 中国图书网)</label>
						<div class="controls">
							<textarea  name="b_abstract"  rows="6" cols="32" ><?php echo $book_abstract ?></textarea>
						</div>
				</div>

				<div class="control-group">
					<label for="input01" class="control-label">图书简介：（FROM 国图opac）</label>
						<div class="controls">
							<textarea  name="bookinfo"  rows="6" cols="32" ><?php echo $book_abstract2 ?></textarea>
						</div>
				</div>

				<div class="control-group">
					<label for="input01" class="control-label">作者简介：</label>
					<div class="controls">
							<textarea  name="b_authorinfo"  rows="6" cols="32" ><?php echo $book_authorinfo ?></textarea>
					</div>
				</div>

				<div class="control-group">
					<label for="input01" class="control-label">目录：</label>
					<div class="controls">
						<textarea  name="b_catalog"  rows="6" cols="32" ><?php echo $book_catalog ?></textarea>
					</div>

				</div>
				
				<?php
				//循环显示已有图书的索书号以及图书编号
					$sql= "select * from book where bookinfo_id='$bookinfo_id'";
					$result_tmp=mysql_query($sql) or die(mysql_error());
					while($row_tmp = mysql_fetch_array($result_tmp)){
						?>
						<div class="control-group">
							<label for="input01" class="control-label">馆藏地：<?php echo $row_tmp['guancangdi']?></label>
							<label for="input01" class="control-label">索书号：<?php echo $row_tmp['findcode']?></label>
							<label for="input01" class="control-label">图书编号：<?php echo $row_tmp['bookcode']?></label>
						</div>
				<?php		
					}
				?>
				
				<div style="font-size:18px;" >
			        <input type="radio" name="test" onclick="ch('yes')">102实习室
			        <input type="radio" name="test" onclick="ch('no')">123实验室
			        <input type="hidden" id="3" name="guancangdi" value="">
			    </div>    
			    <div>
			        <div id="0" style="display: none;">
						
						<div class="control-group">
								<label for="input01"  class="control-label">新入库索书号：</label>
								<div class="controls">
									<input type="text" readonly value="<?php echo $code2[0]?>"  class="input-xlarge" name="findcode102" >
								</div>
							</div>	
							
							<div class="control-group">
								<label for="input01"  class="control-label">新入库图书编号：</label>
								<div class="controls">
									<input type="text" readonly value="<?php echo $code2[1]?>"  class="input-xlarge" name="bookcode102" >
								</div>
						</div>
			        </div>
			        <div id="1" style="display: none;">
							<div class="control-group">
								<label for="input01"  class="control-label">新入库索书号：</label>
								<div class="controls">
									<input type="text" readonly value="<?php echo $code[0]?>"  class="input-xlarge" name="findcode" >
								</div>
							</div>	
							
							<div class="control-group">
								<label for="input01"  class="control-label">新入库图书编号：</label>
								<div class="controls">
									<input type="text" readonly value="<?php echo $code[1]?>"  class="input-xlarge" name="bookcode" >
								</div>
							</div>
							
							<div class="control-group">
								<label for="input01" class="control-label">捐赠者学号：</label>
								<div class="controls">
									<input type="text"  value=""  class="input-xlarge" name="donor_xuehao" id="donor_xuehao" >
								</div>
							</div>


							<div class="control-group">
								<label for="input01" class="control-label">捐赠者姓名：</label>
								<div class="controls">
									<input type="text"  value="" placeholder="" class="input-xlarge" name="donor_name" id="donor_name" >
								</div>
							</div>

			        </div>
			    </div>	


				

				<div class="form-actions">
					<input class="btn btn-primary" type="submit" name="submit1" value="更新" />
					<input id="2" style="display: none;" class="btn btn-primary" type="submit" name="submit2" value="入库">

				</div>
	
			</fieldset>
		</form>		

<?php		
}
}

?>

<!-- <legend>分割线</legend>
 -->
</div><!--/span-->



<?php 

include_once("footer.php");
?>
<script>
    function ch(tag){
    	var a=document.getElementById("0");
        var b=document.getElementById("1");
        var c=document.getElementById("2");
        var d=document.getElementById("3");

    	var cat="<?php echo $book_category;?>";
        if(cat==''){
        	alert('没有填写分类号!填写分类号并且更新后，再进行入库操作!');
        	c.style.display="none";
        }
        else{
        	if(tag=='yes'){
            a.style.display="block";
            b.style.display="none";
            c.style.display="inline";
            d.value='102';
       		}
	        else{
	            a.style.display="none";
	            b.style.display="block";
	            c.style.display="inline";
	            d.value='123';
	        }
        }



    }
</script>

<script type="text/javascript">
$('#donor_xuehao').on('keyup', function() {
	//setTimeout(1000);
    //alert($('#text').val());
	var xuehao = $(this).val();
	var data = "action=getlink&xuehao="+xuehao;
	//alert(data);
	$.getJSON("catalog_ajax.php", data, function(response){ 
	$('#donor_name').val(response.name);
	$('#donor_name').attr("placeholder",response.tishi);
	$('#donor_id').attr("value",response.donor_id);
	//$('#donor_name').attr("placeholder","胡歌");
	//$('#box').val(data);

       });  
	
	
});



</script>
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
  </body>
</html>
