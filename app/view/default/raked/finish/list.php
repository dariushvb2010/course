<?php
?>
<style>
#sortform_Select {
}

#singleResult {
	width: 400px;
	border: 3px double gray;
	color: darkgreen;
	font-size: 36px;
	font-weight: bold;
	margin: 10px auto;
	padding: 10px;
	text-align: center;
}

#singleResult span {
	font-size: 12px;
}

#finishresult{
	width:200px;
}

#rangeResult .Reviewer {
	color: darkgreen;
	font-weight: bolder;
}
</style>
<h1>مختومه کردن پرونده</h1>
<?php if (isset($this->Result))
ViewResultPlugin::Show($this->Result,$this->Error);
?>

<?php if (count($this->FinishableFiles)) {

	?>
<span style='margin-right: 100px; float: right;'> <input
	type="checkbox" id="SelectAll"> انتخاب همه </span> 
	<?php 		
	$this->AssignList->PresentSortbox();
	?> 
<form method='post'>
	<?php
	$this->AssignList->Present();
	?> <input type="submit" value='مختومه کردن پرونده های  انتخابی'>
	</form>
	<?php }

	if (count($this->ResultList)) { //results?>
<div id='rangeResult'>
<h2>نتایج مختومه کردن پرونده ها</h2>
	<?php
	$this->ResultList->Present();
	?></div>
	<?php }?>
<script>
	$("#SelectAll").click(function()
			{
				$(".item").attr("checked",$("#SelectAll").attr("checked"));
			});
</script>
