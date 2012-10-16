<style>
	.mymenu, .ui-menu {
		font-size:small;
	}
	.ui-menu {
		position:absolute;
	}
</style>

<div id='smenuBar'>
<span id="toolbar" class="ui-widget-header ui-corner-all">	
	<span id='repeat'>

<?php if (j::Check("Raked")) :?>
	<button class='mymenu' id='staticArchiveMenu'>منوی تستی</button>
	<ul class='menu' style='width:160px;display: none;'>
		<li>
			<a href='/raked/start'>
				<span class='ui-icon-circle-arrow-s ui-icon'></span>
				 منوی۱
			</a>
		</li>
		<li>
			<a href='#'>
				<span class='ui-icon-plusthick ui-icon'></span> 
				دارای زیرمنو 
			</a>
			<ul class='menu'>
				<li>
					<a href='/raked/transfer/fromarchive'>
								<span class='ui-icon-plusthick ui-icon'></span> 
								زیرمنوی۱
					</a>
				</li>
				<li>
					<a href='/raked/transfer/fromout?Taraf=mygate'>
								<span class='ui-icon-extlink ui-icon'></span> 
								زیرمنوی۲
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
	</ul>
<?php endif;?>

	<button class='mymenu' id='serviceMenu'>خدمات</button>
	<ul class='menu' style='width:160px;display: none;'>
		<li>
			<a href='/user/login'>
				<span class='ui-icon-cart ui-icon'></span>
				ورود به سیستم
			</a>
		</li>
		<li>
			<a href='/user/create'>
				<span class='ui-icon-help ui-icon'></span>
				ثبت نام
			</a>
		</li>
		<li>
			<a href='/report/poll'>
				<span class='ui-icon-cart ui-icon'></span>
				آمار ثبت نام
			</a>
		</li>
		<li>
			<a href='/report/news'>
				<span class='ui-icon-cart ui-icon'></span>
				اخبار
			</a>
		</li>
	</ul>
	<button class='mymenu' id='managerMenu'>مدیریت</button>
	<ul class='menu' style='width:160px;display: none;'>
		<li>
			<a href='/report/users'>
				<span class='ui-icon-help ui-icon'></span>
				کاربران
			</a>
		</li>
	</ul>
	<button class='mymenu' id='helpMenu'>راهنما</button>
	<ul class='menu' style='width:160px;display: none;'>
		<li>
			<a href='/help/'>
				<span class='ui-icon-help ui-icon'></span>
				کلیات راهنما
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
				<a href='/user/list'>
					<span class='ui-icon-minus ui-icon'></span> 
					لیست کاربران
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
			<a href='/user/profile'>
				<span class='ui-icon-info ui-icon'></span> 
				پروفایل من
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
	