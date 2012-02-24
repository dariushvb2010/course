<?php
?>
<style>

form {
	width:500px;
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
}

<?php $this->Form->PresentCSS();?>

</style>

<h1>ارسال اظهارنامه</h1>



<?php if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);
?>
<?php $this->Form->PresentHTML();?>
<script>
<?php $this->Form->PresentScript();?>
</script>


