<?php
include_once('inc/conn.php');	


$sql="DELETE FROM bookinfo WHERE isbn =  '9787111540526'";
mysql_query($sql);

echo "<script>location.href='test_ip_bookrec.php';</script>";
?>



