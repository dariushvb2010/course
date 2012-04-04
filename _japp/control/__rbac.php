<?php
	$j=new jURL();
	if ($j->RequestFile()=="sys/login" or $j->RequestFile()=="sys/logout") return;

	if (!j::UserID())
		header("location: ".SiteRoot."/sys/login?return=/{$j->RequestFile()}");
	else 
		if (!j::$RBAC->Check("panel")) 
			j::$RBAC->Enforce("root");
?>