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

<?php $this->Form->PresentCSS();?>

</style>

<h1><img src="/img/h/h1-receive-50.png"/>
 دریافت اظهارنامه</h1>
<?php if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);
 if(!isset($_GET['Cotag']) || isset($this->Result)){?>
<form method='get'>

	<a href='/help/#archive_receive'>
	<img src='/img/web/icon/help32.png' style='border:0px solid gray; float:left;' />
	</a>
<div>
	<label>شماره کوتاژ</label>
	<input type='text' name='Cotag' />

</div>

<input type='submit' value='وصول' />
</form>
<?php } if($this->Send){?>

<?php if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);
?>
<?php $this->Form->PresentHTML();}?>
<script>
<?php $this->Form->PresentScript();?>
$("input[name='Cotag']").focus();
</script>


