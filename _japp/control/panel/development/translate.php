<?php
class PanelDevelopmentTranslateController extends BaseControllerClass
{
    function Start ()
    {
    	$off=0;
		$lim=20;
		if (isset($_GET['off'])) $off=$_GET['off'];
		if (isset($_GET['lim'])) $lim=$_GET['lim'];
		$off*=1;
		$lim*=1;
		if (isset($_POST['Operation']))
		{
			if ($_POST['Operation']=='Save') //Save Edition
			{
				$this->EditResult=j::SQL("REPLACE jf_i18n (Language,Phrase,Translation,TimeModified) VALUES (?,?,?,?)",
				$_POST['Language'],trim($_POST['Phrase']),$_POST['Translation'],time());
			}
			elseif ($_POST['Operation']=='Delete') //Delete Selection
			{
				$this->DeleteResult=j::SQL("DELETE FROM jf_i18n WHERE Language=? AND Phrase=?",$_POST['Language'],trim($_POST['Phrase']));
			}
		}
		
		
		$Res=j::SQL("SELECT * FROM jf_i18n ORDER BY TimeAdded DESC LIMIT {$off},{$lim}");
		if ($Res) $this->Phrases=$Res;
    	$this->Present();
    }
}
?>