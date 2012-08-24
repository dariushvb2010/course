<?php 
	$T1="دریافت اظهارنامه ";
	$T2="دریافت اظهارنامه از ";
	$Taraf=ReviewTopic::GetPersianType($this->Handler->TopicType);
	$Title=(isset($Taraf) ? $T2.$Taraf : $T1);
	
	
	ViewTransferPlugin::Present($this, $Title);
?>