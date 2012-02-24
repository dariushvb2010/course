<?php
$this->HasError=count($this->Error);
?>
<h1><img src="/img/h/h1-registerarchive-50.png"/>
وصول اظهارنامه</h1>
<style>

form {
	width:500px;
	margin:auto;
	padding:10px;
	border:3px double;
	text-align:center;
}
form input[type='submit'] {
	width:200px;
	margin:5px;
}
form input[type='text'] {
	width:150px;
}
<?php if($this->HasError) {?>
#body{background:red; -moz-box-shadow:10px 10px 50px 100px #FAFAFA inset; box-shadow:10px 10px 50px 100px #FAFAFA inset;}
<?php }?>
</style>

<form method='post'>
<?php if (isset($this->Result))
		{
			ViewResultPlugin::Show($this->Result,$this->Error);	
		}
		if($this->HasError)
		{
			AutosoundPlugin::EchoError("error3");
		}
?>
	<a href='/help/#archive_new'>
	<img src='/img/web/icon/help32.png' style='border:0px solid gray; float:left;' />
	</a>
<div>
	<label>شماره کوتاژ</label>
	<input type='text' name='Cotag' />

</div>

<input type='submit' value='وصول' />
</form>

<script>
$(function(){
	
	$("form input[name='Cotag']").focus();
});

	<?php 
	if($this->HasError){
		?>
		function back()
		{
			document.getElementById("body").style.background="#FAFAFA";
		}
		setTimeout(back,2000);
	<?php }?>
</script>