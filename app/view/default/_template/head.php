<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html dir='rtl'>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php
if (isset($Title))
	echo $Title;
else
    echo j::Registry("app/title");
?></title>
<?php 
if ($Extra)
	echo $Extra;
?>
<link rel="shortcut icon" href="/img/logo2.png" />
<link rel="stylesheet" href="/style/base.css" />
<link rel="stylesheet" href="/style/print.css" />

<link rel="stylesheet" href="/style/fileuploader.css" />

<link rel="stylesheet" href="/script/calendar/skins/calendar-blue.css" />

<link rel="stylesheet" href="/script/jqueryui/themes/base/jquery.ui.all.css" />

<script src='/script/jquery/ui1.8.14/js/jquery-1.5.1.min.js'></script>
<script src='/script/jquery/ui1.8.14/js/jquery-ui-1.8.14.custom.min.js'></script>
<script src='/script/jqueryui/ui/jquery.ui.core.js'></script>
<script src='/script/jqueryui/ui/jquery.ui.position.js'></script>
<script src='/script/jqueryui/ui/jquery.ui.widget.js'></script>
<script src='/script/jqueryui/ui/jquery.ui.button.js'></script>
<script src='/script/jqueryui/ui/jquery.ui.autocomplete.js'></script>
<script src='/script/jqueryui/ui/jquery.ui.datepicker.js'></script>
<script src='/script/jqueryui/ui/jquery.ui.dialog.js'></script>
<script src='/script/jqueryui/ui/jquery.ui.menu.js'></script>
<script src='/script/jqueryui/ui/jquery.ui.menubar.js'></script>
<script src='/script/jqueryui/ui/jquery.ui.mouse.js'></script>
<script src='/script/jqueryui/ui/jquery.ui.popup.js'></script>
<script src='/script/jqueryui/ui/jquery.ui.progressbar.js'></script>
<script src='/script/jqueryui/ui/jquery.ui.tabs.js'></script>
<script src='/script/jqueryui/ui/jquery.ui.slider.js'></script>
<!--uploader-->
<script src='/script/fileuploader.js'></script>
<!--calender-->
<script src='/script/calendar/calendar.js'></script>
<script src='/script/calendar/calendar-setup.js'></script>
<script src='/script/calendar/jalali.js'></script>
<script src='/script/calendar/lang/calendar-fa.js'></script>


</head>
<body>
<a name='top'></a>
<style>
body{
	background-image:url('/img/background2.png') ;
}
div#http{display:table-cell; width:30%;}
.security {
	border:1px gray solid;
	margin:2px auto;
	-moz-border-radius:5px;
	overflow:none;
	display:block;
	text-decoration:none;
	text-align:center;

}
#securityWarning {
	width:350px;
	padding:1px;
	font-size:small;	
	background-color:darkred;
	color:white;
}
#securityConfirm {
	width:220px;
	padding:1px;
	background-color:darkgreen;
	font-size:11px;	
	color:white;
	border:1px green solid;
}
#securityWarning:HOVER {
	background-color:black;
	color:red;
}
.security img {
	position:relative;
	top:2px;
	border:0px solid;
}
<?php CotagflowPlugin::PresentCSS();?>
</style>
<div id="top" class="noprint">
<?php if (j::UserID()&&$Me=ORM::find(new MyUser,j::UserID())) :?>
	<div id="topright">
		<span title="صفحه اصلی">
			<a class="off" href="<?php echo SiteRoot;?>">
				<img class='off' src="/img/toolbar/home-30.png" />
				<img class='on' src="/img/toolbar/home-on-30.png"/>
			</a>
		</span><span title="ایجاد یادآور">
			<a class="off" href="/alarm">
				<img class='off' src="/img/toolbar/alarmfree-30.png" />
				<img class='on' src="/img/toolbar/alarmfree-on-30.png"/>
			</a>
		</span><span title="گردش کار اظهارنامه" onclick="CGF.toggle();">
			<a class="off" href="#" >
				<img class='off' src="/img/toolbar/cotagflow-30.png" />
				<img class='on' src="/img/toolbar/cotagflow-on-30.png"/>
			</a>
		</span>
	</div>
	<div id="http">
