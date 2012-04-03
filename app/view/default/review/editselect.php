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

<h1>انتخاب اظهارنامه جهت ویرایش نتیجه بازبینی</h1>
<!-- 
<p>
تعداد اظهارنامه‌های بازبینی شده توسط شما : 
<strong> 
<?php  //echo $this->Count; ?>
 </strong>
</p>
-->
<p>
شماره کوتاژ اظهارنامه‌ ی بازبینی شده را وارد نمایید تا نتایج بازبینی را ویرایش نمایید.
</p>
<form method='post'>
<?php if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);
?>
<div>
	<label>شماره کوتاژ</label>
	<input type='text' name='Cotag' />

</div>

<input type='submit' value='انتخاب' />
</form>
<?php if($this->Count){ ?>
	<div id="filelist">
		<?php
			//$this->FileAutoList->Present(); 
		?>
	</div>
<?php }else{?>
<?php }?>
<script>
$(function(){
	
	$("form input[name='Cotag']").focus();
});
</script>