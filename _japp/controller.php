<?php
class BasejFrameworkApplicationController 
{
	/**
	 * Self!
	 * @var ApplicationController
	 */
	public $App;
	/**
	 * Database Access Layer Object. Database transaction and management functions are inside this object.
	 * @var DBAL 
	 */
	public $DB;
	/**
	 * Session Management object. Session Handling, User management and session management are here.
	 * @var SessionManager
	 */
	public $Session;
	/**
	 * Service Management. This object handles all service calls and service consumptions allowed by jFramework.
	 * @var ServiceCore 
	 */
	public $Services;
	/**
	 * Web Tracker. Tracks user activities on specific pages of this system.
	 * @var WebTracker
	 */
	public $Tracker;
	/**
	 * Options Interface. This object allows you to save options for current session, current user and even current application
	 * and retrieve them when needed.
	 * @var ApplicationOptions
	 */
	public $Options;
	/**
	 * Log Management. Logs system events, Analyses logs and etc.
	 * @var LogManager
	 */
	public $Log;
	/**
	 * Role Based Access Control. Handles application access control and user/role/permission relations. NIST standard.
	 * @var RBAC
	 */
	public $RBAC;
	/**
	 * Security Interface. This object contains methods for all common aspects of web security (except session and RBAC management)
	 * @SecurityInterface
	 */
	public $Security;
	
	/**
	 * jFramework Registry
	 * @var jRegistry
	 */
	public $Registry;
	
	/**
	 * 
	 * jFramework Error Handler 
	 * @var jfErrorHandler
	 */
	public $ErrorHandler;
	
	
	
	public $ReqeustRaw;
	public $RequestProcessed;
	public $RequestModule;
	public $RequestType;
	public $SystemMode;
	/**
	 * Random number describing the current request
	 *
	 * @var Integer
	 */
	public $RequestID;

	/**
	 * Generates a random request ID, for every request
	 *
	 */
	protected function GenerateRequestID()
	{
		$this->RequestID = "";
		for($i = 0; $i < 10; ++ $i)
			$this->RequestID .= mt_rand ( 0, 9 );
		return $this->RequestID;
	}
	


}

/**
 * Entry point for a jFramework Application.
 * This has functions for handling main application events and objects for handling framework tasks.
 *
 */
class ApplicationController extends BasejFrameworkApplicationController
{

	function __construct()
	{
		$this->App = $this;
	}

	/**
	 * Loads necessary files to initialize jFramework. This should not be changed.
	 *
	 */
	protected function LoadFundamentalModules()
	{
		spl_autoload_register ( array (
			$this, "Autoload" 
		),true );
		//jURL module   
		$this->LoadCustomSystemModule ( "model.core.jurl", "." );
		//Registry
		$this->LoadCustomSystemModule ( "model.core.registry", "." );
		$this->Registry = new jRegistry ( );
		$this->LoadCustomSystemModule ( "model.j", "." ); //j:: helper
		j::__Initialize ( $this );
		$this->LoadCustomSystemModule ( "model.functions", "." ); //functions helper
		b::__Initialize();
		
	}
	protected function LoadFundamentalConfiguration()
	{
		//jFramework and Application specific configurations
		$this->LoadCustomSystemModule ( "config.base", "." );
	}
	Protected function LoadApplication()
	{
		//Error handling module
		$this->LoadCustomSystemModule ( "model.core.errorhandling", "." );
		$this->ErrorHandler=new jfErrorHandler();
		
		//Base Abstract Class for Application Models
		$this->LoadCustomSystemModule ( "model.base.app", "." );
		
		//Base  Class for Application Views
		$this->LoadCustomSystemModule ( "model.base.view", "." );
		
		//Base Abstract Class for Plug-ins
		$this->LoadCustomSystemModule ( "model.base.plugin", "." );
		
		//Base Abstract Class for Controllers
		$this->LoadCustomSystemModule ( "model.base.control", "." );

		//jFramework and Application specific configurations
		$this->LoadCustomSystemModule ( "config.main", "." );
				
	}

