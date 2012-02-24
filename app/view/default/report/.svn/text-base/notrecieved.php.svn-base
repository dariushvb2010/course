
<h1>لیست کوتاژهای وصول نشده</h1>
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


<?php if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);
?>




<?php 
	if (isset($_POST['RangeStart']) && !isset($this->Error) && isset($_POST['RangeEnd'])) {?>
<p style="text-align: center;">
لیست کوتاژهای وصول نشده بین کوتاژ
<strong><?php echo $_POST['RangeStart'];?></strong> 
و 
<strong><?php echo $_POST['RangeEnd'];?></strong>
<br/> 
  تعداد کل   :
<strong><?php echo $this->Count;?></strong> 

 </p>
 <div align='center'>
 <?php 
 	
 		$this->al->Width="30%";
		$this->al->Present();
		
?>
<a href=./notrecieved>لیست کوتاژ های وصول نشده یک بازه جدید</a>
	
<?php } 	else { ?>
		<form method='post'>
<a href='/help/#report_notreceived'>
<img src='/img/web/icon/help32.png' style='border:0px solid gray; float:left;' />
</a>
<h3>لیست کوتاژ های وصول نشده در یک بازه خاص</h3>

<label>کوتاژ آغازی</label>
<input type='text' name='RangeStart' />
<br/>
<label>کوتاژ پایانی</label>
<input type='text' name='RangeEnd' />
<br/>
<input type='submit' value='نمایش' />
</form>
<?php }?>	

 </div>


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
	if (Val2-val1>200)
		return confirm("اندازه بازه انتخاب شده بیش از ۲۰۰ اظهارنامه است. آیا مطمئنید؟");
	return true;
}
</script>