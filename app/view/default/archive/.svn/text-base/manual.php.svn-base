<?php
?>
<h1>ثبت اظهارنامه‌های  پیشین</h1>
<style>

form {
	width:400px;
	margin:auto;
	padding:10px;
	border:3px double;
	text-align:center;
}
form input[type='submit'] {
	width:200px;
	margin:5px;
}
.date
{
	width:25px;
	margin :3px;
	text-align: center;
}
.text {
	width:150px;
	text-align: center;
}
#year
{
	width:40px;
	margin :3px;
	text-align:left;
}
</style>

<form method='post'>
<?php if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);
?>
<div>
	<label>شماره کوتاژ</label>
	<input class='text' type='text' name='Cotag' />

</div>
<div>
	<label>تاریخ وصول اظهارنامه</label>
	<input class='date' type='text' name='CDay' value='<?php echo $_POST['CDay'];?>'/> /
	<input class='date' type='text' name='CMonth' value='<?php echo $_POST['CMonth'];?>' /> /
	<input id='year' type='text' name='CYear' value='<?php if (isset($_POST['CYear']))echo $_POST['CYear'];else echo 13;?>' /> 
</div>
	<a href='/help/#archive_manual'>
	<img src='/img/web/icon/help32.png' style='border:0px solid gray; float:right;' />
	</a>

<input type='submit' value='وصول' />
</form>