<?php
?>
<style>
#form_container{
	width:400px;
	margin:auto;
	border:double;
	padding:10px;
}
</style>
<h1>مشاهده ی وضعیت کارشناس</h1>
<?php if (isset($this->Result))
ViewResultPlugin::Show($this->Result,$this->Error);
?>
<?php if (true) {?>
	<div id='form_container'>
		<div>نام:<?php echo $this->Name;?></div>
		<?php echo $this->Form->PresentHTML();?>
		<script>
		<?php echo $this->Form->PresentScript();?>
		</script>
		<a href="./assigned?id=<?php echo $this->ID;?>">مشاهده لیست اظهارنامه های فعلی</a><br>
		مشاهده لیست اظهارنامه های کارشناسی شده<br>
		<a href="./list">بازگشت به لیست کارشناسان</a><br>
	</div>
<?php }	?>


<!-- --------------------------------------List of Assigned Files-------------- -->
<h3>اظهارنامه های نزد کارشناس</h3>
<?php if (count($this->MyUnreviewedFiles)) {	?>
	<span style='margin-right: 100px; float: right;'>
	<input type="checkbox" id="SelectAll"> انتخاب همه </span> 
	<?php $this->FileAutoList->PresentSortbox();?> 
	<form method='post'>
		<?php $this->FileAutoList->Present();?>
		<input type="submit" value='تخصیص کوتاژ های انتخابی به کارشناس دیگر'>
	</form>
<?php } ?>
<?php if (count($this->ResultList)) { //results?>
	<div id='rangeResult'>
		<h2>نتایج تخصیص اظهارنامه‌های انتخابی به کارشناس</h2>
		<?php $this->ResultList->Present();?>
	</div>
<?php }?>
<script>
	$("#SelectAll").click(function()
			{
				$(".item").attr("checked",$("#SelectAll").attr("checked"));
			});
</script>
<!-- ----------------------------------L O A F--------------------------------- -->
