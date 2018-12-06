//登陆验证
function login_submit(thisform)
{
	with(thisform)
	{
		if((username.value==""||username.value==null)&&(password.value==""||password.value==null))
		{
			alert("用户名和密码不能为空，请输入用户名和密码！");
			return false;
		}
		else if(username.value==""||username.value==null)
		{
			alert("用户名不能为空，请输入用户名！");
			return false;
		}
		else if(password.value==""||password.value==null)
		{
			alert("密码不能为空，请输入密码！");
			return false;
		}else
		{
			return true;
		}
	}
}

//添加通讯录验证
function addAdressList(thisform)
{
	with(thisform)
	{
	
		

		if((name.value==""||name.value==null)&&(tel.value==""||tel.value==null) &&(deparyment.value==""||deparyment.value==null)
		
			&&(position.value==""||position.value==null)
		)
		{
			alert("提交的内容不能为空！");
			return false;
		}
		else if(name.value==""||name.value==null)
		{
			alert("姓名不能为空，请输入姓名！");
			return false;
		}
		else if(tel.value==""||tel.value==null)
		{
			alert("电话不能为空，请输入电话！");
			return false;
			
		}
		else if(isNaN(tel.value)){
		
            alert("号码必须是数字！请正确填写！");
            return false;
        } 
		else if(deparyment.value==""||deparyment.value==null)
		{
			alert("部门名称不能为空，请输入部门名称！");
			return false;
		}
		else if(position.value==""||position.value==null)
		{
			alert("职位不能为空，请输入职位！");
			return false;
		}
		else if(adress.value==""||adress.value==null)
		{
			alert("住址不能为空，请输入住址！");
			return false;
		}
		else
		{	
			return true;
		}
	}
}


//更新通讯录验证
function editAdressList(thisform)
{
	with(thisform)
	{
	
		

		if((name.value==""||name.value==null)&&(tel.value==""||tel.value==null) &&(deparyment.value==""||deparyment.value==null)
		
			&&(position.value==""||position.value==null)
		)
		{
			alert("提交的内容不能为空！");
			return false;
		}
		else if(name.value==""||name.value==null)
		{
			alert("姓名不能为空，请输入姓名！");
			return false;
		}
		else if(tel.value==""||tel.value==null)
		{
			alert("电话不能为空，请输入电话！");
			return false;
			
		}
		else if(isNaN(tel.value)){
		
            alert("号码必须是数字！请正确填写！");
            return false;
        } 
		else if(deparyment.value==""||deparyment.value==null)
		{
			alert("部门名称不能为空，请输入部门名称！");
			return false;
		}
		else if(position.value==""||position.value==null)
		{
			alert("职位不能为空，请输入职位！");
			return false;
		}
		else if(adress.value==""||adress.value==null)
		{
			alert("住址不能为空，请输入住址！");
			return false;
		}
		else
		{	
			return true;
		}
	}
}

//添加用户验证
function addUsers(thisform)
{
	with(thisform)
	{

		if((usersname.value==""||usersname.value==null)&&(password.value==""||password.value==null))
		{
			alert("用户名和密码不能为空，请输入用户名和密码！");
			return false;
		}
		else if(usersname.value==""||usersname.value==null)
		{
			alert("用户名不能为空，请输入用户名！");
			return false;
		}
		else if(password.value==""||password.value==null)
		{
			alert("密码不能为空，请输入密码！");
			return false;
		}
		
		else if(password2.value==""||password2.value==null)
		{
			alert("再次输入密码不能为空，请再次输入密码！");
			return false;
		}
		
		else if(password.value.length<6||password.value.length>12){
			alert("密码至少6个字符,最多12个字符");
			return false;
		}
		
		else if(password2.value.length<6||password2.value.length>12){
			alert("密码至少6个字符,最多12个字符");
			return false;
		}
		
		else if(password.value !=password2.value)
		{
			alert("两次密码不一致！");
			return false;
		}
		
		else
		{
			return true;
		}
	}
}


//更新用户验证
function editUsers(thisform)
{
	with(thisform)
	{
		if((usersname.value==""||usersname.value==null)&&(password.value==""||password.value==null))
		{
			alert("用户名和密码不能为空，请输入用户名和密码！");
			return false;
		}
		else if(usersname.value==""||usersname.value==null)
		{
			alert("用户名不能为空，请输入用户名！");
			return false;
		}
		else if(password.value==""||password.value==null)
		{
			alert("密码不能为空，请输入密码！");
			return false;
		}
		else if(password.value.length<6||password.value.length>12){
			alert("密码至少6个字符,最多12个字符");
			return false;
		}
		
		else if(password2.value.length<6||password2.value.length>12){
			alert("密码至少6个字符,最多12个字符");
			return false;
		}
		
		else if(password2.value==""||password2.value==null)
		{
			alert("再次输入密码不能为空，请再次输入密码！");
			return false;
		}
		
		else if(password.value !=password2.value)
		{
			alert("两次密码不一致！");
			return false;
		}
		
		else
		{
			return true;
		}
	}
}



