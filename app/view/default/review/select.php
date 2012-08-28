<?php
?>
<style>

#formdiv {
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

<h1><img src="/img/h/h1-review-50.png"/>
<?php echo $this->Title;?></h1>
<p>
تعداد اظهارنامه‌های در انتظار کارشناسی : 
<strong> 
<?php echo $this->Count; ?> 
 </strong>
</p>
<?php if ($this->PageType=="ReviewerView"){?>
<p>
شماره کوتاژ اظهارنامه‌ای که به شما تخصیص یافته وارد نمایید تا نتایج بازبینی مربوطه را وارد نمایید.
</p>

<form method='post'>
<div id="formdiv"> 
<?php if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);
?>
<div>
	<label>شماره کوتاژ</label>
	<input type='text' name='Cotag' />

</div>

<input type='submit' value='انتخاب' />
</div>
<?php } ?>

<?php if($this->Count){ ?>
	<div id="filelist">
		<?php
			$this->FileAutoList->Present(); 
		?>
	</div>
<?php }else{?>
<?php }?>
</form>

<script>
$(function(){
	$("form input[name='Cotag']").focus();
});
</script>