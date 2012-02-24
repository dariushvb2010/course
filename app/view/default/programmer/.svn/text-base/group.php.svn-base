<style>
form {
	width:60%;
	margin:auto;
	padding:10px;
	border:3px double;
	text-align:center;
}
form input[type='submit'] {
	margin:5px;
	width:200px;
}
form input[type='text'] {
	width:150px;
	text-align:center;
}
<?php $this->List->PresentCSS();?>
</style>
<h1><img src="/img/h/h1-add-group-50.png"/>
	گروه کاربری
</h1>
<div>
<?php if (isset($this->UserResult))
ViewResultPlugin::Show($this->UserResult,$this->UserError);
$this->UserForm->PresentHTML(); ?> 
</div>
<div>
<?php if($this->UserList)$this->UserList->Present(); ?>
</div>
<hr/>
<div>
<?php if (isset($this->Result))
ViewResultPlugin::Show($this->Result,$this->Error);
$this->Form->PresentHTML(); ?>
</div>
<div>
	 <?php if($this->List)$this->List->Present(); ?>
</div>


<script >
<?php 
$this->List->PresentScript();
?>
</script>