	/**
	 * The autoload function for jFramework
	 * this is called when you use a class without including it!
	 *
	 * @param String $ClassName
	 */
	protected function Autoload($ClassName)
	{
		$path = new jpClassName2Module ( $ClassName );
		$path=$path->__toString();
		if ($path)
		{
			$Result = $this->LoadApplicationModule ( $path, true );
			if (! $Result) $Result = $this->LoadSystemModule ( $path, true );
		}
//		if (! $Result) trigger_error ( "Unable to autoload module $ClassName at " . $path );
	}

	/**
	 * Loads necessary application libraries. This should not be changed.
	 * Instead of libraries, service core and j:: helper are loaded
	 */
	Protected function LoadLibraries()
	{
		
		$this->LoadCustomSystemModule ( "lib.tracker", "." );
		
		$this->Tracker = new WebTracker ( $this->DB, $this->Session );
		$this->LoadCustomSystemModule ( "lib.dbal", "." ); //Database Access layer
		$this->DB = new DBAL ( reg ( "app/db/default/user" ), reg ( "app/db/default/pass" ), reg ( "app/db/default/name" ), reg ( "app/db/default/host" ) );
		
		$this->LoadCustomSystemModule ( "lib.log", "." );
		$this->Log = new LogManager ( $this );
		
		$this->LoadCustomSystemModule ( "lib.session", "." );
		$this->Session = new SessionManager ( $this->DB );

		
		$this->LoadCustomSystemModule ( "lib.options", "." );
		$this->Options = new ApplicationOptions ( $this );
		
		$this->LoadCustomSystemModule ( "lib.security", "." );
		$this->Security = new SecurityInterface ( $this );
		
		$this->LoadCustomSystemModule ( "lib.rbac", "." );
		$this->RBAC = new RBAC ( $this );
				
		$this->LoadCustomSystemModule ( "model.services.core", "." );
		$this->Services = new ServiceCore ( $this );
			
		//final configurations, runs after all loadings just before the specific controller
		$this->LoadCustomApplicationModule ( "config.final", "." );
	
	}

	/**
	 * Loads a jFramework module (system module if system mode and application module otherwise)
	 * Typically a jFramework module is a single PHP file which is included into the program with $this as the ApplicationController object for it.
	 * @param String $ModulePath jFramework module name (path) e.g. "view.main"
	 * @param Boolean $Ignore do not trigger an error if file not found
	 * @param array $Variables whatever other parameters that you set here, are known to the included file
	 * @return true on success
	 */
	function LoadModule($Path, $Ignore = false, $Variables = null)
	{
		if (j::Registry ( "jf/mode" ) == "system")
			return $this->LoadSystemModule ( $Path, $Ignore, $Variables );
		else
			return $this->LoadApplicationModule ( $Path, $Ignore, $Variables );
	}

	/**
	 * Loads a custom module (a module with custom delimiters)
	 * This is equal to calling LoadModule (new jpCustom2Module($Path,$CustomDelimeter))
	 * @param $Path
	 * @param $CustomDelimiter
	 * @param $Ignore
	 * @param $Variables
	 * @return true on success
	 */
	function LoadCustomModule($Path, $CustomDelimiter = jf_jPath_Module_Custom_Delimiter, $Ignore = false, $Variables = null)
	{
		if ($CustomDelimiter == constant ( "jf_jPath_Module_Delimiter" )) return $this->LoadModule ( $Path, $Ignore, $Variables );
		$x=new jpCustom2Module ( $Path, $CustomDelimiter );
		return $this->LoadModule ( $x->__toString(), $Ignore, $Variables );
	}