<?php 
if (jURL::Protocol()!="https")
{
	$url="https".substr(jURL::URL(),strlen(jURL::Protocol()));
	?>
	<a id='securityWarning' class='security' href='<?php echo $url;?>'>
		<img  width='16' src='/img/web/icon/unlock32.png' />
		ارتباط شما امن نیست. برای اتصال امن اینجا کلیک کنید.
	</a>
	<?php 
}
else
{
?>
	<a id='securityConfirm' class='security'>
		<img  width='16' src='/img/web/icon/lock32.png' />
		<span style='position:relative;top:-2px;'>
		ارتباط شما امن و رمزشده است.
		</span>
	</a>

<?php 
} 
?>
	</div>
	<div id="topleft" dir="ltr">
		<span title="خروج از سیستم">
			<a href="/user/logout"><img src="/img/toolbar/logout-30.png"/></a>
		</span>
		<span title="راهنما	">
			<a href="/help/main"><img src="/img/toolbar/help-30.png"/></a>
		</span>
		<span title="تنظیمات">
			<a href="/user/setting"><img src="/img/toolbar/setting-30.png"/></a>
		</span>
		<span title="تغییر رمز عبور">
			<a href="/user/pass"><img src="/img/toolbar/pass-30.png"/></a>
		</span>
	</div>
<?php endif;?>
</div><!-- top -->


<?php CotagflowPlugin::PresentHTML();?>
<div id="body" >
<script>
<?php CotagflowPlugin::PresentScript();?>
$("div#top>div#topright span").mouseover(function(){
	$(this).find("img.off").hide();
	$(this).find("img.on").show();
});
$("div#top>div#topright span").mouseleave(function(){
	$(this).find("img.off").show();
	$(this).find("img.on").hide();
});
$("div#top>div#topleft span").mouseover(function(){
	$(this).css("margin-top","0");
	
});
$("div#top>div#topleft span").mouseleave(function(){
	$(this).css("margin-top","7px");
	
});
</script>
<style>
#toolbar {
	padding:2px;
	display:block;
}
.mymenu, .ui-menu {
	font-size:small;
}
.ui-menu {
position:absolute;
}
</style>
<script>
$(function() {
	$("#homeMenu").button({
		icons: {
			secondary: "ui-icon-home",
			primary: "ui-icon-arrowthick-1-e"
		}
	});
	$("#homeMenu").click(function(){ document.location='<?php echo SiteRoot;?>';});
	$("#archiveMenu").button({
		icons: {
			secondary: "ui-icon-folder-collapsed",
				primary: "ui-icon-triangle-1-s"
		}
	});
	$("#cotagMenu").button({
		icons: {
			secondary: "ui-icon-grip-dotted-vertical",
			primary: "ui-icon-triangle-1-s"
		}
	});
	$("#reviewMenu").button({
		icons: {
			secondary: "ui-icon-search",
			primary: "ui-icon-triangle-1-s"
		}
	});
	$("#correspondenceMenu").button({
		icons: {
			secondary: "ui-icon-note",
			primary: "ui-icon-triangle-1-s"
		}
	});
	$("#staticArchiveMenu").button({
		icons: {
			secondary: "ui-icon-trash",
			primary: "ui-icon-triangle-1-s"
		}
	});
	$("#ManagerMenu").button({
		icons: {
			secondary: "ui-icon-star",
			primary: "ui-icon-triangle-1-s"
		}
	});
	$("#reportMenu").button({
		icons: {
			secondary: "ui-icon-gear",
			primary: "ui-icon-triangle-1-s"
		}
	});
	$("#helpMenu").button({
		icons: {
			secondary: "ui-icon-help",
			primary: "ui-icon-triangle-1-s"
		}
	});
	$("#extraMenu").button({
		icons: {
			secondary: "ui-icon-locked",
			primary: "ui-icon-triangle-1-s"
		}
	});
	$(".menu").menu({
		select: function(event, ui) {
			$(this).hide();
			return true;
		}
	}).popup();
//	$("#repeat").buttonset();
});
</script>
<div id='smenuBar'>
<span id="toolbar" class="ui-widget-header ui-corner-all">	
	<span id='repeat'>
