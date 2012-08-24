<?php


class BaseViewClass extends BaseApplicationClass
{

	private function CurrentView()
	{
		if (class_exists ( "ViewDetector", false ))
		{
			$V = new ViewDetector ( $this->App );
			return $V->DetermineView ();
		}
		else
			return "default";
	}
	private $ViewTitle;

	/**
	 * Loads the header from _template/head.php
	 * if a _template folder exists on the view file's folder, its header would be loaded (ignore on head.php not exists)
	 * otherwise, global header would be loaded (error on head.php not exists)
	 *
	 * @param String $PageTitle
	 * @param String $ViewTitle
	 */
	function PresentHeader($PageTitle, $ViewTitle = "", $Extra = null)
	{
		$d = constant ( "jf_jPath_Module_Delimiter" );
		
		if (reg("jf/view/templates/iterative"))
			$Iteration = reg("jf/view/templates/iterations");
		else
			$Iteration = 1;
		
		$n = 0;
		while (  $n <= $Iteration )
		{
			$template = new jpTrimEnd ( $ViewTitle, $d, ++ $n );
			$template=$template->__toString();
			if ($template == "") break;
			$template = $d . $template;
			$template = "view" . $d . $this->CurrentView () . $template . $d . "_template" . $d . "head";
			$x=new jpModule2FileSystem ( $template );
			if (file_exists ( $x->__toString() )) return $this->App->LoadModule ( $template, true, array (
				"Title" => $PageTitle, "Extra" => $Extra 
			) );
		}
		$template_root = "view" . $d . $this->CurrentView () . $d . "_template" . $d . "head";
		return $this->App->LoadModule ( $template_root, true, array (
			"Title" => $PageTitle, "Extra" => $Extra
		) );
	}
	
	/**
	 * Loads the header from _template/head.php
	 * if a _template folder exists on the view file's folder, its header would be loaded (ignore on head.php not exists)
	 * otherwise, global header would be loaded (error on head.php not exists)
	 *
	 * @param String $PageTitle
	 * @param String $ViewTitle
	 */
	function PresentLayout($PageTitle, $ViewTitle = "", $Extra = null,$Content=null)
	{
		$d = constant ( "jf_jPath_Module_Delimiter" );
	
		if (reg("jf/view/templates/iterative"))
			$Iteration = reg("jf/view/templates/iterations");
		else
			$Iteration = 1;
	
		$n = 0;
		while (  $n <= $Iteration )
		{
			$template = new jpTrimEnd ( $ViewTitle, $d, ++ $n );
			$template=$template->__toString();
			if ($template == "") break;
			$template = $d . $template;
			$template = "view" . $d . $this->CurrentView () . $template . $d . "_template" . $d . "layout";
			$x=new jpModule2FileSystem ( $template );
			if (file_exists ( $x->__toString() )) return $this->App->LoadModule ( $template, true, array (
					"Title" => $PageTitle, "Extra" => $Extra, "Content" => $Content
			) );
		}
		$template_root = "view" . $d . $this->CurrentView () . $d . "_template" . $d . "layout";
		return $this->App->LoadModule ( $template_root, true, array (
				"Title" => $PageTitle, "Extra" => $Extra , "Content" => $Content
		) );
	}

	function PresentFooter($ViewTitle = "")
	{
		$d = constant ( "jf_jPath_Module_Delimiter" );
		
		if (j::Registry("jf/view/templates/iterative"))
			$Iteration = 10000;
		else
			$Iteration = 1;
		
		$n = 0;
		while ( $n <= $Iteration )
		{
			$template = new jpTrimEnd ( $ViewTitle, $d, ++ $n );
			if (($template->__toString()) == "") break;
			$template = $d . $template;
			$template = "view" . $d . $this->CurrentView () . $template . $d . "_template" . $d . "foot";
			$x=new jpModule2FileSystem ( $template );
			if (file_exists ( $x->__toString() )) return $this->App->LoadModule ( $template );
		}
		$template_root = "view" . $d . $this->CurrentView () . $d . "_template" . $d . "foot";
		return $this->App->LoadModule ( $template_root, true );
	}

	function StartBuffering()
	{
		ob_start ();
	}

	function EndBuffering()
	{
		return ob_get_clean ();
	}

	function Present($ViewTitle, $PageTitle, $Extra = null)
	{
		if (file_exists ( $this->ViewFile ( $ViewTitle ) ))
		{
			$this->ViewTitle = $ViewTitle;
			if (j::Registry("jf/view/parser"))
			{
				$Parser = new ViewParserPlugin ( $this->App );
				
				$File = $this->ViewFile ( $ViewTitle );
				$this->StartBuffering ();
				if (file_exists ( $File )) include $File;
				$Content = $this->EndBuffering ();
				$Parser->Parse ( $Content );

				$Extra = "";
				$Extra .= $Parser->FormatMeta ();
				reg("app/view/title",$Parser->Title);
				$this->StartBuffering ();
				$this->PresentLayout( $Parser->Title, $ViewTitle, $Extra,$Content );
				$LayoutContent = $this->EndBuffering ();

				$Parser->FixLinks ( $LayoutContent );
				
				//$this->StartBuffering ();
				//$this->PresentFooter ( $ViewTitle );
				//$FootContent = $this->EndBuffering ();
				//$Parser->FixLinks ( $FootContent );
				
				echo $LayoutContent;				
				//echo $Content;
				//echo $FootContent;
				return true;
			}
			else
			{
				$this->PresentHeader ( $PageTitle, $ViewTitle, $Extra );
				$x = $this->PresentView ( $ViewTitle );
				$this->PresentFooter ( $ViewTitle );
				return $x;
			}
		}
		else
			return false;
	}

	function PresentString($String, $PageTitle = null)
	{
		$this->PresentHeader ( $PageTitle );
		echo $String;
		$this->PresentFooter ();
		return true;
	}

	function BarePresentString($String)
	{
		echo $String;
		return true;
	}

	function ViewModule($ViewTitle)
	{
		$d = constant ( "jf_jPath_Module_Delimiter" );
		$t = "view{$d}" . $this->CurrentView () . "{$d}{$ViewTitle}";
		return $t;
	}

	function ViewFile($ViewTitle)
	{
		$t = $this->ViewModule ( $ViewTitle );
		$File = new jpModule2FileSystem ( $t, j::Registry("jf/mode")=="system" );
		return $File->__toString();
	}

	function PresentView($ViewTitle)
	{
		$File = $this->ViewFile ( $ViewTitle );
		if (file_exists ( $File ) && is_file ( $File ))
		{
			include $File;
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Represents a portion of a view
	 * This is useful for huge views.
	 *
	 * @param String $RelativePath the path from here to the other portion of the view
	 */
	function Represent($RelativePath)
	{
		$x = new jpTrimEnd ( $this->ViewTitle );
		$x=$x->__toString();
		if ($x != "") $x .= constant ( "jf_jPath_Module_Delimiter" );
		return $this->PresentView ( $x . $RelativePath );
	}
}
abstract class JView extends BaseViewClass {}

?>
