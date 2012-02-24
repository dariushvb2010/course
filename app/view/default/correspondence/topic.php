<?php
?>
<style>
form#add {
	width:500px;
	margin:auto;
	padding:10px;
	border:3px double;
	text-align:center;
}
form#add input[type='submit'] {
	width:200px;
	margin:5px;
}
form#add input[type='text'] {
	width:150px;
}
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

#rangeResult .Reviewer {
	color: darkgreen;
	font-weight: bolder;
}
</style>
<h1>مدیریت عناوین مکاتبات</h1>
<?php if (isset($this->Result))
ViewResultPlugin::Show($this->Result,$this->Error);
?>
<?php if (count($this->Topics)) {

	?>
<span style='margin-right: 100px; float: right;'> <input
	type="checkbox" id="SelectAll"> انتخاب همه </span> 
	<br/>
<form method='post'>
	<?php
	$this->TopicList->Present();
	?> <input type="submit" value='حذف عناوین انتخاب شده'>
	</form>
	<?php }	?>
	<br/>
	<h4 style='margin-right:200px;'>اضافه کردن عنوان</h4>
<form id="add" method='post'>
			<a href='/help/#correspondence_topic'>
			<img src='/img/web/icon/help32.png' style='border:0px solid gray; float:left; margin:0;' />
			</a>
			
		<div><div style="margin:0 30px;">
			<label>عنوان</label>
			<input type='text' name='Topic' />
			<br/><br/>
			<label>توضیحات</label>
			<textarea  name='Comment' style="width:200px; height:80px;"></textarea>
		</div></div>
		
		<input type='submit' value='اضافه' />
</form>
<script>
	$("#SelectAll").click(function()
			{
				$(".item").attr("checked",$("#SelectAll").attr("checked"));
			});
</script>