<?php if (j::Check("CotagBook")) :?>
	<button id='cotagMenu' class="mymenu" >دفتر کوتاژ</button>
	<ul class='menu' style="display: none;">
		
		<li>
			<a href='/cotag/new'>
				<span class='ui-icon-circle-arrow-s ui-icon'></span>
				 دریافت اظهارنامه
			</a>
		</li>
		<li>
			<a href="/cotag/transfer/toarchive">
			<!-- <a href='/cotag/deliver'> -->
				<span class='ui-icon-arrowthick-1-w ui-icon'></span>
				تحویل به بازبینی
			</a>
		</li>
		<li>
			<a href='/cotag/print'>
				<span class='ui-icon-print ui-icon'></span>
				چاپ بارکد 
			</a>
		</li>
		<li>
			<a href='/cotag/cancel'>
				<span class='ui-icon-close ui-icon'></span>
				لغو وصول 
			</a>
		</li>
	</ul>
	
	
<?php endif;?>
<?php if (j::Check("Archive")) :?>
	<button id='archiveMenu' class="mymenu" > بایگانی بازبینی</button>
	<ul class='menu' style="display: none;">
	<li>
		<li>
			<a href='/archive/transfer/fromcotag' >
				<span class='ui-icon-grip-dotted-vertical ui-icon'></span> 
				 وصول از دفتر کوتاژ 
			</a>
		</li>
	</li>
	<li>
		<a href='#'>
			<span class='ui-icon-person ui-icon'></span> 
			تعیین کارشناس
		</a>
		<ul class='menu'>
			<li>
				<a href='/archive/assign/single'>
					<span class='ui-icon-bullet ui-icon'></span> 
					تخصیص تکی
				</a>
			</li>
			<li>
				<a href='/archive/assign/range'>
					<span class='ui-icon-carat-2-e-w ui-icon'></span> 
					تخصیص بازه ای
				</a>
			</li>
			<li>
				<a href='/archive/assign/group'>
					<span class='ui-icon-shuffle ui-icon'></span> 
					تخصیص گروهی
				</a>
			</li>
			
		</ul>
	</li>
		<li>
			<a href='/report/assignedrange'>
				<span class='ui-icon-search ui-icon'></span> 
				مشاهده کارشناس اظهارنامه
			</a>
		</li>
	<li>
		<a href='#'>
			<span class='ui-icon-arrowthick-1-n ui-icon'></span> 
			ارسال اظهارنامه
		</a>
		<ul class='menu'>
			<li>

				<a href='/archive/transfer/toraked'>
							<span class='ui-icon-trash ui-icon'></span> 
							به بایگانی راکد
				</a>
			</li>
			<li>
					<a href='/archive/transfer/toout?Taraf=rajaie'>
				<!--  <a href='/archive/send?to=rajaie&from=Archive'> -->
							<span class='ui-icon-extlink ui-icon'></span> 
							سایر دوائر گمرگ رجایی
				</a>
			</li>
			<li>
					<a href='/archive/transfer/toout?Taraf=iran'>
				<!-- <a href='/archive/send?to=iran&from=Archive'> -->
							<span class='ui-icon-flag ui-icon'></span> 
							گمرک ایران
				</a>
			</li>
			<li>
				<a href='/archive/transfer/toout?Taraf=othergates'>
				<!-- <a href='/archive/send?to=othergates&from=Archive'>-->
							<span class='ui-icon-arrow-4 ui-icon'></span> 
							سایر گمرکات اجرایی
				</a>
			</li>
			<li>
					<a href='/archive/transfer/toout?Taraf=other'>
				<!-- <a href='/archive/send?to=other&from=Archive'> -->
							<span class='ui-icon-gripsmall-diagonal-se ui-icon'></span> 
							سایر
				</a>
			</li>
		</ul>
	</li>
	<li>
		<a href='#'>
			<span class='ui-icon-arrowthick-1-n ui-icon'></span> 
			دریافت اظهارنامه
		</a>
		<ul class='menu'>
			<li>

				<a href='/archive/transfer/fromraked'>
							<span class='ui-icon-trash ui-icon'></span> 
							از بایگانی راکد
				</a>
			</li>
			<li>
					<a href='/archive/transfer/fromout?Taraf=rajaie'>
				<!--  <a href='/archive/send?to=rajaie&from=Archive'> -->
							<span class='ui-icon-extlink ui-icon'></span> 
							سایر دوائر گمرگ رجایی
				</a>
			</li>
			<li>
					<a href='/archive/transfer/fromout?Taraf=iran'>
				<!-- <a href='/archive/send?to=iran&from=Archive'> -->
							<span class='ui-icon-flag ui-icon'></span> 
							گمرک ایران
				</a>
			</li>
			<li>
				<a href='/archive/transfer/fromout?Taraf=othergates'>
				<!-- <a href='/archive/send?to=othergates&from=Archive'>-->
							<span class='ui-icon-arrow-4 ui-icon'></span> 
							سایر گمرکات اجرایی
				</a>
			</li>
			<li>
					<a href='/archive/transfer/fromout?Taraf=other'>
				<!-- <a href='/archive/send?to=other&from=Archive'> -->
							<span class='ui-icon-gripsmall-diagonal-se ui-icon'></span> 
							سایر
				</a>
			</li>
		</ul>
	</li>
	
	<li>
		<a href='/cotag/barcode'>
					<span class='ui-icon-print ui-icon'></span> 
					چاپ بارکد
		</a>
	</li>
	</ul>
