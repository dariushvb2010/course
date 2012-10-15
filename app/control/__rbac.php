<?php
	$j=new jURL();
	$pp=explode('/',$j->RequestFile());
	if ($j->RequestFile()=="user/login" or 
		$j->RequestFile()=="user/logout" or
		$pp[0]=="myservice" or
		$j->RequestFile()=="about" or 
		$j->RequestFile()=='main' or 
		$j->RequestFile()=='user/create' or 
		$j->RequestFile()=='report/poll' or
		$j->RequestFile()=='report/news'
	) return true;
	if (!j::UserID())
	{
		$return=$j->URL();
		$return=urlencode($return);
		header("location: ".SiteRoot."/user/create?return={$return}");
		die();
	}
?>
