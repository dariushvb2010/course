<?php
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
<h1>دریافت پرونده</h1>


<form method='post'>
<?php if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);
?>

	<a href='/help/#correspondence_sendfile'>
	<img src='/img/web/icon/help32.png' style='border:0px solid gray; float:left; margin:0;' />
	</a>
	
<div><div style="margin:0 30px;">
	<label>شماره کوتاژ</label>
	<input type='text' name='Cotag' />
	<br/>
	<label>شماره نامه </label>
	<input type='text' name='MailNum'/>
	<br/>
	<label>ارسال کننده</label>
	<input type='text' name='Sender'/>
	<br/><br/>
	<label>توضیحات</label>
	<textarea  name='Comment' style="width:200px; height:80px;"></textarea>
</div></div>

<input type='submit' value='دریافت' />
</form>