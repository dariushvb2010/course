<?php
############################################################
### This module is loaded after all libraries loaded and ###
### before the controller invoked. Use it to determine   ###
### dynamic configuration and re-reg options.			 ###
############################################################
$this->LoadCustomApplicationModule("config.plugin",".");


# Language Determination
if (isset($_GET['lang']))
{
	if ($_GET['lang']=='fa')
	{
		j::SaveSession("jf/currentLanguage","fa");
	}
	elseif ($_GET['lang']=='en')
	{
		j::SaveSession("jf/currentLanguage","en");
	}
}
$lang=j::LoadSession("jf/currentLanguage");


if ($lang=="fa")	
	j::$i18n->SetCurrentLanguage("fa");
elseif ($lang=="en")	
	j::$i18n->SetCurrentLanguage("en");

	
unset($lang);

$this->App->LoadSystemModule ("lib.dbal.adapter.mssql");
//j::$MSSQL=new DBAL_MSSQL(reg("app/db/source/user"), reg("app/db/source/pass"), reg("app/db/source/name"),reg("app/db/source/host"));


?>