<?php
$this->HasError=(count($this->Error));
?>
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
</style>
<div id='exceptBarcode'>
<h1>تنظیم وضعیت ها</h1>
<form method='post'>
<?php if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);
	if($this->HasError)
		AutosoundPlugin::EchoError("error3");
?>
	<a href='/help/#archive_new'>
	<img src='/img/web/icon/help32.png' style='border:0px solid gray; float:left;' />
	</a>
<div>
	<label>شماره کوتاژ</label>
	<input name='Cotag' value='<?php echo $_POST['Cotag'];?>' /><br/>
	<label>شماره وضعیت</label>
	<input type='text' name='Num'  value='<?php echo $_POST['Num'];?>'/>
	
</div>

<input type='submit' id='sub' value='ایجاد' />
</form>
</div>

