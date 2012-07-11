<?php
/**
 * jURL Fundumental Class
 * This is intended to replace every URL in jFramework.
 * a URL is a unique resource locator in the outside world of the jFramework Application.
 * External URLs (aka Request URLs) are those of the world and the browser
 * Internal URLs are internal links to jFramework resources (e.g script.something.js)
 * @version 1.2.1
 */


class jURL_Server
{
	/**
	 * Returns the current URL of the browser
	 *
	 * @return String URL
	 */
	static function URL ($QueryString=true)
	{
		
		if ($QueryString && jURL::QueryString() )
			return (jURL::Protocol()."://".jURL::ServerName().jURL::PortReadable().jURL::RequestURI()."?".jURL::QueryString());
		else
			return (jURL::Protocol()."://".jURL::ServerName().jURL::PortReadable().jURL::RequestURI());
	}			
	/**
	 * Port of client connection
	 *
	 * @return String Port
	 */
	static function Port ()
	{
		return isset($_SERVER['SERVER_PORT'])?$_SERVER['SERVER_PORT']:"";
	}
	static function PortReadable()
	{
		$port=jURL::Port();
		if ($port=="80" && strtolower(jURL::Protocol())=="http")
		$port="";
		else if ($port=="443" && strtolower(jURL::Protocol())=="https")
		$port="";
		else
		$port=":".$port;
		return $port;
	}
	/**
	 * Protocol of client connection, HTTP or HTTPS
	 *
	 * @return String Protocol
	 */
	static function Protocol ()
	{
		if (isset($_SERVER['HTTPS']))
		$x = $_SERVER['HTTPS'];
		else
			$x="";
		if ($x=="off" or $x=="")
		return "http";
		else
		return "https";
	}
	/**
	 * Request Path, e.g http://somesite.com/this/is/the/request/path/index.php
	 *
	 * @return String RequestPath
	 */
	static function RequestPath ()
	{
		if (isset($_SERVER['REDIRECT_URL']))
		{
			$x = $_SERVER["REDIRECT_URL"];
			$a=explode("/", $x);
			$x = array_pop($a);
			return substr($_SERVER["REDIRECT_URL"], 0, strlen($_SERVER["REDIRECT_URL"]) - strlen(constant("REQUEST")));
		}
		else
		{
			if (jURL::QueryString())
			return substr($_SERVER['REQUEST_URI'], 0, strlen($_SERVER['REQUEST_URI']) - strlen(jURL::QueryString()) - 1);
			else
			return isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:"";
		}
	}
	/**
	 * Contains RequestPath+RequestFile+QueryString formatted as the browser URL
	 */
	static function RequestURI()
	{
		if (isset($_SERVER['REDIRECT_URL']))
		return $_SERVER["REDIRECT_URL"];
		else
		return $_SERVER['REQUEST_URI'];
	}

	/**
	 * HTTP Host, aka Domain name
	 *
	 * @return String HTTPHost
	 */
	static function HTTPHost ()
	{
		if (isset($_SERVER['HTTP_HOST']))
			return $_SERVER['HTTP_HOST'];
		else
			return "";
	}
	static function ServerName()
	{
		if (isset($_SERVER['SERVER_NAME']))
			return $_SERVER['SERVER_NAME'];
		else
			return "";
		
	}
	/**
	 * Request method, either GET/POST
	 *
	 * @return String RequestMethod
	 */
	static function RequestMethod ()
	{
		return $_SERVER['REQUEST_METHOD'];
	}
	/**
	 * Requested file, the last part in URL between / and ?
	 *
	 * @return String RequestFile
	 */
	static function RequestFile ()
	{
		if (isset($_SERVER['REDIRECT_QUERY_STRING']))
		{
			$a=explode("&", $_SERVER['REDIRECT_QUERY_STRING']);
			$x = array_shift($a);
			$x = explode("=", $x);
			$x=$x[1];
			return $x;
		}
		else
		return ""; //index.php
	}
	/**
	 * Query String, the last part in url after ? (not including jFramework request)
	 *
	 * @return String QueryString
	 */
	static function QueryString ()
	{
		if (isset($_SERVER['REDIRECT_QUERY_STRING']))
		{
			$a=explode("&", $_SERVER['REDIRECT_QUERY_STRING']);
			$x = array_shift($a);
			return substr($_SERVER['REDIRECT_QUERY_STRING'], strlen($x) + 1);
		}
		else
		return isset($_SERVER['QUERY_STRING'])?$_SERVER['QUERY_STRING']:"";
	}
	/**
	 * Root of website without trailing slash
	 *
	 * @return String SiteRoot
	 */
	static function Root ()
	{
		$x=jURL::Protocol() . "://" . jURL::HTTPHost(). jURL::RequestPath();
		return substr($x,0,strlen($x)-1);
	}

	static function Request()
	{
		return jURL::RequestFile() . (jURL::QueryString() ? "?" . jURL::QueryString() : "");
	}
}

class jURL extends jURL_Server
{


}
?>