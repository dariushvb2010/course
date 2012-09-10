<style>
<?php if($this->AutoList)
		$this->AutoList->PresentCSS();
?>
</style>
<h1>
لیست مشمولین ماده ۷
</h1>
<p>مبالغ اختلاف بر حسب هزار ریال می باشد.</p>
<?php
ViewResultPlugin::Show($this->Result, $this->Error);

if($this->AutoList)
	$this->AutoList->PresentSortbox();
if($this->AutoList)
	$this->AutoList->Present();
?>

<script>

</script>
