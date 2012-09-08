<style>
<?php if($this->Form)
		$this->Form->PresentCSS();
?>
</style>
<h1>
کارشناسی پرونده
</h1>
<?php
ViewResultPlugin::Show($this->Result, $this->Error);
if($this->Form)
	$this->Form->PresentHTML();

?>
<script type="text/javascript">
<!--
<?php if($this->Form)
	$this->Form->PresentScript();
	?>
//-->
</script>