	/**
	 * Loads a jFramework application module.
	 * Typically a jFramework module is a single PHP file which is included into the program with $this as the ApplicationController object for it.
	 * @param String $ModulePath jFramework module name (path) e.g. "view.main"
	 * @param Boolean $Ignore do not trigger an error if file not found
	 * @param array $Variables whatever other parameters that you set here, are known to the included file
	 * @return true on success
	 */
	function LoadApplicationModule($ModulePath, $Ignore = false, $Variables = null)
	{
		$m = new jpModule2FileSystem ( $ModulePath, false );
		$m=$m->__toString(); //PHP 5.2- compatibility
		// define variables to be valid in included file
		if ($Variables) foreach ( $Variables as $ArgName => $ArgValue )
			${$ArgName} = $ArgValue;
		
		#load the file
		if (! file_exists ( $m ) || ! is_file ( $m ))
		{
			if ($Ignore)
				return false;
			else
			{
				trigger_error ( "LoadModule failed, not found: " . $m );
				return false;
			}
		}
		require_once ($m);
		return true;
	}

	/**
	 * Loads a custom application module (a module with custom delimiters)
	 * This is equal to calling LoadApplicationModule (new jpCustom2Module($Path,$CustomDelimeter))
	 * @param $Path
	 * @param $CustomDelimiter
	 * @param $Ignore
	 * @param $Variables
	 * @return true on success
	 */
	function LoadCustomApplicationModule($Path, $CustomDelimiter = jf_jPath_Module_Custom_Delimiter, $Ignore = false, $Variables = null)
	{
		if ($CustomDelimiter == constant ( "jf_jPath_Module_Delimiter" )) return $this->LoadApplicationModule ( $Path, $Ignore, $Variables );
		$x=new jpCustom2Module ( $Path, $CustomDelimiter );
		return $this->LoadApplicationModule ( $x->__toString(), $Ignore, $Variables );
	}

	/**
	 * Loads a jFramework system module.
	 * Typically a jFramework module is a single PHP file which is included into the program with $this as the ApplicationController object for it.
	 * triggers and error on failure (set $Ignore to true to bypass)
	 * @param String $ModulePath jFramework module name (path) e.g. "view.main"
	 * @param Boolean $Ignore do not trigger an error if file not found
	 * @param array $Variables whatever other parameters that you set here, are known to the included file
	 * @return true on success,false if ignore and file not found
	 */
	function LoadSystemModule($ModulePath, $Ignore = false, $Variables = null)
	{
		$m = new jpModule2FileSystem ( $ModulePath, true );
		$m=$m->__toString();
		// define variables to be valid in included file
		if ($Variables) foreach ( $Variables as $ArgName => $ArgValue )
			${$ArgName} = $ArgValue;
			
		#load the file
		if (! file_exists ( $m ) || ! is_file ( $m ))
		{
			if ($Ignore)
				return false;
			else
			{
				trigger_error ( "LoadSystemModule failed, not found: " . $m );
				return false;
			}
		}
		require_once ($m);
		return true;
	}

	/**
	 * Loads a custom system module (a module with custom delimiters)
	 * This is equal to calling LoadSystemModule (new jpCustom2Module($Path,$CustomDelimeter))
	 * @param $Path
	 * @param $CustomDelimiter
	 * @param $Ignore
	 * @param $Variables
	 * @return true on success
	 */
	function LoadCustomSystemModule($Path, $CustomDelimiter = jf_jPath_Module_Custom_Delimiter, $Ignore = false, $Variables = null)
	{
		if ($CustomDelimiter == constant ( "jf_jPath_Module_Delimiter" )) return $this->LoadSystemModule ( $Path, $Ignore, $Variables );
		$x=new jpCustom2Module ( $Path, $CustomDelimiter );
		return $this->LoadSystemModule ( $x->__toString(), $Ignore, $Variables );
	}

