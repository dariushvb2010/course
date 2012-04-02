<?php 
	$T1="ارسال اظهارنامه ";
	$T2="ارسال اظهارنامه به ";
	$Taraf=ReviewTopic::$TYPES[$this->Handler->TopicType];
	$Title=(isset($Taraf) ? $T2.$Taraf : $T1);
	
	
	ViewTransferPlugin::Present($this, $Title);
?>