<style>
div#body>div.mainform{border:4px double black; margin: 5px 12px; padding:5px;}
<?php 
if($this->Handler->CreateForm)
	$this->Handler->CreateForm->PresentCSS();
if($this->Handler->MainForm);
	//$this->Handler->MainForm->PresentCSS();

?>
</style>
<?php ViewMailPlugin::EchoCSS();?>
<h1>
بایگانی راکد: دریافت اظهارنامه از بایگانی بازبینی</h1>
<?php
ViewResultPlugin::Show($this->Handler->Result, $this->Handler->Error);
if($this->Handler->CreateForm)
	$this->Handler->CreateForm->PresentHTML();
?>
<!-- --------------------------main form of the mail -->
<div class="mainform" ><?php 
ViewMailPlugin::SingleShow($this->Handler->Mail, "float:left;","Get");
if($this->Handler->MainForm)
	$this->Handler->MainForm->PresentHTML();
?>
</div>
<?php $this->Handler->ShowMails();?>
<script>
<?php 
if($this->Handler->MainForm)
	$this->Handler->MainForm->PresentScript();

?>
</script>