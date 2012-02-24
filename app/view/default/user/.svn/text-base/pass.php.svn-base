<?php
?>
<script>
</script>
<style>
label { 
	width:100px;
	float:right;
}
input[type="text"],input[type="password"] {
	width:150px;
	direction:ltr;
	text-align:center;
	margin:5px;	
}

#remember_container {
	text-align:center;
}
#login_container {
	margin:auto;
	text-align:center;
	width:300px;
	
}
form#login {
	border: 2px ridge;
	padding:10px;
	margin:5px;
	}

</style>
<h1>تغییر رمز عبور</h1>
<form method="post">
	<?php if (isset($this->Result))
ViewResultPlugin::Show($this->Result,$this->Error);
?>
	<br/>
	<label>رمز کنونی :</label>
	<input type='password' name="OldPassword" />
	<br/>
	<label>رمز جدید :</label>
	<input type="password" name="NewPassword" />
	<br/>
	<label>تکرار رمز :</label>
	<input type="password" name="Retype" />
	<br/>
	
	
	<input type="submit" value="تغییر" class='button' />
	<input type="button" value="بازگشت" onclick="history.back()" class='button'/>
	
	</form>

