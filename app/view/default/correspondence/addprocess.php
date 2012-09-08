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
<h1><img src="/img/h/h1-correspondence-50.png"/>
مکاتبات</h1>

<?php if(isset($this->File)){
	include dirname(__FILE__).'/'.'../report/blocks/fileinfobox.php';
}?>

<form method='post' action="">
<?php if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);
?>

	<a href='/help/#correspondence_sendfile'>
	<img src='/img/web/icon/help32.png' style='border:0px solid gray; float:left; margin:0;' />
	</a>
	<label>شماره کوتاژ</label>
	<input type='text' name='Cotag' value='<?php echo $this->Cotag?>'/><br/>
	<input type='submit' value='ورود'/>	
</form>

<?php if($this->ProcessArray){ ?>
<form method='post' action="">
	<input type='hidden' name='Cotag' value='<?php echo $this->Cotag;?>'>	
	<?php 
	foreach ($this->ProcessArray as $p){
	?>
	<input type='submit' name="###<?php echo $p->Name;?>" value="<?php echo $p->Label;?>"  label="hi"/><br/>
	<?php }	?>
</form>
<?php }else{?>
	<form method='post' action="">
		<div> در حال حاضر فرآیندی برای این پرونده تعریف نشده است.</div>
	</form>
<?php }?>