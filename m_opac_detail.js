﻿	
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
	
	mui('#opac_jIconfont1')[0].addEventListener('tap', function(e) {
		history.back(-1);
		//window.open('m_index.php','_self');
	})
