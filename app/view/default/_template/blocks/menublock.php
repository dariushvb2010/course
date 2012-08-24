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
<?php if (j::Check("Scan")&&0) :?>
	<button id='scanMenu' class="mymenu" >اسکن</button>
	<ul class='menu' style="display: none;">
		
		<li>
			<a href='/scan/'>
				<span class='ui-icon-circle-arrow-s ui-icon'></span>
				 اسکن اظهارنامه
			</a>
		</li>
		
		<li>
			<a href='/scan/add'>
				<span class='ui-icon-circle-arrow-s ui-icon'></span>
				 اضافه کردن سند
			</a>
		</li>
		
		<li>
			<a href='/review/dossier/digital'>
				<span class='ui-icon-close ui-icon'></span>
				مشاهده اظهارنامه دیجیتال 
			</a>
		</li>
		
		<li>
			<a href='/scan/cancel'>
				<span class='ui-icon-close ui-icon'></span>
				لغو وصول 
			</a>
		</li>
		
	</ul>
	
	
<?php endif;?>
<?php if (j::Check("CotagBook")) :?>
	<button id='cotagMenu' class="mymenu" >ورودی</button>
	<ul class='menu' style="display: none;">
		
		<li>
			<a href='/cotag/start'>
				<span class='ui-icon-circle-arrow-s ui-icon'></span>
				 ورود اظهارنامه
			</a>
		</li>
		<li>
			<a href="/cotag/transfer/toarchive">
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
				لغو ورود اظهارنامه 
			</a>
		</li>
		<li>
			<a href='/cotag/untiebtal'>
				<span class='ui-icon-close ui-icon'></span>
				وصول مجدد ابطالی 
			</a>
		</li>
	</ul>
	
	
<?php endif;?>
<?php if (j::Check("Archive")) :?>
	<button id='archiveMenu' class="mymenu" > بایگانی </button>
	<ul class='menu' style="display: none;">
		<li>
			<a href='/archive/start'>
				<span class='ui-icon-circle-arrow-s ui-icon'></span>
				 ورود اظهارنامه
			</a>
		</li>
		<li>
			<a href='/archive/transfer/fromcotag' >
				<span class='ui-icon-grip-dotted-vertical ui-icon'></span> 
				 وصول از دفتر کوتاژ 
			</a>
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
				<a href='/archive/assign/group'>
					<span class='ui-icon-shuffle ui-icon'></span> 
					تخصیص گروهی
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

				<a href='/archive/transfer/toraked'>
							<span class='ui-icon-trash ui-icon'></span> 
							به بایگانی راکد
				</a>
			</li>
			<li>
					<a href='/archive/transfer/toout?Taraf=mygate'>
				<!--  <a href='/archive/send?to=mygate&from=Archive'> -->
							<span class='ui-icon-extlink ui-icon'></span> 
							سایر دوائر گمرگ <?php echo GateName;?>
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
					<a href='/archive/transfer/fromout?Taraf=mygate'>
				<!--  <a href='/archive/send?to=mygate&from=Archive'> -->
							<span class='ui-icon-extlink ui-icon'></span> 
							سایر دوائر گمرگ <?php echo GateName;?>
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
	<button class='mymenu' id='staticArchiveMenu'>راکد</button>
	<ul class='menu' style='width:160px;display: none;'>
		<li>
			<a href='/raked/start'>
				<span class='ui-icon-circle-arrow-s ui-icon'></span>
				 ورود اظهارنامه
			</a>
		</li>
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
					<a href='/raked/transfer/fromout?Taraf=mygate'>
								<span class='ui-icon-extlink ui-icon'></span> 
								سایر دوائر گمرگ <?php echo GateName;?>
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
				<a href='/raked/transfer/toout?Taraf=mygate'>
							<span class='ui-icon-extlink ui-icon'></span> 
							سایر دوائر گمرگ <?php echo GateName;?>
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
	<button class='mymenu' id='reviewMenu'>کارشناسی</button>
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
			<a href='/correspondence/start'>
				<span class='ui-icon-image ui-icon'></span> 
				ورود اظهارنامه
			</a>
		</li>
		<li>
			<a href='/correspondence/addprocess'>
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
<?php if (j::Check("Typist")) :?>
	<button style="" class='mymenu' id='TypistMenu'>تایپیست</button>
	<ul class='menu' style="display: none;">
		<li>
			<a href='#'>
				<span class='ui-icon-plusthick ui-icon'></span> 
				نگارش 
			</a>
			<ul class='menu'>
				<li>
					<a href='/typist/type/list'>
						<span class='ui-icon-image ui-icon'></span> 
						صف نگارش
					</a>
				</li>
				
				<li>
					<a href='/typist/type/edit'>
						<span class='ui-icon-image ui-icon'></span> 
						ویرایش نگارش
					</a>
				</li>
			</ul>
		</li>

		<li>
			<a href='#'>
				<span class='ui-icon-plusthick ui-icon'></span> 
				قالب نگارش 
			</a>
			<ul class='menu'>
				<li>
					<a href='/typist/template/list'>
						<span class='ui-icon-image ui-icon'></span> 
						لیست قالب ها
					</a>
				</li>
				
				<li>
					<a href='/typist/template/edit'>
						<span class='ui-icon-image ui-icon'></span> 
						ویرایش قالب
					</a>
				</li>
			</ul>
		</li>
		
	</ul>
