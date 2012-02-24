<?php
?>
<h1>لغو وصول بایگانی بازبینی</h1>
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

<form method='post'>
<?php
 if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);
?>
	<a href='/help/#archive_new'>
	<img src='/img/web/icon/help32.png' style='border:0px solid gray; float:left;' />
	</a>
<div>
	<label>شماره کوتاژ</label>
	<input type='text' name='Cotag' />

</div>
<input type='submit' id='sub' value='لغو وصول' />
</form>
<script>
	
	$("form input[name='Cotag']").focus();

</script>