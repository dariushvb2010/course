<?php
?>
<h1><img src="/img/h/h1-add-user-50.png"/>
رمز جدید کاربر</h1>

<?php 
	ViewResultPlugin::Show($this->Result,$this->Error);
?>

<style>
#newpass{
	width:100px;
	height:50px;
	background-color:buttonshadow;
	text-align: center;
	margin:auto;
}
#npform{
	border: 3px double;
    margin: auto;
    padding: 10px;
    text-align: center;
    width: 60%;
}
</style>

<form id='npform' method="post">
<?php if(isset($this->NewPass)){ ?>
<div id="newpass">
	رمز عبور جدید: <?php echo $this->NewPass;?>
</div>
<?php } ?>
<input type="submit" name="submit" value="ایجاد رمز جدید"> 
</form>