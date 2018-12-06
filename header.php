<?php  
error_reporting(0);
include_once('inc/common_function.php');
require_once 'inc/Mobile_Detect.php';
$detect = new Mobile_Detect;

date_default_timezone_set('ETC/GMT-8');
session_start();
$islogin=$_SESSION['islogin'];
$name=$_SESSION['name'];//用户姓名
$username=$_SESSION['username'];//用户学号
$u_id=$_SESSION['u_id'];
$vip=$_SESSION['vip'];
$_SESSION['F_URL'] ='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];

if ( !$detect->isMobile() ) {

//if (!isMobile()) {
    //电脑端的内容
    $_SESSION['mobile']='n';
?> 
     <html>
        <head>
          <meta charset="utf-8">
          <title>BbookK V3</title>
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <meta name="description" content="">
          <meta name="author" content="">

          <!-- Le styles -->
          <link href="css/bootstrap.css" rel="stylesheet">
          <link href="css/new-mobile.css" rel="stylesheet">
            <!-- <link href="css/mui.min.css" rel="stylesheet" type="text/css" /> -->
            <!-- <link href="css/app.css" rel="stylesheet" type="text/css" /> -->
          <link rel="Stylesheet" type="text/css" href="css/loginDialog.css" />
          <link href="css/mycss.css" rel="stylesheet" type="text/css" />
          <style type="text/css">
            body {
      /*        padding-top: 60px;
              padding-bottom: 40px;*/
            }
            .sidebar-nav {
              padding: 9px 0;
            }
          </style>
          <link href="css/bootstrap-responsive.css" rel="stylesheet">
          <script src="js/jquery-3.2.1.min.js"></script>
      <script type="text/javascript" src="js/common.js"></script>

          <script src="js/bootstrap.min.js"></script>

      	<script type="text/javascript">
      		$(function () {
      		//alert("ab");
      		// showlogin('<?php echo $url?>');
      		showlogin();
      		 });
      	</script>
        </head>

        <body>
        

          <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
              <div class="container-fluid">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </a>
                <a class="brand" href="#">BbookK V3</a>
                <div class="nav-collapse collapse">
                  <p class="navbar-text pull-right">
                    当前用户: <b href="#" class="navbar-link"><?php echo $name;?></b>
                  </p>
                  <ul class="nav">
      			<?php
      				if(!isset($u_id)){
      			?>
                    <li><a href="#" id="example">登录</a></li>

      			<?php
      				}
      			?>
      			   
              <li><a href="index.php">首页</a></li>
      			  <li><a href="opac.php">OPAC</a></li>
      			  <li><a href="bookrec.php">荐购</a></li>
      			 <?php
      				if($islogin<9){//除了超级用户外，都显示这些基本菜单。
      			 ?>

      			  <li><a href="attend.php">签到</a></li>
      			  <li><a href="yuyue_list.php">座位预约</a></li>
      					<?php
      					if($islogin>0){//判断只要是登录用户，且不是管理员的登录用户
      						?>
      						<li><a href="my.php">我的</a></li>
      						<?php
      					}
      					?>

      			  <li><a href="help.php">预约说明</a></li>
      			  <li><a href="vipinfo.php">vip信息</a></li>
      			  <li><a href="rankinfo.php">排行相关</a></li>
      			<?php
      				}
      				if($islogin==3 or $islogin==9){//对图书管理员和超级用户增加新的借还书菜单。
      				?>
                    <li><a href="book_manager.php">图书管理</a></li>
      			<?php
      				}
      				if($islogin==2 or $islogin==6 or $islogin==7){//自助借还用户，增加借书、还书的菜单
      			  ?>
                    <li><a href="borrow.php">借书</a></li>
      			  
      			<?php
      				}
      				
      				
      				if($islogin==9){
      			?>
                    <li><a href="admin_output.php">导出记录</a></li>
      			  <?php
      				}
					if(isset($vip) && $vip == 1){
					?>
						<li><a href="action/action.abandonvip.php">放弃vip</a></li>
					<?php
					}
					if(isset($u_id)){
					?>
                    <li><a href="action/action.loginOut.php">退出</a></li>
      			  <?php
      				}
      				?>
                  </ul>
      			
                </div>
              </div>
            </div>
          </div>
<?php
  }
else{
  //移动端的内容
  $_SESSION['mobile']='y';

?>
      <html>
  <head>
    <meta charset="utf-8">
    <title>BbookK V3</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles 
    <link href="css/bootstrap.css" rel="stylesheet">-->
    <link href="css/new-mobile.css" rel="stylesheet">
    <link rel="Stylesheet" type="text/css" href="css/loginDialog.css" />
  <link href="css/mui.min.css" rel="stylesheet" type="text/css" />
  <link href="css/app.css" rel="stylesheet" type="text/css" />
  <link href="css/icons-extra.css" rel="stylesheet" type="text/css" />
  <link href="css/vjpage.css" rel="stylesheet" type="text/css" />
  <link href="css/config.css" rel="stylesheet" type="text/css" />
  <link href="css/mycss.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
      body {
/*        padding-top: 60px;
        padding-bottom: 40px;*/
      }
      .sidebar-nav {
        padding: 9px 0;
      }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <script src="js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="js/common.js"></script>

    <script src="js/bootstrap.min.js"></script>

  <script type="text/javascript">
    $(function () {
    //alert("ab");
    // showlogin('<?php echo $url?>');
    showlogin();
     });
  </script>
  </head>

  <body>

<header id="myarrow_out" class="mui-bar mui-bar-nav" style="background-color:#1287f5;">
  <span id="myarrow" class="mui-icon mui-icon-arrowleft" style="color: #fff;"></span>
   <!-- <span id="example"></span> -->
   <span id="example"></span>
  <h1 id="myarrow_zi" class="mui-title" style="color: #fff;">BbookK</h1>
</header>

<?php
  }
?>