<?php
?>
<h1><img src="/img/h/h1-add-user-50.png"/>
ثبت نام </h1>

<?php 
	ViewResultPlugin::Show($this->Result,$this->Error);
	?>

<style>
<?php $this->Autoform->PresentCSS();?>
</style>
<div style="text-align: center">نام کاربری و رمز عبور خود را به خاطر داشته باشید</div>
<?php $this->Autoform->PresentHTML();?>
<div style="text-align: center">زمان کلاس بین یک ساعت تا یک و نیم ساعت می باشد</div>
<script>
<?php $this->Autoform->PresentScript();?>
</script>