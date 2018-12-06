	//使用方法，需要在要出现菜单的地方加入
	//<a href="#" id="example">登录DIY账号窗口示例</a>
	function showlogin(){
	$("body").append("<div id='LoginBox'></div>");
			$("#LoginBox").html("<div class='row1'>登录BbookK<a href='javascript:void(0)' title='关闭窗口' class='close_btn' id='closeBtn'>×</a>        </div>        <div class='row'>            用户名: <span class='inputBox'>                <input type='text' id='txtName' placeholder='学号' />            </span><a href='javascript:void(0)' title='提示' class='warning' id='warn'>*</a>        </div>        <div class='row'>            密　码: <span class='inputBox'>                <input type='password' id='txtPwd' placeholder='默认密码virlib，请修改！' />            </span><a href='javascript:void(0)' title='提示' class='warning' id='warn2'>*</a>        </div> <div class='wait'></div>       <div class='row'>   <div id='logbut'>         <a href='#' id='loginbtn'>登录</a>  </div>      </div>");
		//弹出登录
		$("#example").hover(function () {
			$(this).stop().animate({
				opacity: '0.8'
			}, 600);
		}, function () {
			$(this).stop().animate({
				opacity: '1'
			}, 1000);
		}).on('click', function () {
			$("body").append("<div id='mask'></div>");
			
			$("#mask").addClass("mask").fadeIn("slow");
			$("#LoginBox").fadeIn("slow");
		});
		//
		//按钮的透明度
		$("#loginbtn").hover(function () {
			$(this).stop().animate({
				opacity: '1'
			}, 600);
		}, function () {
			$(this).stop().animate({
				opacity: '0.8'
			}, 1000);
		});
		//文本框不允许为空---按钮触发
		$("#loginbtn").on('click', function () {
			var txtName = $("#txtName").val();
			var txtPwd = $("#txtPwd").val();
			if (txtName == "" || txtName == undefined || txtName == null) {
				if (txtPwd == "" || txtPwd == undefined || txtPwd == null) {
					$(".warning").css({ display: 'block' });
				}
				else {
					$("#warn").css({ display: 'block' });
					$("#warn2").css({ display: 'none' });
				}
			}
			else {
				if (txtPwd == "" || txtPwd == undefined || txtPwd == null) {
					$("#warn").css({ display: 'none' });
					$(".warn2").css({ display: 'block' });
				}
				else {
					$(".warning").css({ display: 'none' });
					//这里准备加入登录的判断，需要getjson
					$(".wait").show().html("<img src='img/loading.gif'/>");
						var data = "action=getlink&uname="+txtName+"&pwd="+txtPwd;
						$.getJSON("login_ajax.php", data, function(response){

							rstr= response.rstr;
							console.log(txtName,rstr);
							if(rstr==1){
								$(".close_btn").trigger("click");
								console.log(qr_url);
								location.href=qr_url;
							}else{
								$(".wait").show().html("登录失败");
							}
						});
					
				}
			}
		});
		//文本框不允许为空---单个文本触发
		$("#txtName").on('blur', function () {
			var txtName = $("#txtName").val();
			if (txtName == "" || txtName == undefined || txtName == null) {
				$("#warn").css({ display: 'block' });
			}
			else {
				$("#warn").css({ display: 'none' });
			}
		});
		$("#txtName").on('focus', function () {
			$("#warn").css({ display: 'none' });
		});
		//
		$("#txtPwd").on('blur', function () {
			var txtName = $("#txtPwd").val();
			if (txtName == "" || txtName == undefined || txtName == null) {
				$("#warn2").css({ display: 'block' });
			}
			else {
				$("#warn2").css({ display: 'none' });
			}
		});
		$("#txtPwd").on('focus', function () {
			$("#warn2").css({ display: 'none' });
		});
		$("#txtPwd").on('keyup',function () {  
                if (event.keyCode == 13){  
                    $("#loginbtn").trigger("click");  
                }  
            }); 
   		$("#txtName").on('keyup',function () {  
                if (event.keyCode == 13){ 
                    $("#txtPwd").focus();  
                }  
            });   
		//关闭
		$(".close_btn").hover(function () { $(this).css({ color: 'black' }) }, function () { $(this).css({ color: '#999' }) }).on('click', function () {
			$("#LoginBox").fadeOut("fast");
			$("#mask").css({ display: 'none' });
		});

		$(function () {
			$("#txtName").focus();	
		 });
	};
