<?php 
	$Title="ارسال اظهارنامه به ";
	$Title.= ConfigData::$TOPIC_TYPE[$this->Handler->TopicType];
	ViewTransferPlugin::Present($this, $Title);
?>