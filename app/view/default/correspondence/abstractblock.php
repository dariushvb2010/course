<?php 
/**
 * =================================================================
 * a block used in the many views in  (correspondence) folder
 * and can see:
 * $this->File
 * $this->Cotag
 * $this->abs_h1_title
 */



?>
<style>
<?php if($this->Form)
		$this->Form->PresentCSS();
?>
</style>
<h1>
	<a href="addprocess?Cotag=<?php echo $this->Cotag;?>"> <img style="height: 30px; float:left; margin:7px 7px 0 12px;" src="/img/mail/back.png" title="برگشت به صفحه اصلی"/></a>
	<?php echo $this->abs_h1_title;?>
</h1>
<?php include dirname(dirname(__File__)).'/report/blocks/fileinfobox.php'; ?>
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
