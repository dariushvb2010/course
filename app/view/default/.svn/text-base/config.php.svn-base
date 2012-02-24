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

</style>
<h1>تنظیمات کلی سیستم
</h1>

<div>
<?php $this->List->Present();?>
</div>
<div>
<form id="add_form" method="post">
	 <?php if (isset($this->Result))
ViewResultPlugin::Show($this->Result,$this->Error);
?>
	<label>عنوان</label><input name="Name" /><br/>
	<label>مقدار</label><input name="Value" /><br/>
	<label>امکان حذف</label><select name="DeleteAccess"><option value="1">بله</option><option value="0">خیر</option></select><br/>
	<label>توضیحات</label><textarea name="Comment" cols="40" rows="6"></textarea>
	<input type="submit" value="اضافه کردن" />
</form>
</div>
<script src="/script/editlist.js"></script>