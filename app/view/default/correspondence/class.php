<?php
?>
<style>
#singleResult {
	width:400px;
	border:3px double gray;
	color:darkgreen;
	font-size:36px;
	font-weight:bold;
	margin:10px auto;
	padding:10px;
	text-align:center;
}
#singleResult span {
	font-size:12px;
}
</style>
<?php

ViewResultPlugin::Show($this->Result,$this->Error);
if ($this->class)
{
	?>
<div id='singleResult'>
<span >شماره کوتاژ :‌ 
<strong>
<?php echo $this->Cotag;?>
</strong>
<br>
</span>
<span>کلاسه : </span>
<?php
	echo $this->class;?>
<br>
<a href="./">بازگشت</a>
</div>
<?php 
}
?>