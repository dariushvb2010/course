<style>
<?php if($this->Form)
		$this->Form->PresentCSS();
?>
</style>
<h1>
ماده هفت
</h1>
<?php
ViewResultPlugin::Show($this->Result, $this->Error);
if($this->Form)
	$this->Form->PresentHTML();

?>
<script>
function redirect(){
	window.location = '<?php echo SiteRoot;?>/correspondence/addprocess?Cotag=<?php echo $this->Cotag;?>';
} 
<?php if($this->IfRedirect):?>
	setTimeout(redirect,3000);
<?php endif;?>
</script>
