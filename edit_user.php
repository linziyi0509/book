<?php include_once("header.php");
if($islogin>0){			
?>


    <div class="container-fluid">
      <div class="row-fluid">
        
		<?php
		error_reporting(0);
		include_once('inc/conn.php');
		$id=$_GET["id"];
		if($id!=$u_id){
			echo"没有修改权限！";
			exit;
		}


		$sql=" SELECT * FROM user WHERE id ='$id'";
		$result=mysql_query($sql); 
		while($row=mysql_fetch_object($result))
			 {
		?>
              
              <div id="show_num"><br/>修改密码</div>  
                                      
	<div class="span8">
      <form class="form-horizontal" action="edit_user_do.php?id=<?php echo $row->id ?>" method="post" name="myform" onsubmit="return editAdressList(this)">
        <fieldset>
         							

          <div class="control-group">
            <label for="input01" class="control-label">姓名：</label>
            <div class="controls">
              <input type="text" value="<?php echo $row->name ?>" id="input01" class="input-xlarge" name="name" disabled="true">
            </div>
          </div>
          

          
          <div class="control-group">
            <label for="input01" class="control-label">年级：</label>
            <div class="controls">
              <input type="text" id="input01" value="<?php echo $row->nianji ?>" class="input-xlarge" name="nianji" disabled="true">
            </div>
          </div>
		  
		  <div class="control-group">
            <label for="input01" class="control-label">新密码：</label>
            <div class="controls">
              <input type="password" id="input01" class="input-xlarge" name="newPassword" value=""  onfocus="this.select()">
            </div>
          </div>

		   <div class="control-group">
            <label for="input01" class="control-label">再次输入新密码：</label>
            <div class="controls">
              <input type="password" id="input01" class="input-xlarge" name="newPassword2" value="" onfocus="this.select()">
            </div>
          </div>
          
		  
		
       <input type="hidden"  name="oldPassword" value="<?php echo $row->userpass ?>" >


		 <?php
		 }?>

          <div class="form-actions">
            <button class="btn btn-primary" type="submit">更新</button>
			<!--<input class="btn" onclic="javascript:history.back(-1)" value="取消"/>-->
            <input class="btn " type="reset"/>

          </div>
        </fieldset>
      </form>
      

    </div><!--/span-->
      </div><!--/row-->



<?php 
}else{

	echo "<br><br><br><br>请先登录，再完成相关操作！";
}
include_once("footer.php");
?>

    </div><!--/.fluid-container-->



  </body>

	<script language="JavaScript" type="text/JavaScript">
	
	
		//更新通讯录验证
		function editAdressList(thisform)
		{
			with(thisform)
			{
			
				if((newPassword.value==""||newPassword.value==null)&&(newPassword2.value==""||newPassword2.value==null))
			    {
				alert("两次密码均不能为空，请重新输入！");
				return false;
			    }
				else if(newPassword.value==""||newPassword.value==null)
			    {
				alert("新密码不能为空！");
				return false;
				}
				else if(newPassword.value!=newPassword2.value)
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
	</script> 
</html>
