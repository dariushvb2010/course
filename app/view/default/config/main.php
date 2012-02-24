<style>
<?php $this->Form->PresentCSS(); ?>
<?php $this->List->PresentCSS(); ?>
</style>
<h1><img src="/img/h/h1-configmain-50.png"/>
	تنظیمات بازبینی
</h1>


<div>

	 <?php if (isset($this->Result))
ViewResultPlugin::Show($this->Result,$this->Error);
	 $this->Form->PresentHTML();
?>
	
</div>
<div>
<?php if($this->List)$this->List->Present();?>
</div>
<script ><?php $this->List->PresentScript();?></script>
<script ><?php $this->Form->PresentScript();?></script>