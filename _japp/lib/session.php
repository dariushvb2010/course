<?php

/*
 * jFramework Session Manager
 * @author AbiusX@jFramework.info
 * @version 1.5.96
=======
/*
 * jFramework Session Manager
 * @author AbiusX@jFramework.info
 * @version 1.5.96
>>>>>>> .r442
 */
/*
	note:
	this session class builds up a session for every user visiting the site. 
	Those users who have logged in properly (using the login method) have their UserID set.
	Guest users have their UserID set to null;

	There are three SessionStates
	SessionState_New : only the first time a user visits the site
	SessionState_Live : Times after the first time
	SessionState_Expired : if a user does not use the system for an amount of time, This will happen once. The next
		time the same user visits the site, It's state would be New, since the last (expired) time its session was removed.
 */
#FIXME: use updating and etc. using SessionID since a user can be logged in multiple times
# also add option to only allow one login of the user
/**
This class Handles user sessions and user management (credentials).
 */
class SessionManager
{
	/**
	 * 
	 * @var DBAL
	 */
	private $DB;

	function __construct(DBAL $DB)
	{
		$this->DB = $DB;
		$this->IP = (getenv ( "HTTP_X_FORWARDED_FOR" )) ? getenv ( "HTTP_X_FORWARDED_FOR" ) : getenv ( "REMOTE_ADDR" );
		
		session_name ( reg("jf/session/name") );
		if (!session_start ())
			trigger_error("Unable to start session!");
		$this->ManageSession ();
		session_write_close (); //This would release the session_start lock and prevent request sequentialization
	}
	public $IP;
	/**
	The state of a session can be 
		(1) SessionState_New for a session which is just begun (happens once for every visitor),
		(2) SessionState_Live for a session which is established (all times except the first one),
		(3) SessionState_Expired for a session which is timed out due to no visits by visitor in a defined period (happens once, on the next visit, state would be new again).
		You can use these to for example determine when the user's session is expired and redirect him to login again.
		
	 */
	public $SessionState;
	/**
	If a user is logged in, This variable holds his UserID, else it would be null.
	 */
	public $UserID;

	/**
	Manages the current session. It checks the session, Sets appropriate values and updates the session if expired,
		Creates the session if new and so on.
	 */
	function ManageSession()
	{
		$this->CheckSession ();
		if ($this->SessionState == reg("jf/session/state/new"))
		{
			$this->CreateSession ();
		}
		elseif ($this->SessionState == reg("jf/session/state/expired"))
		{
			$this->Logout ();
		}
	}

	/**
	 * returns Username of a user
	 *
	 * @param Integer $UserID leave null for logged in user
	 * @return String
	 */
	function Username($UserID = null)
	{
		if (! $UserID) $UserID = $this->UserID;
		if (! $UserID) return null;
		$Result = $this->DB->Execute ( "SELECT `" . reg("jf/users/table/Username") . "` FROM `" . reg("jf/users/table/name") . "` WHERE " . reg("jf/users/table/UserID") . "=?", $UserID );
		if ($Result)
			return $Result [0] [reg("jf/users/table/Username")];
		else
			return null;
	}

	/**
	Checks the session and sets the SessionState variable accordingly
    @return 
		0 on New Session,
		1 on Existing Session
	 */
	function CheckSession()
	{
		$SessionID = session_id ();
		$Result = $this->DB->Execute ( "SELECT * FROM `" . reg("jf/session/table/name") . "` WHERE " . reg("jf/session/table/SessionID") . "=?", $SessionID );
		if (! $Result)
		{
			$this->SessionState = reg("jf/session/state/new");
			return 0;
		}
		if (count ( $Result ) == 1)
		{
			$Result = $Result [0];
			$this->UserID = $Result [reg("jf/session/table/UserID")];
			if ($this->UserID == 0) $this->UserID = null;
			$LoginDate = $Result [reg("jf/session/table/LoginTimestamp")];
			$LastAccess = $Result [reg("jf/session/table/LastAccessTimestamp")];
			$LoginTimestamp = $LoginDate; //strtotime($LoginDate);
			$LastAccessTimestamp = $LastAccess; //strtotime($LastAccess);
			$NowTimestamp = time ();
			$Dis = $NowTimestamp - $LastAccessTimestamp;
			$LoginTime = $NowTimestamp - $LoginTimestamp;
			if ($Dis > reg("jf/session/timeout/NoAccess") or $LoginTime > reg("jf/session/timeout/General"))
			{
				$this->SessionState = reg("jf/session/state/expired");
			}
			else
			{
				$this->SessionState = reg("jf/session/state/live");
			}
			$this->DB->Execute ( "UPDATE `" . reg("jf/session/table/name") . "` SET `" . reg("jf/session/table/LastAccessTimestamp") . "`=? ,`" . reg("jf/session/table/AccessCount") . "`=`" . reg("jf/session/table/AccessCount") . "`+1 , `".reg("jf/session/table/Request")."`=? WHERE `" . reg("jf/session/table/SessionID") . "`=?", time (), reg("request/raw"),session_id () );
			$this->_Sweep ();
			return 1;
		}
		return - 1;
	}

