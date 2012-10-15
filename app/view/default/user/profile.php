<?php
?>
<h1><img src="/img/h/h1-add-user-50.png"/>
پروفایل من </h1>
<?php echo $this->User->getFullName()?>
<table>
	<tr>
		<td>نام کاربری:</td><td><?php echo $this->User->Username()?></td>
	</tr><tr>
		<td>شماره دانشجویی:</td><td><?php echo $this->User->Codemelli()?></td>
	</tr>
</table>
<?php 
	ViewResultPlugin::Show($this->Result,$this->Error);
	?>

<style>
<?php $this->Autoform->PresentCSS();?>
</style>

<?php $this->Autoform->PresentHTML();?>
<script>
<?php $this->Autoform->PresentScript();?>
</script>