<?php endif;?>
<?php if (j::Check("Raked")) :?>
	<button class='mymenu' id='staticArchiveMenu'>بایگانی راکد</button>
	<ul class='menu' style='width:160px;display: none;'>
			<li>
				<a href='#'>
					<span class='ui-icon-plusthick ui-icon'></span> 
					دریافت 
				</a>
				<ul class='menu'>
					<li>
						<a href='/raked/transfer/fromarchive'>
									<span class='ui-icon-plusthick ui-icon'></span> 
									از بازبینی
						</a>
					</li>
					<li>
						<a href='/raked/transfer/fromout?Taraf=rajaie'>
									<span class='ui-icon-extlink ui-icon'></span> 
									سایر دوائر گمرگ رجایی
						</a>
					</li>
					<li>
						<a href='/raked/transfer/fromout?Taraf=iran'>
									<span class='ui-icon-flag ui-icon'></span> 
									گمرک ایران
						</a>
					</li>
					<li>
						<a href='/raked/transfer/fromout?Taraf=othergates'>
									<span class='ui-icon-arrow-4 ui-icon'></span> 
									سایر گمرکات اجرایی
						</a>
					</li>
					<li>
						<a href='/raked/transfer/fromout?Taraf=other'>
									<span class='ui-icon-gripsmall-diagonal-se ui-icon'></span> 
									سایر
						</a>
					</li>
				</ul>
			</li>
			<li>
		<a href='#'>
			<span class='ui-icon-arrowthick-1-n ui-icon'></span> 
			ارسال اظهارنامه
		</a>
		<ul class='menu'>
			
			<li>
				<a href='/raked/transfer/toout?Taraf=rajaie'>
							<span class='ui-icon-extlink ui-icon'></span> 
							سایر دوائر گمرگ رجایی
				</a>
			</li>
			<li>
				<a href='/raked/transfer/toout?Taraf=iran'>
							<span class='ui-icon-flag ui-icon'></span> 
							گمرک ایران
				</a>
			</li>
			<li>
				<a href='/raked/transfer/toout?Taraf=othergates'>
							<span class='ui-icon-arrow-4 ui-icon'></span> 
							سایر گمرکات اجرایی
				</a>
			</li>
			<li>
				<a href='/raked/transfer/toout?Taraf=other'>
							<span class='ui-icon-gripsmall-diagonal-se ui-icon'></span> 
							سایر
				</a>
			</li>
		</ul>
	</li>
	</ul>
