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
<h1><img src="/img/h/h1-assign-single-50.png"/>
تخصیص اظهارنامه به کارشناس</h1>
<p>
<strong>توجه : </strong>
در فرآیند تخصیص اظهارنامه به کارشناس بازبینی،
به ازای هر شماره کوتاژی که در سیستم وارد گردد، کارشناسی تعیین می‌گردد.
از آنجایی که این فرآیند برگشت پذیر نمی‌باشد، دقت کافی مبذول فرمایید.
</p>

<form method='post'>
<a href='/help/#archive_assign_single'>
<img src='/img/web/icon/help32.png' style='border:0px solid gray; float:left;' />
</a>
<h3>تخصیص تکی</h3>
<div>
	<label>شماره کوتاژ</label>
	<input type='text' name='Cotag' />
<input type='submit' value='تخصیص' />
</div>
</form>
<?php

ViewResultPlugin::Show($this->Result,$this->Error);
if ($this->Reviewer)
{
	?>
<div id='singleResult'>
<span style='float:left;'>شماره کوتاژ :‌ 
<strong>
<?php echo $this->Cotag;?>
</strong>
</span>
<span>کارشناس : </span>
<?php
	echo $this->Reviewer->Firstname()," ",$this->Reviewer->Lastname();?>
</div>
<?php 
}
?>
<script>
$(function(){
	
	$("form input[name='Cotag']").focus();
});
</script>