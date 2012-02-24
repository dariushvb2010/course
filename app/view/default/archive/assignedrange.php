<?php
?>
<style>
form {
	width:400px;
	margin:10px auto;
	padding:10px;
	border:3px double;
	text-align:center;
}
form input[type='submit'] {
	width:100px;
}
form label {
width:100px;
}
form h3 {
	margin:0px;
	padding:0px;
	text-align:right;
	margin-bottom:2px;
}
#singleResult {
	width:400px;
	border:3px double gray;
	color:darkgreen;
	font-size:36px;
	font-weight:bold;
	margin:10px auto;
	padding:10px;
	text-align:center;
}
#singleResult span {
	font-size:12px;
}
#rangeResult .Reviewer {
	color:darkgreen;
	font-weight:bolder;
}
</style>
<h1>مشاهده ی  کارشناسان اظهارنامه ها</h1>
<p>
<?php if (!count($this->Files)) {?>
<form method='post'>
<a href='/help/#archive_assign_range'>
<img src='/img/web/icon/help32.png' style='border:0px solid gray; float:left;' />
</a>
<?php if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);
?>
<label>کوتاژ آغازی</label>
<input type='text' name='RangeStart' />
<br/>
<label>کوتاژ پایانی</label>
<input type='text' name='RangeEnd' />
<br/>
<input type='submit' value='مشاهده' />
</form>



<?php }else{ ?>
<div id='rangeResult'>
	<?php 
	$this->AssignedList->Present();
	?>
	<a href="">مشاهده ی بازه ی جدید</a>
</div> 
<?php }?>


<script>
function validateRange()
{
	val1=$("input[name='RangeStart']").val()*1;
	val2=$("input[name='RangeEnd']").val()*1;
	if (val2-val1<0)
	{
		alert("شماره پایانی باید از شماره آغازی بیشتر باشد");
		return false;
	}
	
	return true;
}
</script>