<?php

//$x=new jpModule2FileSystem("config.j");
//require_once $x->__toString();
$this->App->LoadApplicationModule("config.j");
class j extends jExtend
{
    /**
     * The Application
     *
     * @var ApplicationController 
     */
    public static $App;
    /**
     * Database Access Layer Object. Database transaction and management functions are inside this object.
     * @var DBAL 
     */
    public static $DB;
    /**
     * Session Management object. Session Handling, User management and session management are here.
     * @var SessionManager
     */
    public static $Session;
    /**
     * Service Management. This object handles all service calls and service consumptions allowed by jFramework.
     * @var ServiceCore 
     */
    public static $Services;
    /**
     * Web Tracker. Tracks user activities on specific pages of this system.
     * @var WebTracker
     */
    public static $Tracker;
    /**
     * Options Interface. This object allows you to save options for current session, current user and even current application
     * and retrieve them when needed.
     * @var ApplicationOptions
     */
    public static $Options;
    /**
     * Log Management. Logs system events, Analyses logs and etc.
     * @var LogManager
     */
    public static $Log;
    /**
     * Security Interface
     *
     * @var SecurityInterface
     */
    public static $Security;

    /**
     * Registry Interface
     * @var jRegistry
     */
    public static $Registry;
    
    /**
     * Role Based Access Control
     *
     * @var RBAC
     */
    public static $RBAC;


	/**
	 * Internationalization Interface
	 * @var I18nPlugin
	 */
	public static $i18n;
    
    ##### RBAC Section #####
    static function EnforcePermission($Permission)
    {
        
    }
    static function CheckPermission($Permission)
    {
        
    }
    ### Services Section ###
    static function CallService($Endpoint,$Method,$Params=null,$Session=true)
    {
    	if ($Params===null)
    		$Params=array();
    	return j::$Services->CallService($Endpoint,$Method,$Params,false,$Session);
    }
    static function CallWSDL($WSDLURL, $Method,$Params=null,$Session=true)
    {
    	if ($Params===null)
    		$Params=array();
    	return j::$Services->CallService($Endpoint,$Method,$Params,true,$Session);
    }
    
    ### Security Section ###
    static function XSS(&$String)
    {
        return j::$Security->AntiXSS($String);
    }
    
    
    #### Options Section ####
    static function SaveGeneral($Name, $Value, $Timeout = null)
	{
		if ($Timeout===null)
			$Timeout=reg("jf/session/timeout/General");
		$a=func_get_args();
        return call_user_func_array(array(j::$Options,"SaveGeneral"),$a);	    
	}
	static function LoadGeneral($Name)
	{
		$a=func_get_args();
		return call_user_func_array(array(j::$Options,"LoadGeneral"),$a);	    
	}
	static function SaveUser($Name, $Value,$UserID=null,$Timeout = null)
	{
		if ($Timeout===null)
			$Timeout=reg("jf/session/timeout/NoAccess");
		$a=func_get_args();
		return call_user_func_array(array(j::$Options,"Save"),$a);	    
	}
	static function DeleteGeneral($Name,$UserID=null)
	{
		$a=func_get_args();
		return call_user_func_array(array(j::$Options,"DeleteGeneral"),$a);	    
	}
	
    static function LoadUser($Name)
	{
		$a=func_get_args();
		return call_user_func_array(array(j::$Options,"Load"),$a);	    
	}
	static function SaveSession($Name,$Value,$Timeout = null)
	{
		if ($Timeout===null)
			$Timeout=reg("jf/session/timeout/General");
		$a=func_get_args();
		return call_user_func_array(array(j::$Options,"SaveSession"),$a);	    
	}
	static function DeleteUser($Name,$ID=null)
	{
		$a=func_get_args();
		return call_user_func_array(array(j::$Options,"Delete"),$a);	    
	}
	
	static function LoadSession($Name)
	{
		$a=func_get_args();
		return call_user_func_array(array(j::$Options,"LoadSession"),$a);	    
	}
	static function DeleteSession($Name)
	{
		$a=func_get_args();
		return call_user_func_array(array(j::$Options,"DeleteSession"),$a);	    
	}
    
    ###### Log Section ######
    static function Log ($Subject, $LogData, $Severity = 0)
    {
        return j::$Log->Log($Subject, $LogData, $Severity);
    }
    ###### DBAL Section ######
    /**
     * Runs a SQL query in the database and retrieves result (via DBAL)
     *
     * @param String $Query
     * @param optional $Param1 (could be an array)
     * @return mixed
     */
    static function SQL ($Query, $Param1 = null)
    {
    	$args=&func_get_args();
    	if (is_array($Param1))
    	{
			$args=$Param1;
			array_unshift($args,$Query);
    	}
        return call_user_func_array(array(j::$DB , "Execute"), $args);
    }
    
    static function AffectedRows()
    {
    	return j::$DB->AffectedRows();	
    }
    
    ##### Session Section #####
    static function UserID ()
    {
        return j::$Session->UserID();
    }
    static function Username ()
    {
        return j::$Session->Username();
    }
    static function Login ($Username, $Password,$Force=null)
    {
        return j::$Session->Login($Username, $Password,$Force);
    }
    static function Logout ()
    {
        j::$Session->Logout();
    }
    
    ##### RBAC ######
    static function Check($Permission,$UserID=null)
    {
    	return j::$RBAC->Check($Permission,$UserID);
    }
    static function Enforce($Permission)
    {
    	return j::$RBAC->Enforce($Permission);
    }
    
    static function Register($Path,$Value,$Readonly=false)
    {
    	return j::$Registry->Set($Path,$Value,$Readonly);
    }
    static function Registry($Path)
    {
		return j::$Registry->Get($Path);
    }
    static function RootDir()
    {
    	$r=new jpRoot();
    	return $r->__toString();
    }
    static function NoHTML(&$Var)
    {
    	return j::$Security->NoHTML($Var);
    }
    static public function __Initialize (ApplicationController $App)
    {
        j::$App = &$App;
        j::$DB = &$App->DB;
        j::$Registry = &$App->Registry;
        j::$Session = &$App->Session;
        j::$Services = &$App->Services;
        j::$Tracker = &$App->Tracker;
        j::$Options = &$App->Options;
        j::$Log = &$App->Log;
        j::$RBAC = &$App->RBAC;
        j::$Security=&$App->Security;
    }

}
?>