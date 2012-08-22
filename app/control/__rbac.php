<?php
	$j=new jURL();
	$pp=explode('/',$j->RequestFile());
	if ($j->RequestFile()=="user/login" or 
		$j->RequestFile()=="user/logout" or
		$pp[0]=="myservice" or
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