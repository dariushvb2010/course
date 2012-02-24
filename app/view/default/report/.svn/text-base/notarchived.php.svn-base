<?php
?>
<style>
</style>
<h1>بایگانی: لیست کوتاژهای وصول نشده از دفتر کوتاژ</h1>
<?php if (isset($this->Result))
ViewResultPlugin::Show($this->Result,$this->Error);
?>
<?php if (count($this->NAF)) { ?> 
	<?php $this->NotArchivedList->PresentSortboxForPrint(); ?>
	<form method='post'>
		<?php //$this->NotArchivedList->Present(); 
				$this->NotArchivedList->PresentForPrint();?>
	</form>
<?php } ?>
