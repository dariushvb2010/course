<style>
	div.jChat{border:2px solid navy; -moz-box-shadow:5px gray;  overflow:hidden;
				 -moz-border-radius:5px; position: fixed; top:7px; left:1030px; display:none; background:white;
				 }
	iframe.jChat{background:white; height:420px; width:270px;}
	p.iframeclose {margin:0; padding:0; background:rgb(107,165,208); color:white;}
	p.iframeclose span:hover{cursor:pointer;}
	
	
#sideMenu {
	width:200px;
	margin:8px;
	padding:5px;
	border:3px double gray;
	float:left;
	font-size:small;
}
#sideMenu a {
	display:block;
}
</style>

<?php ViewAlarmPlugin::EchoCSS();?>
<h2><?php echo reg("app/title")." نسخه ".reg("app/version");?></h2>
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
		<a href="/alarm">ایجاد یادآور دستی</a>
		<a href="/desk/alarm">مشاهده پیام ها</a>
		<a href="/help/main">راهنما</a>
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
<?php 
ViewAlarmPlugin::GroupShow($this->Alarm_Personal,"پیام های فردی");
ViewAlarmPlugin::GroupShow($this->Alarm_Group,"پیام های مربوط به واحد".$this->GroupTitle);
?>
<!--  
 	<div class="jChat">
 	<p class="iframeclose" ><span>X</span></p>
 	<iframe class="jChat" src=" echo SiteRoot;?>/jchat/iframe"></iframe>
 	</div> 
-->
<script>
<?php ViewAlarmPlugin::PresentScritp();?>
$("iframe.jChat").css("top",top+"px");
$(document).ready(function(){
	$("div.jChat").show("slow");
	h=$(window).height();
	$("div.jChat").height(300);
});
$("p.iframeclose span").click(function(){ 

	ifr=$("iframe.jChat");
	div=$("div.jChat");
	span=$("p.iframeclose span");
	if(ifr.css("display")=="none")
	{
		span.html("X");
		div.css("height","500px");
	}
	else
	{
		span.html("Chat");
		div.css("height","20px");
	}
	$("iframe.jChat").toggle("slow");
		

});
</script>