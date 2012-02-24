<?php


class I18nPlugin extends BaseApplicationClass
{
	
	private $TranslatePreparedStatement;
	private $SavePreparedStatement;
	
	public $Disabled=false;
	function Translate($Phrase, $TargetLanguage = null)
	{
		if ($this->Disabled) return $Phrase;
		$t=j::Registry("jf/i18n/langs");
		$Lang=$t['default'];
		if ($Lang==$TargetLanguage) return $Phrase;
		if (! $this->TranslatePreparedStatement) $this->TranslatePreparedStatement = $this->App->DB->Prepare ( "SELECT `" . "Translation" . "` FROM `" . "jf_i18n" . "` WHERE " . "`" . "Language" . "`=? AND `" . "Phrase" . "`=?" );
		$this->TranslatePreparedStatement->Execute ( $TargetLanguage, trim($Phrase) );
		$Res = $this->TranslatePreparedStatement->AllResult ();
		if ($Res)
		{
			if ($Res [0])
			{
				if ($Res [0] ['Translation'])
				{
					return $Res [0] ['Translation'];
				}
				else #exists but is not translated
				{
					return reg("jf/i18n/untranslated");	
				}
			}
		
		}
		$t = j::Registry ( "jf/i18n/untranslated" );
		$this->Save ( $Phrase, $TargetLanguage, "" );
		return $t;
	}

	function Save($Phrase, $TargetLanguage, $Translation)
	{
		if (! $this->SavePreparedStatement) $this->SavePreparedStatement = $this->App->DB->Prepare ( "REPLACE `" . "jf_i18n" . "` (" . "`" . "Language" . "`,`" . "Phrase" . "`,`" . "Translation" . "`,`"."TimeAdded"."`) VALUES (?,?,?,?)" );
		$this->SavePreparedStatement->Execute ( $TargetLanguage, $Phrase, $Translation,time() );
	
	}
	function SetCurrentLanguage($Lang)
	{
		j::$Registry->jf->i18n->langs['current']=$Lang;
	}
	function GetCurrentLanguage()
	{
		return j::$Registry->jf->i18n->langs['current'];
		
	}
	function SetDefaultLanguage($Lang)
	{
		j::$Registry->jf->i18n->langs['default']=$Lang;
	}
	function GetDefaultLanguage()
	{
		return j::$Registry->jf->i18n->langs['default'];
		
	}
	function __destruct()
	{
	
	}

}