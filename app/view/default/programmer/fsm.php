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
	<label>شماره وضعیت</label>
	<input type='text' name='Num' id='Cotag' value='<?php echo $_POST['Num'];?>'/><br/>
	<label>عنوان وضعیت به انگلیسی</label>
	<input name='Str' value='<?php echo $_POST['Str'];?>' /><br/>
	<label>مکان</label>
	<input name='Place' value='<?php echo $_POST['Place'];?>' /><br/>
	<label>توضیحات</label>
	<textarea cols='30' rows='5' name='Summary'><?php echo $_POST['Summary']?></textarea><br/>
</div>

<input type='submit' id='sub' value='ایجاد' />
</form>
</div>
<?php $this->List->Present(); 

 ?>
