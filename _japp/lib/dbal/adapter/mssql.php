<?php

class DBAL_MSSQL extends DBAL_Base implements jFramework_DBAL
{
	
	/**
	 * 
	 * Last result handle
	 * @var resource
	 */
	public $Result;
	
	/**
	 * 
	 * Connection handle resource
	 * @var unknown_type
	 */
	public $Connection;
	/**
	 * Debug mode. if set to true DBAL is intended to generate debug output
	 * @var boolean
	 */
	public $Debug=false;
	
	/**
	 * last prepared statement
	 * @var 
	 */
	public $Statement;
	
	protected $m_username, $m_password, $m_databasename;

	public $Charset="utf8";
	function __construct($Username, $Password, $DatabaseName, $Host = "localhost")
	{
		if ($Username && $Username != "")
		{
			$this->Connection=mssql_connect($Host,$Username,$Password);
			if (!$this->Connection)
			{
				trigger_error("MSSQL connect error : ".$e);
			}
			mssql_select_db($DatabaseName);
// 			if (isset($this->Charset)) $this->DB->set_charset($this->Charset);
		}
		else
		{
			$this->Connection = null; //this is mandatory for no-database jFramework        
		}
		$this->m_username = $Username;
		$this->m_password = $Password;
		$this->m_databasename = $DatabaseName;
	}

	function __destruct()
	{
		if ($this->Connection) 
			mssql_close($this->Connection);
	}

	function LastID()
	{
		$r=$this->AutoQuery("SELECT SCOPE_IDENTITY() AS Result");
		return $r[0]['Result'];
		
	}

	function Escape()
	{
		
	}

	function Query($QueryString)
	{
		if (! $this->Connection) return null;
		$this->Statement=null;
		$this->QueryCount += 1;
		$this->Result =	mssql_query($QueryString,$this->Connection);
	}

	function NextResult()
	{
		if ($a=mssql_fetch_assoc($this->Result))
		{
			return $a;
		}
		else
			return false;
	}

	function AllResult()
	{
		$out = array ();
		while ( null != ($r = mssql_fetch_assoc($this->Result)) )
			$out [] = $r;
		if (count($out)==0)
			return false;
		return $out;
	}
	function AutoQuery($Query)
	{
		$this->Query($Query);
		return $this->AllResult();
	}
	function ResultCount()
	{
		if ($this->Statement)
			return $this->Statement->ResultCount();
		else
		{
			return mssql_num_rows($this->Result);
		} 
	}

	function AffectedRows()
	{
		if ($this->Statement)
			return $this->Statement->AffectedRows();
		else
		
			return mssql_rows_affected($this->Connection) ;
	}
	
	function Execute()
	{
		if (! $this->Connection) return null;
		$args = func_get_args ();
		if (count($args)==1)
		{
			return $this->AutoQuery($args[0]);
		}
		else
		{
			$Query=array_shift($args);
			foreach ($args as $a)
			{
				$p=strpos($Query, "?");
				$Query=substr_replace($Query, "'{$arg}'", $p,1);
			}
			return $this->AutoQuery($Query);
			
		}
	}

	function Prepare($Query)
	{
		trigger_error("NOT SUPPORTED");
	}
}

