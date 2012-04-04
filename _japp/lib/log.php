<?php
class LogManager extends BaseApplicationClass  
{
	//TODO: add prepared statement
	function Log($Subject,$LogData,$Severity=0)
	{
		return $this->DB->Execute("INSERT INTO `".reg("jf/log/table/name")."` (`".reg("jf/log/table/Subject")."`,`".reg("jf/log/table/LogData")."`,`".reg("jf/log/table/Severity")."`,`".
		reg("jf/log/table/UserID")."`,`".reg("jf/log/table/SessionID")."`,`".reg("jf/log/table/Timestamp")."`) 
		"."VALUES (?,?,?,?,?,?)",$Subject,$LogData,$Severity,j::UserID(),session_id(),time());
	}
	
}


?>