<?php endif;?>
<?php if (j::Check("Reviewer")) :?>
	<button class='mymenu' id='reviewMenu'>بازبینی</button>
	<ul class='menu' style='width:150px;display: none;'>
		<li>
			<a href='/review/select'>
				<span class='ui-icon-circle-check ui-icon'></span>
				کارشناسی اظهارنامه
			</a>
		</li>
		<li>
			<a href='/review/editselect'>
				<span class='ui-icon-pencil ui-icon'></span>
				ویرایش کارشناسی اظهارنامه
			</a>
		</li>
		<li>
			<a href='/review/dossier/select'>
				<span class='ui-icon-circle-check ui-icon'></span>
				کارشناسی پرونده
			</a>
		</li>
		<li>
			<a href='/review/sayok'>
				<span class='ui-icon-circle-check ui-icon'></span>
				بدون مشکل گروهی
			</a>
		</li>
		<li>
			<a href='/review/dossier/digital'>
				<span class='ui-icon-circle-check ui-icon'></span>
				پرونده دیجیتال
			</a>
		</li>
	</ul>
<?php endif;?>
<?php if (j::Check("Correspondence")) :?>
	<button style="" class='mymenu' id='correspondenceMenu'>مکاتبات</button>
	<ul class='menu' style="display: none;">
		<li>
			<a href='/correspondence/main'>
				<span class='ui-icon-image ui-icon'></span> 
				ثبت فرآیندهای پرونده
			</a>
		</li>
		<li>
			<a href='/correspondence/blacklist'>
				<span class='ui-icon-image ui-icon'></span> 
				لیست مشمولین ماده ۱۴
			</a>
		</li>  
		
		
	</ul>
<?php endif;?>
<?php if (j::Check("MasterHand")) :?>
	<button style="" class='mymenu' id='ManagerMenu'>مدیریت</button>
	<ul class='menu' style="display: none;">
		<li>
			<a href='/manager/topic'>
				<span class='ui-icon-bookmark ui-icon'></span> 
				بانک عناوین
			</a>
		</li>
		<li>
			<a href='/manager/bazbins/list'>
				<span class='ui-icon-person ui-icon'></span> 
				کاربران
			</a>
		</li>
		<li>
			<a href='/manager/correction'>
				<span class='ui-icon-pin-s ui-icon'></span> 
				 اصلاح کوتاژ
			</a>
		</li>
		<li>
			<a href='/manager/confirm/select'>
				<span class='ui-icon-check ui-icon'></span> 
				تایید کارشناسی
			</a>
		</li>
		<li>
			<a href='/manager/bazbins/singlereassign'>
				<span class='ui-icon-link ui-icon'></span> 
				 مدیریت تخصیص
			</a>
		</li>
		<li>
			<a href='/manager/cancel'>
				<span class='ui-icon-close ui-icon'></span> 
				لغو وصول اظهارنامه
			</a>
		</li>
		<li>
			<a href='/manager/remove'>
				<span class='ui-icon-close ui-icon'></span> 
				حذف فرایند
			</a>
		</li>
		<li>
			<a href='/config/alarm'>
				<span class='ui-icon-close ui-icon'></span> 
				تنظیمات یادآور
			</a>
		</li>
		<li>
			<a href='/config/main'>
				<span class='ui-icon-close ui-icon'></span> 
				تنظیمات بازبینی
			</a>
		</li>
	</ul>
<?php endif;?>
	<button class='mymenu' id='reportMenu'>گزارش‌ها</button>

	<ul class='menu' style='width:160px;display: none;'>
<?php if ( j::Check("CotagList")) :?>
		<li>
			<a href='/report/list'>
				<span class='ui-icon-calculator ui-icon'></span>
				 لیست کوتاژها
			</a>
		</li>
<?php endif;?>

<?php if ( j::Check("ProgressList")) :?>
		<li>
			<a href='/report/progresslist'>
				<span class='ui-icon-refresh ui-icon'></span> 
				گردش‌کاری اظهارنامه
			</a>
		</li>
<?php endif;?>
<?php if ( j::Check("AssignedList")) :?>
		<li>
			<a href='/report/assignedrange'>
				<span class='ui-icon-link ui-icon'></span> 
				مشاهده تخصیص ها
			</a>
		</li>
