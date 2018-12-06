<?php
error_reporting(0);
date_default_timezone_set('ETC/GMT-8');
include_once('inc/common_function.php');
session_start();
$islogin=$_SESSION['islogin'];
$name=$_SESSION['name'];//用户姓名
$username=$_SESSION['username'];//用户学号
$u_id=$_SESSION['u_id'];
$_SESSION['F_URL'] ='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
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
   <span id="example"></span>
  <h1 id="myarrow_zi" class="mui-title" style="color: #fff;">BbookK</h1>
</header>