<?php endif;?>
<?php if (j::Check("MasterHand")) :?>
	<button style="" class='mymenu' id='ManagerMenu'>مدیر</button>
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
			<a href='/manager/ebtal'>
				<span class='ui-icon-close ui-icon'></span> 
				ابطال اظهارنامه
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
	<button class='mymenu' id='reportMenu'>گزارش‌</button>

	<ul class='menu' style='width:160px;display: none;'>
		<li>
			<a href='#'>
				<span class='ui-icon-plusthick ui-icon'></span> 
				لیست کوتاژها 
			</a>
			<ul class='menu'>
				<?php if ( j::Check("CotagList")) :?>
				<li>
					<a href='/report/lists/list'>
						<span class='ui-icon-calculator ui-icon'></span>
						 لیست کوتاژ گمرک <?php echo GateName;?>
					</a>
				</li>
				<?php endif;?>
				
				<?php if ( j::Check("CotagList")) :?>
				<li>
					<a href='/report/lists/list/^Other'>
						<span class='ui-icon-calculator ui-icon'></span>
						 لیست کوتاژ ورودی گمرکات
					</a>
				</li>
				<?php endif;?>
				
				<?php if ( j::Check("NotReceivedInCotagBook")) :?>
				<li>
					<a href='/report/lists/notreceived'>
						<span class='ui-icon-grip-dotted-vertical ui-icon'></span>
						وصول نشده های دفترکوتاژ
					</a>
				</li>
				<?php endif;?>
				
				<?php if ( j::Check("AssignableList")) :?>
				<li>
					<a href='/report/lists/list/^Assignable'>
						<span class='ui-icon-check ui-icon'></span> 
						کوتاژهای قابل تخصیص
					</a>
				</li>
				<?php endif;?>
				<?php if ( j::Check("NotArchivedList")) :?>
				<li>
					<a href='/report/lists/list/^NotArchived'>
						<span class='ui-icon-folder-collapsed ui-icon'></span>
						وصول نشده های بایگانی
					</a>
				</li>
				<?php endif;?>
				<?php if ( j::Check("NotArchivedList")) :?>
				<li>
					<a href='/report/lists/list/^Moshkeldar'>
						<span class='ui-icon-folder-collapsed ui-icon'></span>
						اظهارنامه های مکاتباتی بدون کلاسه
					</a>
				</li>
				<?php endif;?>
			</ul>
		</li>
<?php if ( j::Check("ProgressList")) :?>
		<li>
			<a href='/report/progresslist/file'>
				<span class='ui-icon-refresh ui-icon'></span> 
				گردش‌کاری اظهارنامه
			</a>
		</li>
<?php endif;?>
<?php if (1==1) :?>
		<li>
			<a href='/report/charts/'>
				<span class='ui-icon-refresh ui-icon'></span> 
				نمودارها
			</a>
		</li>
<?php endif;?>
<?php if (1==1) :?>
		<li>
			<a href='/report/statistics/'>
				<span class='ui-icon-refresh ui-icon'></span> 
				آمار
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
		<?php if (j::UserID()>0) :?>
		<li>
			<a href='/report/progresslist/user'>
				<span class='ui-icon-refresh ui-icon'></span> 
				فعالیت های ثبت شده توسط شما
			</a>
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
	