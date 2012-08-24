<?php
?>
<style>

#formdiv {
	width:400px;
	margin:auto;
	padding:10px;
	border:3px double;
	text-align:center;
}
form input[type='submit'] {
	width:200px;
	margin:5px;
}
form input[type='text'] {
	width:150px;
}
#filelist{
	margin:5px;
}
</style>

<h1><img src="/img/h/h1-review-50.png"/>
اعلام اظهارنامه های بدون مشکل</h1>
<p>
تعداد اظهارنامه‌های در انتظار بازبینی توسط شما : 
<strong> 
<?php echo $this->Count; ?> 
 </strong>
</p>
<?php if($this->OKCount>0){?>
<p>  
<strong> 
<?php echo $this->OKCount; ?> 
 </strong>
 اظهارنامه مورد تایید شما قرار گرفت.
</p>
<?php }?>

<p>
شماره کوتاژ اظهارنامه‌ای که به شما تخصیص یافته وارد نمایید.
</p>

<form method='post'>
<div id="formdiv"> 
<?php if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);
?>
<div>
	<label>شماره کوتاژ</label>
	<input type='text' name='Cotag' />

</div>

<input type='submit' name="add" value='اضافه کردن' />
<input type='submit' name="send" value='ثبت نتیجه' />
</div>
<?php if($this->Count){ ?>
	<div id="filelist">
		<?php
			$this->FileAutoList->Present(); 
		?>
	</div>
<?php }else{?>
<?php }?>
</form>

<script>
$(function(){
	$("form input[name='Cotag']").focus();
});
</script>
<script>
$(function(){
	$("input[name='add']").click(function (){
		x=$("input[name='Cotag']").val();
		$(".item[value='"+x+"']").attr('checked', true);
		$(".item[value='"+<?php echo "'".GateCode."-'";?>+x+"']").attr('checked', true);
		$("form input[name='Cotag']").val('');
		$("form input[name='Cotag']").focus();
		return false;
	});
});
</script>