	/**
	Removes outdated session info from sessions table
	 */
	private function _Sweep()
	{
		//Removes timed out sessions
		if (rand ( 0, 1000 ) / 1000.0 > reg("jf/session/SweepRatio")) return; //10%
		$Now = time ();
		$this->DB->Execute ( "DELETE FROM `" . reg("jf/session/table/name") . "` WHERE `" . reg("jf/session/table/LastAccessTimestamp") . "`<?-? OR `" . reg("jf/session/table/LoginTimestamp") . "`<?-?", $Now, reg("jf/session/timeout/NoAccess"), $Now, reg("jf/session/timeout/General"));
	}

	/**
	Validates a user credentials
    @param Username of the user
    @param Password of the user
    @return Array UserID,Username and Password on success
		null on Invalid Credentials
	 */
	function ValidateUserCredentials($Username, $Password)
	{
		$Res = $this->DB->Execute ( "SELECT * FROM `" . reg("jf/users/table/name") . "` WHERE LOWER(" . reg("jf/users/table/Username") . ")=LOWER(?) AND " . reg("jf/users/table/Password"). "=?", $Username, $this->PasswordHashString ( $Username, $Password ) );
		if ($Res)
			return $Res [0];
		else
			return null;
	}

	/**
	Logs in a user if credentials are valid
    @param Username of the user
    @param Password of the user
    @return UserID on success
		false on Invalid Credentials
	 */
	function Login($Username, $Password,$Force=false)
	{
		if (!$Force)
		{
			$Result = $this->ValidateUserCredentials ( $Username, $Password );
		}
		else 
		{
			$Result = 	$this->DB->Execute ( "SELECT * FROM `" . reg("jf/users/table/name") . "` WHERE LOWER(" . reg("jf/users/table/Username") . ")=LOWER(?) ", $Username);
			if ($Result)
				$Result=$Result[0];
		}
		if (!$Result) return false;
		$this->CheckSession();
		if ($this->SessionState == reg("jf/session/state/new"))
		{
			$IID=$this->DB->Execute ( "INSERT INTO `" . reg("jf/session/table/name") . "` (`" . reg("jf/session/table/UserID") . "`,`" . reg("jf/session/table/SessionID") . "`,`" . reg("jf/session/table/LoginTimestamp") . "`,`" . reg("jf/session/table/LastAccessTimestamp") . "`,`" . reg("jf/session/table/IP") . "` ) VALUES (?,?,?,?,?)", $Result [reg("jf/users/table/UserID")], session_id (), time (), time (), $this->IP);
			if (!$IID)
				trigger_error("Unable to save session!");
		}
		else
			$this->DB->Execute ( "UPDATE `" . reg("jf/session/table/name") . "` SET `" . reg("jf/session/table/UserID") . "`=?,`" . reg("jf/session/table/SessionID") . "`=?,`" . reg("jf/session/table/LoginTimestamp") . "`=?,`" . reg("jf/session/table/LastAccessTimestamp") . "`=?,`" . reg("jf/session/table/AccessCount") . "`=?" . " WHERE `" . reg("jf/session/table/SessionID") . "`=?", $Result [reg("jf/users/table/UserID")], session_id (), time (), time (), 1, session_id () );
		$this->CheckSession ();
		return $Result [reg("jf/users/table/UserID")];
	}

	/**
	Checks to see whether a user exists or not
    @param Username of the new user
    @return null on user not exists, 2D array on else
	 */
	function UserExists($Username)
	{
		return $this->DB->Execute ( "SELECT * FROM `" . reg("jf/users/table/name") . "` WHERE LOWER(`" . reg("jf/users/table/Username") . "`)=LOWER(?)", $Username );
	}

	/**
    Creates a new user in the system
    @param Username of the new user
    @param Password of the new user
    @return UserID on success
		null on User Already Exists
	 */
	function CreateUser($Username, $Password)
	{
		$Result = $this->UserExists ( $Username );
		if ($Result) return null;
		$Result = $this->DB->Execute ( "INSERT INTO `" . reg("jf/users/table/name") . "` (`" . reg("jf/users/table/Username") . "`,`" . reg("jf/users/table/Password"). "`) VALUES (?,?)", $Username, $this->PasswordHashString ( $Username, $Password ) );
		return $Result;
	}

