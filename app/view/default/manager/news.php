<style>
<?php 
$this->Form->PresentCSS();
$this->List->PresentCSS();
?>
</style>
<h1>
مدیریت اخبار
</h1>
<?php
ViewResultPlugin::Show($this->Result, $this->Error);
$this->Form->PresentHTML();
$this->List->Present();

?>
<script>
<?php 
$this->Form->PresentScript();
$this->List->PresentScript();
?>
</script>