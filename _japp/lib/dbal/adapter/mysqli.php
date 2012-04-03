<?php


//TODO: add some logic to Execute to run Queries without ? as AutoQuery.
#FIXME: If the result set has fields with the same title, they will overlap. Do sth!
class DBAL_MySQLi extends DBAL_Base implements jFramework_DBAL
{
	/**
	 * 
	 * @var mysqli
	 */
	public $DB;
	public $Result;
	
	/**
	 * Debug mode. if set to true DBAL is intended to generate debug output
	 * @var boolean
	 */
	public $Debug=false;
	
	/**
	 * last prepared statement
	 * @var DBAL_MySQLi_PreparedStatement
	 */
	public $Statement;
	
	protected $m_username, $m_password, $m_databasename;

	public $Charset="utf8";
	function __construct($Username, $Password, $DatabaseName, $Host = "localhost")
	{
		if ($Username && $Username != "")
		{
			$this->DB = new mysqli ( $Host, $Username, $Password, $DatabaseName );
			if (mysqli_connect_errno ()) trigger_error ( "Unable to connect to MySQLi database." );
			if (isset($this->Charset)) $this->DB->set_charset($this->Charset);
		}
		else
		{
			$this->DB = null; //this is mandatory for no-database jFramework        
		}
		$this->m_username = $Username;
		$this->m_password = $Password;
		$this->m_databasename = $DatabaseName;
	}

	function __destruct()
	{
		if ($this->DB) $this->DB->close ();
	}

	function LastID()
	{
		return $this->DB->insert_id;
	}

	function Escape()
	{
		$args = func_get_args ();
		foreach ( $args as $arg )
			$this->DB->real_escape_string ( $arg );
	}

	function Query($QueryString)
	{
		if (! $this->DB) return null;
		$this->Statement=null;
		$this->QueryCount += 1;
		$this->Result = $this->DB->query ( $QueryString );
	}

	function NextResult()
	{
		if ($this->ResultCount ())
			return $this->Result->fetch_array ( MYSQLI_ASSOC );
		else
			return false;
	}

	function AllResult()
	{
		if ($this->ResultCount ())
		{
			$out = array ();
			while ( null != ($r = $this->Result->fetch_array ( MYSQLI_ASSOC )) )
				$out [] = $r;
			return $out;
		}
		else
			return false;
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
			return $this->Result->num_rows;
	}

	function AffectedRows()
	{
		if ($this->Statement)
			return $this->Statement->AffectedRows();
		else
			return $this->DB->affected_rows ;
	}
	
	function Execute()
	{
		if (! $this->DB) return null;
		$args = func_get_args ();
		if (count($args)==1)
		{
			return $this->AutoQuery($args[0]);
		}
		else
		{
			
			$this->Statement = new DBAL_MySQLi_PreparedStatement ( $this );
			return call_user_func_array ( array (
				$this->Statement, "AutoQuery" 
				), $args );
		}
	}

	function Prepare($Query)
	{
		if (!$this->DB) return new DBAL_MySQLi_PreparedStatement(null);
		$this->Statement = new DBAL_MySQLi_PreparedStatement ( $this );
		return $this->Statement->Prepare ( $Query );
	}
}


class DBAL_MySQLi_PreparedStatement
{
	/**
	 * DBAL
	 *
	 * @var DBAL_MySQLi
	 */
	private $DBAL;
	/**
	 * Enter description here...
	 *
	 * @var MySQLi_STMT
	 */
	private $Statement;
	protected $DB;

	function __construct(DBAL $DB=null, $Query = null)
	{
		$this->DB = $DB->DB;
		$this->DBAL = $DB;
		if ($Query) $this->Prepare ( $Query );
	}

