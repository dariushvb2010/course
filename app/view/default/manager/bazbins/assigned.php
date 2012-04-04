<?php
?>
<style>
</style>
<h1>اظهارنامه های نزد کارشناس</h1>
<?php if (isset($this->Result))
ViewResultPlugin::Show($this->Result,$this->Error);
?>
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
