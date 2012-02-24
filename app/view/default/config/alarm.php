<style>
<?php $this->Form->PresentCSS();?>
<?php $this->List->PresentCSS();?>
</style>
<?php ViewAlarmPlugin::EchoCSS();?>
<h1><img src="/img/h/h1-configalarm-50.png"/>
تنظیمات یادآور
</h1>

<div>
<?php //$this->List->Present();?>
</div>
<div>
	<?php 
	if (isset($this->Result))
	{ 		
		ViewResultPlugin::Show($this->Result,$this->Error);	
	}
	 	
	 $this->Form->PresentHTML();
	 if (isset($this->ConfigResult))
	 {
	 	ViewResultPlugin::Show($this->ConfigResult,$this->ConfigError);
	 }
	 $this->List->Present();
	 $Data=ORM::Query("ConfigAlarm")->GetAll();
	 ViewAlarmPlugin::GroupShow($Data,"یادآورهای تنظیم شده");
?>
	
</div>

<script src="/script/editlist.js"></script>
<script> 
<?php  $this->Form->PresentScript(); ?>
<?php $this->List->PresentScript();?>
<?php ViewAlarmPlugin::PresentScritp();?>
<?php ?>
</script>