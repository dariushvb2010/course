<?php
?>
<h1>اصلاح کوتاژ اظهارنامه</h1>
<p>وارد کردن توضیحات برای اصلاح کوتاژ اظهارنامه لازم است.</p>
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
	
<div>
	<label>شماره کوتاژ فعلی</label>
	<input type='text' name='OldCotag' value="<?php echo $this->OldCotag;?>"/>
</div>
<div>
	<label>شماره کوتاژ جدید</label>
	<input type='text' name='NewCotag' value="<?php echo $this->NewCotag;?>"/>
</div>
<div>
	<label>توضیحات</label>
	<textarea name='Comment'/><?php echo $this->Comment;?></textarea>
</div>
<input type='submit' id='sub' value='اصلاح کوتاژ' />
</form>
<script>
	
	$("form input[name='[OldCotag']").focus();
</script>