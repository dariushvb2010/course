<?php
/**
 * ApplicationOptions class
 * @version 1.5
 *
 * Saves and Restores options for session,user and application on database.
 * version 1.5 beta (replaced constants with registry values)
 *
 * * The major difference of 1.4 is that
 * 	private core functions prepare database queries only once, so major speed improvement for
 * 	lots of options playing.
 */
//Note: userID = 0 means general option
class ApplicationOptions extends BaseApplicationClass
{
	private $PreparedLoadSetStatement=null;
	private $PreparedLoadStatement=null;
	private $PreparedSaveStatement=null;
	private $PreparedDeleteStatement=null;
	private $PreparedSweepStatement=null;
	private function _Save($Name, $Value, $UserID = 0, $Timeout)
	{
		$Datetime = time () + $Timeout;
		if (!$this->PreparedSaveStatement)    
	        $this->PreparedSaveStatement=$this->DB->Prepare( "REPLACE INTO `" . reg("jf/options/table/name") . "` (`" . reg("jf/options/table/OptionName") . "`,`" . reg("jf/options/table/OptionValue") . "`, `" . reg("jf/options/table/UserID") . "`, `" . reg("jf/options/table/ExpirationDate") . "`) VALUES (?,?,?,?);");
	    $this->PreparedSaveStatement->Execute( $Name, serialize ( $Value ), $UserID, $Datetime );
		$this->_Sweep ();
	}
	private function _Delete($Name, $UserID = 0)
	{
	    if (!$this->PreparedDeleteStatement)    
	        $this->PreparedDeleteStatement=$this->DB->Prepare( "DELETE FROM `" . reg("jf/options/table/name") . "` WHERE " . "`" . reg("jf/options/table/UserID") . "`=? AND `" . reg("jf/options/table/OptionName") . "`=?");
	    $this->PreparedDeleteStatement->Execute($UserID, $Name);
	}
	/**
	 * Loads an option from the database
	 *
	 * @param String $Name
	 * @param Integer $UserID 0 for General Options
	 * @return String Value on success, null on failure
	 */
	private function _Load($Name, $UserID = 0)
	{
	    $this->_Sweep ();
	    if (!$this->PreparedLoadStatement)    
	        $this->PreparedLoadStatement=$this->DB->Prepare("SELECT `" . reg("jf/options/table/OptionValue") . "` FROM `" . reg("jf/options/table/name") . "` WHERE " . "`" . reg("jf/options/table/UserID") . "`=? AND `" . reg("jf/options/table/OptionName") . "`=?");
	    $this->PreparedLoadStatement->Execute($UserID, $Name);
	    $Res=$this->PreparedLoadStatement->AllResult();
	    if ($Res )
		{
		    return unserialize ( $Res [0] [reg("jf/options/table/OptionValue")] );
		} 
		else
			return null;
	}
	private function _LoadSet($Prefix,$UserID=0)
	{
	    $this->_Sweep ();
	    if (!$this->PreparedLoadSetStatement)    
	        $this->PreparedLoadSetStatement=$this->DB->Prepare("SELECT * FROM `" . reg("jf/options/table/name") . "` WHERE " . "`" . reg("jf/options/table/UserID") . "`=? AND `" . reg("jf/options/table/OptionName") . "` LIKE ?");
	    $this->PreparedLoadStatement->Execute($UserID, $Prefix);
	    $Res=$this->PreparedLoadStatement->AllResult();
		return $Res;
	}
	private function _Sweep()
	{
		
		if (rand ( 0, 1000 ) / 1000.0 > reg("jf/options/SweepRatio"))
			return; //percentage of SweepRatio, don't always do this when called

	    if (!$this->PreparedSweepStatement)    
	        $this->PreparedSweepStatement=$this->DB->Prepare("DELETE FROM `" . reg("jf/options/table/name") . "` WHERE `" . reg("jf/options/table/ExpirationDate") . "`<=?");
	    $this->PreparedSweepStatement->Execute(time());
			
	
	}
	/**
	 * This function loads a set of options together
	 * It expects to get at least 2 parameters
	 * @param Integer $UserID
	 * @return AssociativeArray of option Name/Value pairs as Key/Value in the array.
	 * 
	 */
	
