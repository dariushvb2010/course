<?php
?>
<style>
#add_form{
	width:500px;
	margin:auto;
	padding:10px;
	border:3px double;
	text-align:center;
}
#add_form input[type='submit'] {
	width:200px;
	margin:5px;
}
#add_form input[type='text'] {
	width:150px;
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

#rangeResult .Reviewer {
	color: darkgreen;
	font-weight: bolder;
}
<?php $this->Form->PresentCSS(); ?>
</style>
<h1><img src="/img/h/h1-topics-50.png"/>
مدیریت عناوین</h1>
<p><span style="color:red;">توجه: </span> 
عناوینی قابل حذف کردن هستند که هنوز مورد استفاده قرار نگرفته اند.</p>
<?php if (isset($this->Result))
ViewResultPlugin::Show($this->Result,$this->Error);
?>
<?php if (count($this->Topics)) {?>
	<span style='margin-right: 100px; float: right;'>
	<input type="checkbox" id="SelectAll"> انتخاب همه </span>	<br/>
	<form method='post'>
		<?php $this->TopicList->Present();?>
		<input type="submit" value='حذف عناوین انتخاب شده'>
	</form>
<?php }	?>
<br/>


	<h4 style="text-align: center;">اضافه کردن عنوان</h4>
	
	<?php $this->Form->PresentHTML();?>
	<script>
	<?php $this->Form->PresentScript();?>
	</script>


<script>
	$("#SelectAll").click(function()
			{
				$(".item").attr("checked",$("#SelectAll").attr("checked"));
			});
</script>