	/**
	 * Starts a controller of application.
	 *
	 * @param String $ControllerModule the module path of the controller, e.g control.main
	 * @param Boolean $System = false, if set to true, system controllers would be invoked
	 * @return true on successful controller call, false otherwise
	 */
	function StartController($ControllerModule, $System = false)
	{
		//Loading controller file
		if ($System)
			$LoadStatus = $this->LoadSystemModule ( $ControllerModule, true );
		else
			$LoadStatus = $this->LoadModule ( $ControllerModule, true );
		$Classname="";
		if ($LoadStatus) //Controller file found, set classname and load
		{
			$Classname = new jpModule2ClassName ( $ControllerModule );
			$Classname = $Classname->__toString ();
		}
		else //controller file not found
		{
			$Res = false;
			

			//autoController not found as well, go for default handler
			if (!reg("jf/control/DefaultController/enabled")) return false; //default controller disabled
			
			$dControllerModule=$this->GetIterativeDefaultController($ControllerModule);
			if ($System)
				$LoadStatus = $this->LoadSystemModule ( $dControllerModule, true );
			else
				$LoadStatus = $this->LoadModule ( $dControllerModule, true );
			if ($LoadStatus)
			{
				$Classname = new jpModule2ClassName ( $dControllerModule );
				$Classname = $Classname->__toString ();
				$Classname = str_replace(reg("jf/controller/DefaultController/metafile"),reg("jf/controller/DefaultController/classname"),$Classname);
			}
			else
			{
				//go for autocontroller (view only)
				if (j::Registry ( "jf/Controller/AutoController" )) 
				{
					$control = new AutoController ( $this );
					$x=new jpTrimStart ( $ControllerModule );
					$Res = $control->Start ( $x->__toString() );
				}
				if ($Res) return true; //autocontroller found and presented.
			}
			
			
		}
		if (class_exists ( $Classname ))
		{
			$control = new $Classname ( $this );
			if (method_exists ( $control, "Start" ))
				return $control->Start ();
			elseif (method_exists ( $control, "Main" ))
				return $control->Main ();
			else
				return false;
		}
		if (reg("jf/controller/ForceObjectOriented")) return false;
		else true; //non OO controller
	}
	Protected function GetIterativeDefaultController($ControllerModule) 
	{
			if (reg("jf/control/DefaultController/iterative"))
				$Iteration = reg("jf/control/DefaultController/iterations");
			else
				$Iteration = 1;
			
			$d = constant ( "jf_jPath_Module_Delimiter" );
			$n = 0;
			while ( $n <= $Iteration )
			{
				$template = new jpTrimEnd ( $ControllerModule, $d, ++ $n );
				$template=$template->__toString();
				if ($template=="") break;
				
				$template = $template . $d . reg("jf/controller/DefaultController/metafile");
				$x=new jpModule2FileSystem ( $template );
				if (file_exists ( $x->__toString() )) return $template;
			}
			$template_root = "control" . $d . reg("jf/controller/DefaultController/metafile") ;
			return $template_root;	
	}	
	

	function StartApplication($Request)
	{
		reg( "jf/mode", "application" ); # would be set to "system" on sys. requests
		$this->SystemMode = false;
		
		//append .main as the default index page
		if (substr ( $Request, strlen ( $Request ) - 1, 1 ) == constant ( "jf_jPath_Request_Delimiter" )) $Request .= j::Registry ( "jf/controller/DefaultApplicationInterface", "main" );
		
		//processed and fixed request
		$this->RequestProcessed = $Request;
		
		//get the module we need to load now
		$this->RequestModule = $RequestedModule = new jpRequest2Module ( $Request );
		$this->RequestModule = $RequestedModule= $RequestedModule->__toString();
		//load the controller module 
		if (! $x = $this->StartController ( $RequestedModule ))
		{
			//not found!
			if (! headers_sent ()) # no output done, this check prevents controllers that don't return true to fail
$this->LoadCustomApplicationModule ( "config.page.application-interface-not-found", ".", false, array (
				"Request" => $Request, "Module" => $RequestedModule 
			) );
			return false;
		}
		return true;
	}

	function StartSystem()
	{
		reg ( "jf/mode", "system" );
		$this->SystemMode = true;
		
		
		$this->RequestedModule = $RequestedModule = new jpRequest2Module ( $this->RequestRaw );
		$this->RequestedModule = $RequestedModule =$RequestedModule->__toString();
		if (! $this->StartController ( $RequestedModule, true ))
		{
			if (! headers_sent ()) # no output done, this check prevents controllers that don't return true to fail
$this->LoadCustomApplicationModule ( "config.page.system-interface-not-found", ".", false, array (
				"Request" => $Request, "Module" => $RequestedModule 
			) );
			return false;
		}
	}

