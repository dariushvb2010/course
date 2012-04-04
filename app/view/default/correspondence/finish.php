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
<h1>ختم مکاتبات</h1>


<form method='post'>
<?php if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);
?>

	<a href='/help/#correspondence_sendfile'>
	<img src='/img/web/icon/help32.png' style='border:0px solid gray; float:left; margin:0;' />
	</a>
	
<div>
	<label>شماره کوتاژ</label>
	<input type='text' name='Cotag' />
	
</div>

<input type='submit' value='ارسال' />
</form>