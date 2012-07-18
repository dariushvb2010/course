<style>
</style>

<h1><img src="/img/h/h1-review-50.png"/>
پرینت مطالبه نامه</h1>

<form method="post">
<div id='unknownfields'>
<?php
if(Count($this->Template)){
	$Fields=$this->Template->GetUnknownFields();
	foreach ($Fields as $v){
?>
<div>
	<?php echo $v?>: <input class="unknownfield" name="UF_<?php echo $v?>">
</div>
<?php 
	}
}
?>
</div>
<input type="hidden" name='ID' value='چاپ'>
<input type="submit" name='print' value='چاپ'>
</form>
