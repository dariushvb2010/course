<style>
#toolbar {
			padding:2px;
			display:block;
		}
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