	function _Loads($UserID)
	{
	    if (func_num_args()<2) return false;
	    $Params=func_get_args();
	    array_shift($Params); //rid UserID
	    $flag=true;
	    foreach ($Params as $k=>$v)
	    {
	        if ($flag)
	            $flag=false;
	        else 
	            $Q.=" OR ";
	        $Q.="`".reg("jf/options/table/OptionName")."`=?";
	    }
	    
		$Res = 
		call_user_func_array(array($this->DB,"Execute"),
		 array_merge(array("SELECT `".reg("jf/options/table/OptionName")."`,`" . reg("jf/options/table/OptionValue") . "` FROM `" . reg("jf/options/table/name") . "` WHERE " . "`" . reg("jf/options/table/UserID") . "`=? ".
			" AND ($Q)"), array($UserID),$Params));
		if (count ( $Res ))
		{
			foreach ($Res as $k=>$v)
			    foreach ($v as $k2=>$v2)
			    {
			        if ($k2==reg("jf/options/table/OptionValue")) 
			        {
			            $Res[$k][$k2]=unserialize($v2);
			            $Out[$Res[$k][reg("jf/options/table/OptionName")]]=$Res[$k][$k2];
			        }
			    }
		    return $Out;
		} 
		else
			return null;
	    
	    
	}
	function SaveGeneral($Name, $Value, $Timeout = null)
	{
		if ($Timeout===null ) $Timeout=reg("jf/options/timeout/default");
		$this->_Save ( $Name, $Value, 0, $Timeout );
	}
	function LoadGeneral($Name)
	{
		return $this->_Load ( $Name, 0 );
	}
	function LoadSetGeneral($Prefix)
	{
		return $this->LoadSet($Prefix,0);
	}
	function Save($Name, $Value,$UserID=null, $Timeout = null)
	{
		if ($UserID===null)
		{
			if ($this->App->Session->UserID == null)
				$this->App->FatalError ( "Can not load user options without a logged in user." );
			else
				$UserID=$this->App->Session->UserID;
		}
		
		if ($Timeout===null ) $Timeout=TIMESTAMP_WEEK;
		
		$this->_Save ( $Name, $Value, $UserID, $Timeout );
	}
	function Load($Name,$UserID=null)
	{
		if ($UserID===null)
		{
			if ($this->App->Session->UserID == null)
				$this->App->FatalError ( "Can not load user options without a logged in user." );
			else
				$UserID=$this->App->Session->UserID;
		}
		return $this->_Load ( $Name, $UserID );
	
	}
	function LoadSet($Prefix)
	{
		if ($this->App->Session->UserID == null)
			$this->App->FatalError ( "Can not load user options without a logged in user." );
		return $this->_LoadSet ( $Prefix, $this->App->Session->UserID );
	}
	function Delete($Name,$UserID=null)
	{
		if ($UserID===null)
		{
			if ($this->App->Session->UserID == null)
				$this->App->FatalError ( "Can not delete user options without a logged in user." );
			else
				$UserID=$this->App->Session->UserID;
		}
		$this->_Delete ( $Name, $UserID );
	}
	function DeleteAll()
	{
		if ($this->App->Session->UserID == null)
			$this->App->FatalError ( "Can not delete user options without a logged in user." );
		$this->Execute (  "DELETE FROM `" . reg("jf/options/table/name") . "` WHERE " . "`" . reg("jf/options/table/UserID") . "`=?", $this->App->Session->UserID );
	}
	function DeleteGeneral($Name)
	{
		$this->_Delete ( $Name, 0 );
	}
	function SaveSession($Name,$Value,$Timeout = null)
	{
		if ($Timeout===null) $Timeout=reg("jf/session/timeout/General");
	    $this->SaveGeneral(session_id()."_$Name",$Value,$Timeout);   
	}
	function LoadSession($Name)
	{
	     return $this->LoadGeneral(session_id()."_$Name");   
	}
	function LoadSetSession($Prefix)
	{
		return $this->LoadSetGeneral(session_id()."_{$Prefix}");
	}
	function DeleteSession($Name)
	{
        $this->DeleteGeneral(session_id()."_$Name");
	}
}

?>
