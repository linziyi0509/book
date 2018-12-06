<?php
//error_reporting(0);
date_default_timezone_set('ETC/GMT-8');
session_start();
$islogin=$_SESSION['islogin'];
$name=$_SESSION['name'];
$username=$_SESSION['username'];
$u_id=$_SESSION['u_id'];
$isbn=$_SESSION['isbn'];

if($islogin!=3 and $islogin!=9){
	echo '<script>';
	echo "location='index.php'";
	echo '</script>';
}

include_once('inc/conn.php');	


	//只是更新基本数据，并不入库处理，索书号、图书编号等并不入库。
	$bookname=$_POST["name"];
	$bookinfo_id=$_POST["bookinfo_id"];
	$img=$_POST["img"];
	$publisher=$_POST["publisher"];
	$pubyear=$_POST["pubyear"];
	$author=$_POST["author"];
	$category=$_POST["category"];
	$price=$_POST["price"];
	$pages=$_POST["pages"];
	$b_abstract=$_POST["b_abstract"];
	$bookinfo=$_POST["bookinfo"];
	$b_authorinfo=$_POST["b_authorinfo"];
	$b_catalog=$_POST["b_catalog"];
	//$b_catalog=str_replace('/\n|\r\n/g',"<br>",$b_catalog);


	//对索书号、图书编号、副本量进行操作。
	//说明新的图书副本入库开始
    //经过测试，只有 $_POST["isbn"] 这个才能得到准确的isbn;
	$findcode=$_POST["findcode"];
	$bookcode=$_POST["bookcode"];
	$findcode102=$_POST["findcode102"];
	$bookcode102=$_POST["bookcode102"];
	$isbn=$_POST["isbn"];
	$l_pos=$_POST["l_pos"];
	$l_num=$_POST["l_num"];
	$s_pos=$_POST["s_pos"];
	$s_num=$_POST["s_num"];
	
	//接收捐赠者的信息
	$donor_id=$_POST["donor_id"];
	$donor_name=$_POST["donor_name"];

	//读取馆藏地信息，根据接收的馆藏地信息，进行后面更新和入库时，对相应字段进行调整。
	$guancangdi=substr($_POST['guancangdi'],0,3);
	// echo "<br><br><br><br><br>".$guancangdi;
	// exit;


if(!empty($_POST['submit1'])) {//bookinfo库中已由 更新操作
	
	$sql="update bookinfo set name='$bookname',img='$img',publisher='$publisher',pubyear='$pubyear',author='$author',category='$category',price='$price',pages='$pages',b_abstract='$b_abstract',bookinfo='$bookinfo',b_authorinfo='$b_authorinfo',b_catalog='$b_catalog',edit_id='$u_id',edit_date=now() where id='$bookinfo_id'";

	mysql_query($sql);
	if(mysql_affected_rows()){
		echo "<script>alert('更新成功!');location.href='catalog.php';</script>";
	}else{
		echo "<script>alert('更新失败!');location.href='catalog.php';</script>";
	}

}
elseif(!empty($_POST['submit2'])) {

	if($guancangdi=='123'){
	//bookinfo库中已有，book入库操作
	//根据是否是捐赠图书入库，进行判断
	if(!empty($l_pos)){

		$sql="select * from book where bookcode='$bookcode'";
		$result=mysql_query($sql);
		if($row = mysql_fetch_array($result)){
			echo "<script>alert('入库123存在冲突,若要完成入库，需要重新搜索此ISBN号，再执行入库操作!');location.href='catalog.php';</script>";
		}
		else{

			if($donor_id==0 and empty($donor_name)){//这个状态是没有捐赠者信息的图书，视为非捐赠图书
				$sql="insert into book (bookinfo_id,findcode,bookcode,l_pos,l_num,s_pos,s_num,saver_id,save_time,guancangdi) values ('$bookinfo_id','$findcode','$bookcode','$l_pos','$l_num','$s_pos','$s_num','$u_id',now(),'$guancangdi')";
				mysql_query($sql);
			}
			else{//肯定就是捐赠的图书了，捐赠的图书，需要多填写3个字段值
				$sql="insert into book (bookinfo_id,findcode,bookcode,l_pos,l_num,s_pos,s_num,saver_id,save_time,donor_id,donor_name,donor_date,guancangdi) values ('$bookinfo_id','$findcode','$bookcode','$l_pos','$l_num','$s_pos','$s_num','$u_id',now(),'$donor_id','$donor_name',now(),'$guancangdi')";
				mysql_query($sql);
				
				//这里准备增加捐赠者的积分
				//加分的前提是 donor_id不为0 即可以在user表中找到这个捐赠者
				if($donor_id!=0){
					//在score表中增加新记录，分值为 $price*10,
					$score=round($price*10);
					
					$sql="insert into score (user_id,score,time,remark,operator,op_id) values('$donor_id','$score',now(),'捐书','$name','$u_id') ";
					$result=mysql_query($sql) or die(mysql_error());
				}
			}			

			//更新副本数量
			//修改bookinfo表中的num值 restnum值
			$sql="update bookinfo set num=num+1,restnum=restnum+1,ruku_id='$u_id',ruku_date=now() where id='$bookinfo_id'";
			mysql_query($sql);
			if(mysql_affected_rows()){
				echo "<script>alert('入库123成功!');location.href='catalog.php';</script>";
			}else{
				echo "<script>alert('入库123失败!');location.href='catalog.php';</script>";
			}

		}

	}
}
	if($guancangdi=='102'){
		//入库为了避免同时加工数据，出现冲突问题。所以先进行冲突检测。
		//检测的方法就是根据目前的新入库图书编号，查询目前book表中是否存在这个编号的图书，如果有则说明冲突。需要重新计算索书号和图书编号。
		

		$sql="select * from book where bookcode='$bookcode102'";
		// echo $sql;
		// exit;
		$result=mysql_query($sql);
		if($row = mysql_fetch_array($result)){
			echo "<script>alert('入库102存在冲突,若要完成入库，需要重新搜索此ISBN号，再执行入库操作!');;location.href='catalog.php';</script>";
		}
		else{

			$sql="insert into book (bookinfo_id,findcode,bookcode,saver_id,save_time,guancangdi) values ('$bookinfo_id','$findcode102','$bookcode102','$u_id',now(),'$guancangdi')";
			mysql_query($sql);
			$sql="update bookinfo set num=num+1,restnum=restnum+1,ruku_id='$u_id',ruku_date=now() where id='$bookinfo_id'";
			mysql_query($sql);
			if(mysql_affected_rows()){
				echo "<script>alert('入库102成功!');location.href='catalog.php';</script>";
			}else{
				echo "<script>alert('入库102失败!');location.href='catalog.php';</script>";
			}
		}
	}
}
?>



