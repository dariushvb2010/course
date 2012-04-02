<style>
#left{float:left;margin:20px;}
#logo{text-align:center;}
#sideMenu {
	width:200px;
	margin-top:5px;
	padding:5px;
	border:3px double gray;
	float:left;
	font-size:small;
}
#sideMenu a {
	display:block;
}
</style>
<h2><?php echo reg("app/title");?>

(نسخه <?php echo reg("app/version");?>)
</h2>
<div id="left">
		<div id="logo">
			<img src="/img/logo2.png" style="width:70px;"/>
		</div>
		<div id='sideMenu'>
		<div style='text-align:center;font-weight:bolder;font-size:1.2em;'>
			دسترسی سریع
		</div>
		<hr/>
		<a href='/help/deploy'>
		راهنمای نصب و راه‌اندازی
		</a>
		<?php if (j::Check("Report")){?>
		<a href='/report/progresslist'>
		گردش کاری اظهارنامه
		</a>
		<?php }?>
		</div>
</div>


<p>
	خوش آمدید.
	در صورتی که تازه کار خود با این نرم‌افزار را شروع کرده‌اید، توصیه می‌شود ابتدا
	<a href='/help/deploy'>
	راهنمای نصب و راه‌اندازی نرم‌افزار
	</a>
	
	را مطالعه فرمایید و سپس به مطالعه راهنمای جامع نرم‌افزار بپردازید.
	همچنین توجه داشته باشید که در هر بخش نرم‌افزار پیوندی به بخش راهنما جهت توضیح نکات و  کارکرد‌های آن بخش خاص مهیا گردیده است.
	
</p>
<p>
	توجه داشته باشید که در این نرم‌افزار در دست توسعه است و امکانات آن روز به روز افزایش و تغییر می‌یابند، لذا در صورتی که با نکته مبهمی مواجه شدید، راهنمای مربوطه را 
	مطالعه بفرمایید.
	
</p>