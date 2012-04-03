<?php


abstract class BaseControllerClass extends BaseApplicationClass
{
	/**
	 * View Module
	 *
	 * @var BaseViewClass
	 */
	public $View;
	public $Presented = false;

	abstract function Start(); //Starts the controller

	
	/**
	 * this would help the developer to be able to use this inside controller code:
	 * $this->Variable='something';
	 * 
	 * this is the same as
	 * 
	 * $this->View->Variable='something';
	 * 
	 * 
	 * @param string $name
	 * @param mixed $value
	 */
	function __set($name, $value)
	{
		$this->View->{$name} = $value;
	}

	function __get($name)
	{
		return $this->View->{$name};
	}

	function __isset($name)
	{
		return isset ( $this->View->{$name} );
	}

	function __unset($name)
	{
		unset ( $this->View->{$name} );
	}

	function __construct($App)
	{
		parent::__construct ( $App );
		$this->View = new BaseViewClass ( $this->App );
		$this->AccessControl ();
	}

	function AccessControl()
	{
		$Module = new jpClassName2Module ( get_class ( $this ) );
		$Module=$Module->__toString();
		$d = constant ( "jf_jPath_Module_Delimiter" );
		$n = 0;
		while ( ($rbac_meta = new jpTrimEnd ( $Module, $d, ++ $n ))  )
		{
			$rbac_meta=$rbac_meta->__toString();
			if ($rbac_meta== "") break; 
			$rbac_meta .= $d . reg("jf/rbac/metafile");
			$x=new jpModule2FileSystem ( $rbac_meta, j::Registry("jf/mode")=="system" );
			if (file_exists ( $x->__toString())) return $this->App->LoadModule ( $rbac_meta );
			if ($rbac_meta == "control") break;
		}
		return false;
	}

	/**
	 * Redirects the application using headers and client-side.
	 * Should be only called within a controller.
	 *
	 * @param String $NewLocation the new application
	 */
	function Redirect($NewLocation, $RedirectQueryString = false,$StripQueryStringValues=0)
	{
		if ($RedirectQueryString)
		{
			$j = new jURL ( );
			$x = explode ( "&", $j->QueryString () );
			while ($StripQueryStringValues--) if (is_array ( $x ) && count ( $x ) > 1) array_shift ( $x );
			$x = implode ( "&", $x );
			if ($x) 
			if (strpos ( $NewLocation, "?" ) === false)
				$x = "?{$x}";
			elseif (strpos($NewLocation,"?")===strlen($NewLocation)-1)
				$x = "{$x}";
			else
				$x="&{$x}";
		}
		else
			$x = "";
		header ( "location: {$NewLocation}{$x}" );
		exit ();
	}

	/**
	 * Presents a view without templates (for ajax pages)
	 *
	 * @param jPath $ViewTitle
	 * @return true on success
	 */
	function BarePresent($ViewTitle = "")
	{
		if ($ViewTitle == "") $ViewTitle = $this->DefaultViewTitle ();
		$this->Presented = true;
		return $this->View->PresentView ( $ViewTitle );
	}

	/**
	 * Presents a view
	 *
	 * @param String $ViewTitle, e.g main for view.main
	 */
	public function Present($PageTitle = null, $ViewTitle = "")
	{
		if ($ViewTitle == "")
		{
			$ViewTitle = $this->DefaultViewTitle ();
		}
		$this->Presented = true;
		return $this->View->Present ( $ViewTitle, $PageTitle );
	}

	/**
	 * This function presents a string instead of a view
	 *
	 * @param String $PageTitle
	 * @param String $String
	 * @return true on success
	 */
	public function PresentString($PageTitle = null, &$String)
	{
		$this->Presented = true;
		return $this->View->PresentString ( $String, $PageTitle );
	}

	/**
	 * This function presents a string instead of a view without templates (for ajax use)
	 *
	 * @param String $String
	 * @return true on success
	 */
	function BarePresentString($String)
	{
		$this->Presented = true;
		return $this->View->BarePresentString ( $String );
	}

	protected function DefaultViewTitle()
	{
		$x = get_class ( $this );
		$x = new jpClassName2Module ( $x );
		$x=$x->__toString();
		$a = explode ( constant ( "jf_jPath_Module_Delimiter" ), $x );
		array_shift ( $a ); //remove control.
		$Default = implode ( constant ( "jf_jPath_Module_Delimiter" ), $a );
		return $Default;
	}

	function __destruct()
	{
		if (! $this->Presented && reg("jf/Controller/AutoPresent")) $this->Present ();
	}
	
	
	
	function Request()
	{
		if (reg("request/processed")) 
			return reg("request/processed");
		else
			return reg("request/raw");
	}
	function RawRequest()
	{
		return reg("request/raw");
		
	}
	function RelativeRequest()
	{
		$c = get_class ( $this );
		$x = new jpClassName2Module ( $c );
		$x=$x->__toString();
		$r=$this->RawRequest();
		$x=explode(constant("jf_jPath_Module_Delimiter"),$x);
		array_shift($x);

		//removes the extra default in default handlers
		if (strpos($c,reg("jf/controller/DefaultController/classname")."Controller")!==false)
			array_pop($x);

		$y=$r;
		$y=explode(constant("jf_jPath_Request_Delimiter"),$y);
		foreach ($x as $x2)
			array_shift($y);
		
		$x=implode(constant("jf_jPath_Module_Delimiter"),$x);
		$y=implode(constant("jf_jPath_Request_Delimiter"),$y);
		return $y;	
	}

}


/**
 * This class is used if AutoController is on to automatically load views without appropriate controllers
 *
 */
class AutoController extends BaseControllerClass
{

	function Start()
	{
		$arg = func_get_arg ( 0 );
		return $this->Present ( null, $arg );
	}
}
class BaseDefaultController extends BaseControllerClass
{
	function Start()
	{
		
	}
	function Handle($Request)
	{
		
	}
}

abstract class JController extends BaseControllerClass {}
abstract class JControl extends BaseControllerClass {}

?>