	/**
    Returns the hash that is set as user password in database
    @param Username of the user
    @param Password of the user
    @return SHA-512 Hash of the Username (in lower case),Password and registry value of "jf/users/HashConcat
	 */
	function PasswordHashString($Username, $Password)
	{
		return hash ( "sha512", (strtolower ( $Username ) . reg("jf/users/HashConcat") . $Password) );
	}

	/**
    Removes a user form system users if exists
    @param Username of the user
	 */
	function RemoveUser($Username)
	{
		$this->DB->Execute ( "DELETE FROM `" . reg("jf/users/table/name") . "` WHERE LOWER(`" . reg("jf/users/table/Username") . "`)=LOWER(?)", $Username );
	}

	/**
    Edits a user credentials
    @param String $OldUsername 
    @param String $NewUsername 
    @param String $NewPassword leave null to not change
    @return null on old user doesn't exist, false on new user already exists,  true on success.
	 */
	function EditUser($OldUsername, $NewUsername, $NewPassword = null)
	{
		if (! $this->UserExists ( $OldUsername )) return null;
		if ($OldUsername != $NewUsername and $this->UserExists ( $NewUsername )) return false;
		if ($NewPassword)
		{
			$this->DB->Execute ( "UPDATE `" . reg("jf/users/table/name") . "` SET `" . reg("jf/users/table/Username") . "`=?, `" . reg("jf/users/table/Password") . "`=? WHERE LOWER(`" . reg("jf/users/table/Username") . "`)=LOWER(?)", $NewUsername, $this->PasswordHashString ( $NewUsername, $NewPassword ), $OldUsername );
		}
		else
		{
			$this->DB->Execute ( "UPDATE `" . reg("jf/users/table/name") . "` SET `" . reg("jf/users/table/Username") . "`=? WHERE LOWER(`" . reg("jf/users/table/Username") . "`)=LOWER(?)", $NewUsername, $OldUsername );
		}
		return true;
	}

	/**
	 * Destroys the current session,Hence logging out the user. Then recreates the session.
	 * If UserID provided, destroys the session for that user 
	 * @param $UserID
	 */
	function Logout($UserID=null)
	{
		if ($UserID===null)
		{
			$this->DestroySession ($UserID);
			$this->CreateSession (); // this should be omitted, otherwise two sessions would be made for the user
			$this->CheckSession ();
		}
		else
		{
			$this->DB->Execute ( "DELETE FROM `" . reg("jf/session/table/name") . "` WHERE `" . reg("jf/session/table/UserID") . "`=? ", $UserID );
		}
	}

	/**
    Creates a new session (registers) for current visitor.
	 */
	function CreateSession()
	{
		$this->DB->Execute ( "INSERT INTO `" . reg("jf/session/table/name") . "` (`" . reg("jf/session/table/UserID") . "`,`" . reg("jf/session/table/SessionID") . "`,`" . reg("jf/session/table/LoginTimestamp") . "`,`" . reg("jf/session/table/LastAccessTimestamp") . "`,`" . reg("jf/session/table/IP") . "`) VALUES (?,?,?,?,?)", 0, session_id (), time (), time (), $this->IP );
	}

	/**
    Destroys current session of the visitor
	 */
	/**
	 * Destroys session for user specified
	 * @param $UserID
	 */
	function DestroySession($UserID=null)
	{
		if (isset ( $_COOKIE [session_name ()] ))
		{
			setcookie ( session_name (), '', time () - 42000, '/' );
		}
		$this->UserID = null;
		session_start ();
		session_regenerate_id ( true );
	}

	/**
	 * Returns the number of online visitors based on established sessions.
	 *
	 * @return Integer Number of online visitors
	 */
	function OnlineVisitors()
	{
		$Result = $this->DB->Execute ( "SELECT COUNT(*) AS C FROM `" . reg("jf/session/table/name") . "`" );
		return $Result [0] ["C"];
	}

	/**
	 * returns current UserID, null on not user logged in
	 * @return Integer or null
	 */
	function UserID()
	{
		return $this->UserID;
	}
	/**
	 * Tells whether or not a user is logged in
	 * @param Integer $UserID
	 * @return Boolean
	 */
	function IsLoggedIn($UserID)
	{
		$Result=$this->DB->Execute("SELECT COUNT(*) AS Result FROM `" . reg("jf/session/table/name") . "` WHERE `" . reg("jf/session/table/UserID") . "`=?",$UserID);
		if ($Result[0]['Result']>=1) return true;
		else return false;
	}
}
?>