	/**
	 * Prepares a query, binds variables, Fetches result on select, returns ID on insert.
	 *
	 * @param String $Query
	 * @return Integer on INSERT, 2D Array on SELECT
	 */
	function AutoQuery($Query)
	{
		if (!$this->DBAL) return;
		$IID = false;
		$this->Prepare ( $Query );
		if (substr ( strtoupper ( $Query ), 0, 6 ) == "INSERT" or substr ( strtoupper ( $Query ), 0, 7 ) == "REPLACE") $IID = true;
		$args = func_get_args ();
		array_shift ( $args );
		call_user_func_array ( array (
			$this, "Execute" 
		), $args );
		
		if ($this->DBAL->Debug)
		{
			$DebugStr=$Query;
			foreach ($args as $arg)
			{
				$x=strpos($DebugStr,"?");
				$DebugStr=substr_replace($DebugStr,'"'.str_replace(array("'",'"'),array("\\'",'\\"'),
					$arg).'"',$x,1);
			}
			echo $DebugStr.BR;
		}
		
		if (! $IID)
			return $this->AllResult ();
		else
			return $this->LastID ();
	}

	/**
	 * Prepares a Query and returns the PreparedStatement object
	 *
	 * @param String $QueryString
	 * @return PreparedStatement
	 */
	function Prepare($QueryString) //replace values with ?
	{
		if (!$this->DBAL) return;
		if (! $stmt = $this->DB->prepare ( $QueryString )) trigger_error ( "Unable to prepare statement: " . $QueryString . ", reason: <b>" . $this->DB->error . "</b>" );
		$this->Statement = $stmt;
		return $this;
	}

	function __destruct()
	{
		if ($this->Statement) $this->Statement->close ();
	}

	/**
	 * Binds a few variables to a prepared statement
	 *
	 */
	function Bind()
	{
		if (!$this->DBAL) return;
		$args = func_get_args ();
		$types = str_repeat ( "s", count ( $args ) );
		array_unshift($args,$types);
		//TODO: optimize this on PHP 5.3
		$a=array();
		foreach ($args as $k=>&$v)
			$a[$k]=&$v;
		call_user_func_array ( array (
			$this->Statement, 'bind_param' 
		), $a );
	}

	/**
	 * Executes the prepared statement using binded values. if you provide this function with
	 * arguments, Then those would be binded as well.
	 *
	 */
	function Execute()
	{
		if (!$this->DBAL) return;
		if (func_num_args () >= 1)
		{
			$args = func_get_args ();
			call_user_func_array ( array (
				$this, "Bind" 
			), $args );
		}
		$this->DBAL->QueryCount += 1;
		
		$this->DBAL->QueryTimeIn ();
		$this->Statement->execute ();
		$this->DBAL->QueryTimeOut ();
		//$this->Statement->store_result();
	

	}

	function ResultCount()
	{
		if (!$this->DBAL) return;
			return $this->Statement->num_rows;
	}

	function LastID()
	{
		if (!$this->DBAL) return;
		return $this->Statement->insert_id;
	}

	function NextResult()
	{
		if (!$this->DBAL) return;
		
		$data = $this->Statement->result_metadata ();
		$out = array ();
		$fields = array ();
		if (! $data) return null;
		while ( null != ($field = mysqli_fetch_field ( $data )) )
			$fields [] = &$out [$field->name];
		call_user_func_array ( array (
			$this->Statement, "bind_result" 
		), $fields );
		$this->Statement->fetch ();
		return (count ( $out ) == 0) ? null : $out;
	}

	function AllResult()
	{
		if (!$this->DBAL) return;
		$data = $this->Statement->result_metadata ();
		$out = array ();
		$fields = array ();
		if (! $data) return null;
		$length=0;
		while ( null != ($field = mysqli_fetch_field ( $data )) )
		{
			$fields [] = &$out [$field->name];
			$length+=$field->length;
		}
		call_user_func_array ( array (
			$this->Statement, "bind_result" 
		), $fields );
		$output = array ();
		$count = 0;
		//FIXME: store_result is needed, but using it causes crash
		if ($length>=1000000) 
			if(!$this->Statement->store_result())
				trigger_error("Store_Result error on MySQLi prepared statement : ".$this->Statement->get_warnings());
		while( $this->Statement->fetch () )
		{
			foreach ( $out as $k => $v )
				$output [$count] [$k] = $v;
			$count ++;
		}
		$this->Statement->free_result();
		return ($count == 0) ? null : $output;
	}
	function AffectedRows()
	{
		return $this->Statement->affected_rows;
	}
}
?>