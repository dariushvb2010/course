<?php
?>
<script>
</script>
<style>
label { 
	width:100px;
	float:right;
}
input[type="text"],input[type="password"] {
	width:150px;
	direction:ltr;
	text-align:center;
	margin:5px;	
}

#remember_container {
	text-align:center;
}
#login_container {
	margin:auto;
	text-align:center;
	width:300px;
	
}
form {
	border: 2px double;
	width: 600px;
	padding:10px;
	margin:auto;
	}
<?php if($this->Form)$this->Form->PresentCSS();?>
</style>
<h1><img src="/img/h/h1-settings-50.png"/>
تنظیمات</h1>
<?php 
if (isset($this->Result))
	ViewResultPlugin::Show($this->Result,$this->Error);

if(j::Check("MasterHand")){
	if($this->Form)$this->Form->PresentHTML();
 }
 ?>
 <script>
<?php if($this->Form)$this->Form->PresentScript();?>
 </script>

