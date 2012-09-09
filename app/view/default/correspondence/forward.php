<style>
<?php if($this->Form)
		$this->Form->PresentCSS();
?>
</style>
<h1>
ارسال به دفاتر ستادی
</h1>
<?php
ViewResultPlugin::Show($this->Result, $this->Error);
if($this->Form)
	$this->Form->PresentHTML();

?>
<script>
function redirect(){
	window.location = 'http://localhost/baz/correspondence/addprocess?Cotag=<?php echo $this->Cotag;?>';
} 
<?php if($this->IfRedirect):?>
	setTimeout(redirect,3000);
<?php endif;?>
</script>
