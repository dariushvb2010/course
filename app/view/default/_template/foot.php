<?php
?>
</div> <!-- Body div -->

<div id='Tracker' class='Footer'>
	<?php
	
	$x=$this->Tracker->PageLoadTime();
	printf("این صفحه در مدت زمان %.4f ثانیه (%.4f پایگاه‌داده, %.4f وب) به کمک %d درخواست پایگاهی ایجاد شده است.", 
	    $x,$this->DB->QueryTime,$x-$this->DB->QueryTime ,$this->DB->QueryCount);
	?>
</div>
<div id='datetime' class='Footer' dir='ltr'>
	<?php
	$c=new CalendarPlugin();
	echo $c->JalaliFullTime(time()); 
	?>
</div>
<div id='Copyright' class='Footer'  >
		<a href='<?php echo constant("SiteRoot") ?>'><?php echo j::Registry("app/title") ?></a>
		نسخه <?php echo reg("app/version");?>
		
		<?php tr("نیرو گرفته از");?>
		<a href='http://jframework.info/' style="">
			<img title='<?php echo WHOAMI;?>' src="/img/jlogo.png" width=16 height=16  style="
				text-decoration:none;
				outline:none;
				border: 0 solid;
				vertical-align: middle;"/>
		 </a>
 </div>
<link rel="stylesheet" href="/style/fprint.css" />
</body>
</html>
