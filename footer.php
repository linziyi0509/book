      <footer id="footer_p">
        <p>&copy; M网络工作室 2017-2018</p>
      </footer>

<script type="text/javascript" src="js/mui.min.js"></script>
<script type="text/javascript" src="js/navigation.js"></script>

<script type="text/javascript">
	$(function ($) {
		url="<?php echo $_SESSION['F_URL']?>";
		// qr_url="<?php echo $_SESSION['QR_URL']?>";

		// if(qr_url!="" ){
		// 	url=qr_url;
		// 	<?php $_SESSION['QR_URL']='';?>;
		// }
		
		qr_url="<?php echo $_SESSION['QR_URL']?>";

		console.log(url);
		islogin='<?php echo $islogin?>';
		if(islogin<1){
			$("#example").click();
		}

	});

		mui.init({
		gestureConfig:{
			tap: true,
			doubletap: true,
			longtap: true,
			hold: true,
			drag: true,
			swipe: true,
			release: true
		}
	});
	
	mui('#myarrow')[0].addEventListener('tap', function(e) {
		history.back(-1);
	})
</script>
