<div id ="footer" class="bazbox">
	
	<div id='Tracker' >
		<?php
		
		$x=$this->Tracker->PageLoadTime();
		printf("این صفحه در مدت زمان %.4f ثانیه (%.4f پایگاه‌داده, %.4f وب) به کمک %d درخواست پایگاهی ایجاد شده است.", 
		    $x,$this->DB->QueryTime,$x-$this->DB->QueryTime ,$this->DB->QueryCount);
		?>
	</div>
	<div id='datetime' dir='ltr' style="position: absolute; top:8px; left:10px; font-size:11px;">
		<?php
		$c=new CalendarPlugin();
		echo $c->JalaliFullTime(time()); 
		?>
	</div>
	<div id='Copyright'  style="position: absolute; top:5px; right:10px;" >
			<a href='<?php echo constant("SiteRoot") ?>'><?php echo j::Registry("app/title") ?></a>
			نسخه <?php echo reg("app/version");?>
			<br/>
			<?php tr("نیرو گرفته از");?>
			<a href='http://jframework.info/' style="">
				<img title='<?php echo WHOAMI;?>' src="/img/jlogo.png" width=16 height=16  style="
					text-decoration:none;
					outline:none;
					border: 0 solid;
					vertical-align: middle;"/>
			 </a>
	 </div>
	 <div id="sherkat" >
		کلیه حقوق معنوی این نرم افزار متعلق به 
		<?php echo v::b(reg("app/sherkat/title"))?>
		می باشد.
	</div>
 </div>