	function StartService()
	{
		$ServiceTitle = new jpRequest2Module ( $this->RequestRaw );
		$ServiceTitle = $ServiceTitle->__toString();
		
		$Params = array_merge ( $_GET, $_POST );
		
		$Result = $this->Services->Invoke ( $ServiceTitle, $Params, "soap", "soap" );
		
		$Headers = $this->Services->Headers;
		//Dump headers requested by the service
		if ($Result)
		{
			if (is_array ( $Headers )) foreach ( $Headers as $v )
				header ( $v );
			if (is_string($Result))
				echo ($Result);
			return true;
		}
		else
		{
			$this->LoadCustomApplicationModule ( "config.page.service-not-found", ".", false, array (
				"Request" => $Request, "Service" => $ServiceTitle, "Params" => $Params 
			) );
			return false;
		}
	}

	function StartFile()
	{
		$this->LoadCustomSystemModule ( "model.core.fileman", "." );
		
		//jFileManager
		$File = new jpResource2FileSystem ( $this->RequestRaw );
		$File=$File->__toString();
		$FileMan = new jFileManager ( );
		if (! $FileMan->Feed ( $File )) //404 Not Found
		{
			$this->LoadCustomApplicationModule ( "config.page.404-file-not-found", ".", false, array (
				"File" => $File 
			) );
			return false;
		}
		return true;
	}
	function StartTest($Request)
	{
		if (reg("app/state")!="develop")
		{
			$this->LoadCustomApplicationModule ( "config.page.501-invalid-request", ".", false, array (
				"Request" => $Request, "Module" => $RequestedModule 
			));
			return false;
		}
		reg( "jf/mode", "application" ); # would be set to "system" on sys. requests
		$this->SystemMode = false;
		
		
		//append .main as the default index page
		if (substr ( $Request, strlen ( $Request ) - 1, 1 ) == constant ( "jf_jPath_Request_Delimiter" )) $Request .= j::Registry ( "jf/controller/DefaultApplicationInterface", "main" );
		
		//processed and fixed request
		$this->RequestProcessed = $Request;
		
		//get the module we need to load now
		$this->RequestModule = $RequestedModule = new jpRequest2Module ( $Request );
		$this->RequestModule = $RequestedModule= $RequestedModule->__toString();
		

		//SimpleTest Autorun
		$this->LoadCustomApplicationModule ( "plugin.simpletest.autorun", "." );
		//Base Abstract Class for Tests
		$this->LoadCustomSystemModule ( "model.base.test", "." );
		//Loading test file
		if ($this->SystemMode)
			$LoadStatus = $this->LoadSystemModule ( $RequestedModule, true );
		else
			$LoadStatus = $this->LoadModule ( $RequestedModule, true );
		
		if ($LoadStatus) //Test file found, everything else is automated!
		{
			$c=new jpModule2ClassName($RequestedModule);
			$c=$c->__toString();
			if (!defined("TestTitle")) define("TestTitle",$c);
				return true;
		}
		if (! headers_sent ()) # no output done, this check prevents controllers that don't return true to fail
$this->LoadCustomApplicationModule ( "config.page.test-interface-not-found", ".", false, array (
			"Request" => $Request, "Module" => $RequestedModule 
		) );
		return false;
	}
	/**
	 * Handles a whole web request to this jFramework Application
	 *
	 */
	public function Start($BaseURL=null,&$isFile=false)
	{
		/**
		 * Loads the necessary jFramework modules to start a jFramework application
		 */
		$this->LoadFundamentalModules();
		$Request=null;
		if ($BaseURL!==null)
		{
			if (substr($BaseURL,strlen($BaseURL)-1)!="/")
				$BaseURL.="/";
			$Request=substr(jURL::URL(),strlen($BaseURL));
			if (strpos($Request,"?")!==false)
			{
				$Request=substr($Request,0,strpos($Request,"?"));
			}
		}
		if ($Request===null) 
			if (isset($_GET['__r'])) $Request = $_GET ['__r']; //get the request
		define ( "REQUEST", $Request ); //the actual request to the site, used in jURL
		$this->LoadFundamentalConfiguration();
		reg("request/raw",$Request);
		$this->RequestProcessed = $this->RequestRaw = $Request;
		
		reg("request/id",$this->GenerateRequestID ());
		
		if ($Request == "") 
			$Request = "app" . constant ( "jf_jPath_Request_Delimiter" ) . "main";
		
		reg("request/processed",$Request);
		
		unset ( $_GET ['__r'] );
		$a=explode ( constant ( "jf_jPath_Request_Delimiter" ), $Request );
		$this->RequestType = $RequestType = array_shift ( $a );
		reg("request/type",$this->RequestType);
		$a=explode ( constant ( "jf_jPath_Resource_Delimiter" ), $Request );
		$ResourceType = array_shift ( $a );
		reg("request/resource/type",$this->RequestType);

		$isFile=array_key_exists ( $ResourceType, jpResource2FileSystem::$Prefix );
		/**
		 * Loads jFramework libraries and j helper module
		 */
		
		if (!$isFile) 
		{
			$this->LoadApplication ();
			$this->LoadLibraries ();
		}
		
		
		$this->Started=true;
		
		if (!(defined("jfasalib") and constant("jfasalib")))  
			return $this->Run($Request,$RequestType,$ResourceType);
		

	}
	/**
	 * After loading jFramework and libraries, use this function to run a request type
	 * @param $Request
	 * @param $RequestType
	 */
	public function Run($Request=null,$RequestType=null,$ResourceType=null)
	{
		if (!$this->Started)
		{
			trigger_error("You should start jFramework before running a request");
			return false;
		}
		if ($Request===null)
			$Request=reg("request/processed");
		if ($RequestType===null)
			$RequestType=reg("request/type");
		if ($ResourceType===null)
			$ResourceType=reg("request/resource/type");
			
			
		$isFile=array_key_exists ( $ResourceType, jpResource2FileSystem::$Prefix );
		if ($RequestType == "app") # Application Call app.
			return $this->StartApplication ( $Request );
		elseif ($isFile) //file call
			return $this->StartFile ();
		elseif ($RequestType == "service") # Service Call service.
			return $this->StartService ();
		
		elseif ($RequestType == "sys") # System Call sys.
			return $this->StartSystem ();
		elseif ($RequestType == "test") # Test Call test.
			return $this->StartTest ($Request);
		else //if jFramework receives an unknown type of request, its here.
		{
			if (! reg ( "jf/controller/DefaultApplicationInterface" ))
			{
				$this->LoadCustomApplicationModule ( "config.page.501-invalid-request", ".", false, array (
					"Request" => $Request, "RequestType" => $RequestType 
				) );
				return false;
			}
			else
			{
				// jFramework is set to consider unknown requests as app request, so prepend app/
				$Request = "app" . constant ( "jf_jPath_Request_Delimiter" ) . $Request;
//				reg("request/processed",$Request);
				return $this->StartApplication ( $Request );
			}
		}
	}
}
class jfFrontController extends ApplicationController
{

	
	static function GetSingleton()
	{
		if (jfFrontController::$__singleton===null)
			jfFrontController::$__singleton=new jfFrontController();
		return jfFrontController::$__singleton;
	}
	static private $__singleton=null;
}
try 
{

	require_once (dirname ( __FILE__ ) . "/model/core/jpath.php"); //jPath Module, for jFramework paths
	$x=new jpCustom2Module ( "config.setting", "." );
	$x=$x->__toString();
	$x=new jpModule2FileSystem ( $x );
	$x=$x->__toString();
	require_once $x;
	$jFramework = jfFrontController::GetSingleton();
}
catch (Exception $e)
{
	die("Loading jFramework settings or jPath module failed.");
}

//echo "<BR/>".(memory_get_peak_usage()/(1024.0*1024))."<BR/>".(memory_get_usage()/(1024.0*1024))."<BR/>";
try 
{
	if (!(defined("jfasalib") and constant("jfasalib")))  
		$jFramework->Start ();
}
catch (Exception $e)
{
	$jFramework->ErrorHandler->HandleException($e);	
}

?>