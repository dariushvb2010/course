<?php
?>
<style>
</style>
<h1>لیست اظهارنامه های تخصیص نیافته</h1>
<?php if (isset($this->Result))
ViewResultPlugin::Show($this->Result,$this->Error);
?>
<?php if (count($this->UnassignedFiles)) { ?> 
	<?php $this->AssignList->PresentSortbox(); ?> 
	<form method='post'>
		<?php $this->AssignList->Present(); ?>
	</form>
<?php } ?>
