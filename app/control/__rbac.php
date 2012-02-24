<?php
	$j=new jURL();
	if ($j->RequestFile()=="user/login" or 
		$j->RequestFile()=="user/logout" or
		$j->RequestFile()=="about" 
	) return true;
	if (!j::UserID())
	{
		$return=$j->URL();
		$return=urlencode($return);
		header("location: ".SiteRoot."/user/login?return={$return}");
		die();
	}
?>