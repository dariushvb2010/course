<style>
form {
	width:60%;
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
	text-align:center;
}
<?php $this->Form->PresentCSS();?>
</style>
<link rel="stylesheet" href="/style/alarm.css" />
<h1><img src="/img/h/h1-alarmfree-50.png"/>
 ایجاد یادآور دستی
</h1>

<div>
<?php //$this->List->Present();?>
</div>
<div>
	<?php 
	if (isset($this->Result))
	{ 		
		ViewResultPlugin::Show($this->Result,$this->Error);
		if($this->Alarm)
		ViewAlarmPlugin::SingleShow($this->Alarm);
			
	}
	 ?> 
	 <a href='/help/main#AlarmFree'>
		<img src='/img/web/icon/help32.png' style='border:0px solid gray; float:left;' />
	 </a>
	<?php 	
	 $this->Form->PresentHTML();
?>
	
</div>

<script src="/script/editlist.js"></script>
<script>
<?php  $this->Form->PresentScript(); ?>
<?php ViewAlarmPlugin::PresentScritp();?>
</script>
