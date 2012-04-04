<?php
?>
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
form input[type='text'] {
	width:150px;
}
#filelist{
	margin:5px;
}
</style>

<h1><img src="/img/h/h1-processreview-50.png" />
انتخاب پرونده جهت کارشناسی</h1>
<p>
تعداد پرونده های در انتظار کارشناسی توسط شما : 
<strong> 
<?php echo $this->Count; ?> 
 </strong>
</p>
<p>
شماره کوتاژ پرونده ای که به شما تخصیص یافته وارد نمایید تا نتایج کارشناسی مربوطه را وارد نمایید.
</p>

<form method='post'>
<?php if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);
?>
<div>
	<label>شماره کوتاژ</label>
	<input type='text' name='Cotag' />

</div>
<div>
	<label>شماره کلاسه</label>
	<input type='text' name='Class' />

</div>

<input type='submit' value='انتخاب' />
</form>
<?php if($this->Count){ ?>
	<div id="filelist">
		<?php
			$this->FileAutoList->Present(); 
		?>
	</div>
<?php }else{?>
<?php }?>
<script>
$(function(){
	
	$("form input[name='Cotag']").focus();
});
</script>