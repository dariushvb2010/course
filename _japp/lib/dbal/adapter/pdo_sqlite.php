<?php


/**
 * jFramework PDO_SQLite 3.0 adapter
 * @author abiusx
 * @version 1.00
 */
class DBAL_PDO_SQLite extends DBAL_Base implements jFramework_DBAL
{
	/**
	 * the actual DB object
	 * @var PDO
	 */
	public $DB;
	/**
	 * 
	 * @var PDOStatement
	 */
	public $Result;
	protected $m_username, $m_password, $m_databasename;
	
	function __construct($Username, $Password, $Database, $Host = "localhost")
	{
		if ($Username and $Username != "")
		{
			$File = constant ( "jf_DB_SQLite_Folder" ) . "{$Database}";
			try
			{
				$this->DB = new PDO ( "sqlite:" . $File );
				$this->DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
			} catch ( PDOException $e )
			{
				trigger_error ( "PDO_SQLite connection error: " . $e->getMessage () );
			}
			if (! is_writable ( $File ) && (! defined ( "jf_DB_ReadOnly" ) or ! constant ( "jf_DB_ReadOnly" ))) trigger_error ( "PDO_SQLite : database file not writable. Set read-only or grant write access to database file." );
			if (! is_writable(constant ( "jf_DB_SQLite_Folder" )) && (!defined( "jf_DB_ReadOnly" ) or ! constant ( "jf_DB_ReadOnly" ))) trigger_error("PDO_SQLite : the folder containing the database should have full permissions, or set connection to read-only.");
		}
		else
			$this->DB = null; //this is mandatory for no-database jFramework
		$this->m_username = null;
		$this->m_password = null;
		$this->m_databasename = $Database;
	}

	function __destruct()
	{
		if ($this->DB) $this->DB = null; //destroys the PDO object
	}

	function LastID()
	{
		return $this->DB->lastInsertId ();
	}

	function Escape()
	{
		$args = func_get_args ();
		foreach ( $args as &$arg )
			if ($x = $this->DB->quote ( $arg )) $arg = $x;
	}

	function Query($QueryString)
	{
		if (! $this->DB) return null;
		$this->QueryCount += 1;
		if ($this->Result)
		{
			$this->Result->closeCursor ();
			$this->Result = null;
		}
		return $this->Result = $this->DB->query ( $QueryString );
	}

	function NextResult()
	{
		return $this->Result->fetch ( PDO::FETCH_ASSOC );
	}

	function AllResult()
	{
		return $this->Result->fetchAll ( PDO::FETCH_ASSOC );
	}

	function ResultCount()
	{
		#this does not work in most cases of SELECT
		return $this->Result->rowCount ();
	}

	function AffectedRows()
	{
		return $this->DB->rowCount ();
	}

	function Execute()
	{
		if (! $this->DB) return null;
		$st = new DBAL_PDO_SQLite_PreparedStatement ( $this );
		$args = func_get_args ();
		return call_user_func_array ( array (
			$st, "AutoQuery" 
		), $args );
	}

	function Prepare($Query)
	{
		$st = new DBAL_PDO_SQLite_PreparedStatement ( $this );
		return $st->Prepare ( $Query );
	}
}


class DBAL_PDO_SQLite_PreparedStatement
{
	/**
	 * DBAL
	 *
	 * @var DBAL_PDO_SQLite
	 */
	private $DBAL;
	/**
	 * Enter description here...
	 *
	 * @var PDOStatement
	 */
	private $Statement;
	
	/**
	 * 
	 * @var PDO
	 */
	protected $DB;

	function __construct(DBAL $DB, $Query = null)
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
		$IID = false;
		$this->Prepare ( $Query );
		if (substr ( strtoupper ( $Query ), 0, 6 ) == "INSERT" or substr ( strtoupper ( $Query ), 0, 7 ) == "REPLACE") $IID = true;
		$args = func_get_args ();
		array_shift ( $args );
		call_user_func_array ( array (
			$this, "Execute" 
		), $args );
		if (! $IID)
			return $this->AllResult ();
		else
			return $this->LastID ();
	}

	/**
	 * Prepares a Query and returns the PreparedStatement object
	 *
	 * @param String $QueryString
	 * @return PreparedStatement_PDO_SQLite
	 */
	function Prepare($QueryString) //replace values with ?
	{
		if (! $stmt = $this->DB->prepare ( $QueryString ))
		{
			$Error = $this->DB->errorInfo ();
			trigger_error ( "Unable to prepare statement: " . $QueryString . ", reason: <b>" . $Error [2] . "</b>" );
		}
		$this->Statement = $stmt;
		return $this;
	}

	function __destruct()
	{
		if ($this->Statement) $this->Statement = null;
	}

	/**
	 * Binds a few values to a prepared statement
	 *
	 */
	function Bind()
	{
		$args = func_get_args ();
		$i = 0;
		foreach ( $args as &$arg )
			$this->Statement->bindValue ( ++ $i, $arg );
	}

	/**
	 * Executes the prepared statement using binded values. if you provide this function with
	 * arguments, Then those would be binded as well.
	 *
	 */
	function Execute()
	{
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
	
	}

	function ResultCount()
	{
		return $this->Statement->rowCount ();
	}

	function LastID()
	{
		return $this->DBAL->LastID ();
	}

	function NextResult()
	{
		return $this->Statement->fetch ( PDO::FETCH_ASSOC );
	}

	function AllResult()
	{
		return $this->Statement->fetchAll ( PDO::FETCH_ASSOC );
	}
}
?>