<?php endif;?>
<?php if ( j::Check("NotReceivedInCotagBook")) :?>
		<li>
			<a href='/report/notrecieved'>
				<span class='ui-icon-grip-dotted-vertical ui-icon'></span>
				وصول نشده های دفترکوتاژ
			</a>
		</li>
<?php endif;?>
<?php if ( j::Check("NotArchivedList")) :?>
		<li>
			<a href='/report/notarchived'>
				<span class='ui-icon-folder-collapsed ui-icon'></span>
				وصول نشده های بایگانی
			</a>
		</li>
<?php endif;?>
<?php if ( j::Check("AssignableList")) :?>
		<li>
			<a href='/report/assignable'>
				<span class='ui-icon-check ui-icon'></span> 
				کوتاژهای قابل تخصیص
			</a>
		</li>
<?php endif;?>
<?php if ( j::Check("ExitInfo")) :?>
<li>
			<a href='/report/cotaginfo'>
				<span class='ui-icon-disk ui-icon'></span> 
				گزارش درب خروج
			</a>
		</li>
<?php endif;?>
<?php if ( j::Check("TypistHelp")) :?>
		<li>
			<a href='/report/typisthelp'>
				<span class='ui-icon-disk ui-icon'></span> 
				تایپیست یار
			</a>
		</li>
<?php endif;?>
	</ul>
	
	<button class='mymenu' id='helpMenu'>راهنما</button>
	<ul class='menu' style='width:160px;display: none;'>
		<li>
			<a href='/help/'>
				<span class='ui-icon-help ui-icon'></span>
				کلیات راهنما
			</a>
		</li>
		<li>
			<a href='/help/deploy'>
				<span class='ui-icon-cart ui-icon'></span>
				نصب و راه‌اندازی برنامه
			</a>
		</li>
	</ul>


	</span>
<?php if (j::UserID()&&$Me=ORM::find(new MyUser,j::UserID())) :?>
	<div style='text-align:left;float:left;'>
	<button class='mymenu' id='extraMenu'><?php
	echo htmlspecialchars($Me->LastName());
	?></button>
	<ul class='menu'>
<?php if (j::Check("CreateUser")) :?>
		<li>
			<a href='#'>
				<span class='ui-icon-person ui-icon'></span> 
				کاربران
			</a>
			<ul>
			<li>
				<a href='/user/create'>
					<span class='ui-icon-plus ui-icon'></span> 
					کاربر جدید
				</a>
			</li>
			<li>
				<a href='/sys/main'>
					<span class='ui-icon-minus ui-icon'></span> 
					ویرایش کاربر
				</a>
			</li>
			<li>
				<a href='/sys/main'>
					<span class='ui-icon-close ui-icon'></span> 
					حذف کاربر
				</a>
			</li>
			
			</ul>
		</li>
<?php endif;?>
	<li>
		<a href='#'>
			<span class='ui-icon-person ui-icon'></span> 
			پیام ها و یادآورها
		</a>
		<ul>
		<li>
			<a href='/main'>
				<span class='ui-icon-plus ui-icon'></span> 
				یادآورها
			</a>
		</li>
		<li>
			<a href='/alarm'>
				<span class='ui-icon-minus ui-icon'></span> 
				یادآوردستی
			</a>
		</li>
		
		</ul>
	</li>
		<li>
			<a href='/user/pass'>
				<span class='ui-icon-key ui-icon'></span> 
				تغییر رمز عبور
			</a>
		</li>
		<li>
			<a href='/user/logout'>
				<span class='ui-icon-power ui-icon'></span> 
				خروج از سیستم
			</a>
		</li>
		<li>
			<a href='/about'>
				<span class='ui-icon-info ui-icon'></span> 
				درباره سیستم
			</a>
		</li>
		</li>
			<li>
			<a href='/user/setting'>
				<span class='ui-icon-wrench ui-icon'></span> 
				تنظیمات
			</a>
		</li>
	</ul>
	</div>
<?php endif;?>
</span>
</div><!-- End menubar -->