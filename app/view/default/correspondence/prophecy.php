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
<h1>ثبت تاریخ ابلاغ</h1>


<form method='post'>
<a href='/help/#correspondence_class'>
<img src='/img/web/icon/help32.png' style='border:0px solid gray; float:left;' />
</a>
<div>
	<label>شماره کوتاژ</label>
	<input type='text' name='Cotag' /><br/>
	<label>تاریخ ابلاغ</label>
	<input type='text' name="Date" /><br/>
<input type='submit' value='ثبت' />
</div>
</form>
<?php

ViewResultPlugin::Show($this->Result,$this->Error);
if ($this->class)
{
	?>
<div id='singleResult'>
<span style='float:left;'>شماره کوتاژ :‌ 
<strong>
<?php echo $this->Cotag;?>
</strong>
</span>
<span>کلاسه : </span>
<?php
	echo $this->class;?>
</div>
<?php 
}
?>
<script>
$(function(){
	
	$("form input[name='Cotag']").focus();
});
</script>