<?php
?>
<style>
form {
	width:400px;
	margin:10px auto;
	padding:10px;
	text-align:center;
}
form#rangeform{
	border:3px double;
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
<h1>تخصیص اظهارنامه به کارشناس</h1>


<?php if (count($this->Files)) {?>
<div id='rangeResult'>
	<a href="">تعیین بازه ی جدید</a>
	<?php
	if($this->ResultList)
		$this->ResultList->Present(); 
	?>
</div> 
<?php }else{?>
<p>
<strong>توجه : </strong>
در فرآیند تخصیص اظهارنامه به کارشناس بازبینی،
به ازای هر شماره کوتاژی که در سیستم وارد گردد، کارشناسی تعیین می‌گردد.
از آنجایی که این فرآیند برگشت پذیر نمی‌باشد، دقت کافی مبذول فرمایید.
</p>

<form id="rangeform" method='post'>
<?php if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);
?>
<a href='/help/#archive_assign_range'>
<img src='/img/web/icon/help32.png' style='border:0px solid gray; float:left;' />
</a>
<h3>تخصیص بازه‌ای</h3>
<label>کوتاژ آغازی</label>
<input type='text' name='RangeStart' />
<br/>
<label>کوتاژ پایانی</label>
<input type='text' name='RangeEnd' />
<br/>
<input type='submit' value='تخصیص بازه‌ای' />
</form>

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