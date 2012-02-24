<?php
?>
<style>

form {
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
</style>
<h1>مختومه کردن پرونده بازبینی</h1>
<p>
<strong>توجه : </strong>
تنها پرونده‌هایی که روند کاری آنها کامل شده است، یعنی توسط کارشناس بازبینی بدون مشکل تشخیص داده شده و یا مکاتبات آنها تکمیل گردیده است، قابل مختومه کردن هستند.
پرونده‌های مختومه دیگر قابل ویرایش نیستند و تنها در گزارش‌گیری استفاده می‌گردند.
</p>

<form method='post'>
<?php if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);
?>
	<a href='/help/#archive_finish'>
	<img src='/img/web/icon/help32.png' style='border:0px solid gray; float:right;' />
	</a>
<div>
	<label>شماره کوتاژ</label>
	<input type='text' name='Cotag' />

</div>

<input type='submit' value='مختومه کردن' />
</form>