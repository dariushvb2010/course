<?php
?>
<style>

form {
	width:400px;
	margin:auto;
	padding:10px;
	border:3px double;
	text-align:center;
}
form input[type='submit'] {
	width:200px;
	margin:5px;
}
form input[type='text'] {
	width:150px;
	text-align:center;
}
<?php $this->Form->PresentCSS();?>
</style>
<h1>ویرایش بازبینی اظهارنامه</h1>
<p>

</p>
<?php if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);
?>
<?php if (!$this->Result) { ?>
<?php $this->Form->PresentHTML();?>
<script>
<?php $this->Form->PresentScript();?>
</script>
<?php }
else
{?>
<a href='./editselect'>انتخاب اظهارنامه بعدی</a>
<?php }?>

<script>
	
</script>