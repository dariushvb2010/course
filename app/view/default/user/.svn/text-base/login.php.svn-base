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
#body{
	background-image:url('/img/Iran_flag2.png');
	background-repeat: no-repeat;
}
form#login {
	border: 2px ridge rgb(135,182,210);
	padding:10px;
	margin:50px 5px 5px;
	background-image:url('/img/bg_login_form.png');
	-moz-border-radius:5px;
}

</style>
<div id="background_container">
<img src="/img/logo2.png" style="width:70px; float:right;"/>
<div id="login_container" dir='rtl'>

<form id="login" method="post">
	<strong>ورود به <?php echo reg("app/title");?></strong>
	<br/>
	<?php if (isset($this->Result) and !$this->Result)
	{
	    ?><span style="color:red">نام کاربری یا رمز عبور ناصحیح است</span><?php
	    $this->Username=$_POST["Username"]; 
	}
	?>
	<br/>
	
	<label>نام کاربری :</label>
	<input type='text' value="<?php echo $this->Username?>" name="Username" />
	<br/>
	<label>رمز عبور :</label>
	<input type="password" name="Password" />
	<br/>
	<div id="remember_container"> 
	<input type="checkbox" value="yes" name="Remember" /> مرا در این رایانه به یاد داشته باشد
	</div>
	
	<div style="margin-top:6px;">
		<input type="submit" value="ورود" class='mymenu ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icons' />
		<input type="button" value="بازگشت" onclick="history.back()" class=' mymenu ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icons'/>
	</div>
<?php if ($this->UserID) {?>
	<br/><a style="font-size:small" href="/uesr/logout?return=/user/login">ورود با نام کاربری دیگر</a>
<?php } ?>
	
	</form>

</div>
</div>
<?php 
