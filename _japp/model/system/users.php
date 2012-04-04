<?php
class SystemUsersModel extends BaseApplicationClass 
{
    function AllSessions()
    {
        return $this->DB->Execute("SELECT S.*,U.`".reg("jf/users/table/Username")."` FROM `".reg("jf/session/table/name")."` AS S LEFT JOIN `".reg("jf/users/table/name")."` AS U ON (
        	S.`".reg("jf/session/table/UserID")."`=U.`".reg("jf/users/table/UserID")."`
        	)
        ");
    }
    function AllUsers($offset=null,$limit=null)
    {
        if ($offset or $limit)
            return $this->DB->Execute("SELECT * FROM `".reg("jf/users/table/name")."` LIMIT ?,?",$offset,$limit);
        else
            return $this->DB->Execute("SELECT * FROM `".reg("jf/users/table/name")."` ");
    }
    function User($UserID)
    {
         return $this->DB->Execute("SELECT `".reg("jf/users/table/UserID")."` AS ID, `".reg("jf/users/table/Username")."` AS Username
         	FROM `".reg("jf/users/table/name")."` WHERE ID=?",$UserID);   
    }
    function UserCount()
    {
        $Result=$this->DB->Execute("SELECT COUNT(*) FROM `".reg("jf/users/table/name")."` ");
        return $Result[0]["COUNT(*)"];
    }
}
?>