<?php
?>
<h1><img src="/img/h/h1-add-user-50.png"/>
تعریف کاربر جدید</h1>

<?php 
// var_dump($this->Result,$this->Error);
	ViewResultPlugin::Show($this->Result,$this->Error);
	?>

<style>
<?php $this->Autoform->PresentCSS();?>
</style>
<?php $this->Autoform->PresentHTML();?>
<script>
<?php $this->Autoform->PresentScript();?>
</script>