<?php
?>
<style>
</style>
<h1><?php echo $this->HeadTitle;?></h1>
<?php if (isset($this->Result))
ViewResultPlugin::Show($this->Result,$this->Error);
?>
<?php if ($this->RecordCount) { ?>
<p>
تعداد کل :
<strong><?php echo $this->RecordCount;?></strong> 
 </p> 
	<?php $this->AutoList->PresentSortbox(); ?> 
	<form method='post'>
		<?php $this->AutoList->PresentForPrint(); ?>
	</form>
<?php } ?>
