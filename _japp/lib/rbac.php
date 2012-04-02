<?php
/*
 * 
 Role Based Access Control
	Implemented on NIST RBAC Standard Model
 		level 2. Hierarchical RBAC (Restricted Hierarchical RBAC : only trees of roles)
	by AbiusX[at]Gmail[dot]com

	Restricted Hierarchies for both Permissions and Roles.
	Multiple roles for each user
*/


/**
 * Role Based Access Control class.
 * defines, manages and implements user/role/permission relations, and role based access control. 
 * NIST RBAC Standard Model 2 (Hierarchical RBAC, trees of roles)
 * @verson 0.60
 */
class RBAC extends BaseApplicationClass 
		implements RBAC_Management ,RBAC_PermissionManagement,RBAC_RoleManagement,RBAC_UserManagement   
{
    /**
     * Permissions Nested Set
     *
     * @var FullNestedSet
     */
    public $Permissions;
    /**
     * Roles Nested Set
     *
     * @var FullNestedSet
     */
    protected $Roles=null;

    function __construct(ApplicationController $App=null)
    {
        parent::__construct($App);
        if ($this->DB) //for lazy load
        {
        $this->Permissions=new FullNestedSet($this->DB,reg("jf/rbac/tables/Permissions/table/name"),reg("jf/rbac/tables/Permissions/table/PermissionID"),
            reg("jf/rbac/tables/Permissions/table/PermissionLeft"),reg("jf/rbac/tables/Permissions/table/PermissionRight"));
        $this->Roles=new FullNestedSet($this->DB,reg("jf/rbac/tables/Roles/table/name"),reg("jf/rbac/tables/Roles/table/RoleID"),
            reg("jf/rbac/tables/Roles/table/RoleLeft"),reg("jf/rbac/tables/Roles/table/RoleRight"));
        }
    }
    
	###############################
	####### Roles Interface #######
	###############################
    /**
     * Determines if the input role is a role ID or a role title
     *
     * @param Role $Role ID or Title
     * @return String Fieldname in database that's appropriate
     */
    private function RoleField($Role)
	{
	    if (is_numeric($Role))
	        return reg("jf/rbac/tables/Roles/table/RoleID");
	    else 
	        return reg("jf/rbac/tables/Roles/table/RoleTitle");
	}
	/**
	 * This returns the condition used to determine role for use in nested set 
	 * 
	 *
	 * @param String $Role RoleID or  RoleTitle
	 */
	private function RoleCondition($Role)
	{
	    return $this->RoleField($Role)."=?";
	}
	/**
    Role Title to Role ID
    Returns ID of a RBAC Role from its Title
    @param String $RoleTitle Title of the RBAC Role
    @return ID String, Null on failure
    @see Role_Info()
    */
	function Role_ID($RoleTitle)
	{
        return $this->Roles->GetID(reg("jf/rbac/tables/Roles/table/RoleTitle")."=?",$RoleTitle);
	}
	
	/**
    Role Information
    Returns ID, ParentID, Title and Description of a RBAC Role from its ID
    @param Role $Role ID or Title of the role
    @return Array 
    @see Role_ID()
    */
	function Role_Info($Role)
	{
		return $this->Roles->GetRecord($this->RoleCondition($Role),$Role);
	}
	
	/**
    Adds a new Role
    Returns new role's ID
    @param String $RoleTitle Title of the new role
    @param String $RoleDescription Description of the new role
    @param Role $RoleParent ID or title of the parent node in the roles hierarchy
    @return ID of the new role
    */
	function Role_Add($RoleTitle,$RoleDescription,$RoleParent=0)
	{
		return $this->Roles->InsertChildData(
    		array(reg("jf/rbac/tables/Roles/table/RoleTitle")=>$RoleTitle
    		,reg("jf/rbac/tables/Roles/table/RoleDescription")=>$RoleDescription)
    		,$this->RoleCondition($RoleParent),$RoleParent
		);
	}
	/**
    Removes a role and all its assignments
	@param String $Role ID or Title of a role
	@param Boolean $Recursive to remove children as well or not
    @see Role_ID()
    */
	function Role_Remove($Role,$Recursive=false)
	{
	    $this->UnassignRolePermissions($Role);
	    $this->UnassignRoleUsers($Role);
        if (!$Recursive)
         $this->Roles->DeleteConditional($this->RoleCondition($Role),$Role);
        else
            $this->Roles->DeleteSubtreeConditional($this->RoleCondition($Role),$Role);
	}
	
	/**
	 * Edits a role title and permission (not position)
	 *
	 * @param String $Role ID or Title of node
	 * @param String $RoleTitle new title
	 * @param String $RoleDescription new description
	 */
	function Role_Edit($Role,$RoleTitle=null,$RoleDescription=null)
	{
        return $this->Roles->EditData(array(reg("jf/rbac/tables/Roles/table/RoleTitle")=>$RoleTitle
    		,reg("jf/rbac/tables/Roles/table/RoleDescription")=>$RoleDescription)
    		,$this->RoleCondition($Role),$Role);
	}
	/**
	 * Returns all direct children of a role
	 *
	 * @param String $Role ID or Title of role
	 * @return Array Children
	 */
	function Role_Children($Role=0)
	{
	    return $this->Roles->ChildrenConditional($this->RoleCondition($Role),$Role);
	}
	/**
	 * Returns all level descendants of a role
	 *
	 * @param String $Role ID or Title of role
	 * @return Array of Descendants (with Depth field)
	 */
	function Role_Descendants($Role=0)
	{
	    return $this->Roles->DescendantsConditional(false,$this->RoleCondition($Role),$Role);
	}

	/**
	 * Returns all roles in a Depth sorted manner
	 * includes the Depth field in each role
	 *
	 * @return Array Roles
	 */
	function Role_All()
	{
	    return $this->Roles->FullTree();
	}
	
	function Role_Reset($Ensure=false)
	{
		if ($Ensure!==true) 
		{
			trigger_error("Make sure you want to reset all roles first!");
			return;
		}
        $this->DB->Execute("DELETE FROM `".reg("jf/rbac/tables/Roles/table/name")."`");	    
        $this->Role_Add("root","root");
        $this->DB->Execute("UPDATE `".reg("jf/rbac/tables/Roles/table/name")."` SET `".reg("jf/rbac/tables/Roles/table/RoleID")."` = '0'");
        if (reg("app/db/default/type")=="mysql") 
        	$this->DB->Execute("ALTER TABLE `".reg("jf/rbac/tables/Roles/table/name")."` AUTO_INCREMENT =1 ");
       	elseif (reg("app/db/default/type")=="sqlite")
        	$this->DB->Execute("delete from sqlite_sequence where name=? ",reg("jf/rbac/tables/Roles/table/name"));
        else
			trigger_error("RBAC can not reset table on this type of database:".reg("app/db/default/type"));
        
	}
	###############################
	#### Permissions Interface ####
	###############################
    /**
     * Determines if the input permission is a permission ID or a permission title
     * then returns the appropriate field name in database to set the condition
     *
     * @param String $Permission ID or Title
     * @return String Fieldname in database that's appropriate
     */
    private function PermissionField($Permission)
	{
	    if (is_numeric($Permission))
	        return reg("jf/rbac/tables/Permissions/table/PermissionID");
	    else 
	        return reg("jf/rbac/tables/Permissions/table/PermissionTitle");
	}
	/**
	 * This returns the condition used to determine permission for use in nested set 
	 *
	 * @param String $Permission ID or  Title
	 */
	private function PermissionCondition($Permission)
	{
	    return $this->PermissionField($Permission)."=?";
	}
	/**
    Permission Title to Permission ID
    Returns ID of a RBAC Permission from its Title
    @param String $PermissionTitle Title of the RBAC Permission
    @return ID String, Null on failure
    @see Permission_Info()
    */
	function Permission_ID($PermissionTitle)
	{
        return $this->Permissions->GetID($this->PermissionCondition($PermissionTitle),$PermissionTitle);
	 }
	
	/**
    Permission Information
    Returns ID, Title and Description of a RBAC Permission from its ID (and Depth)
    @param String $Permission ID or Title of the RBAC Permission
    @return Array with 4 elements
    @see Permission_ID()
    */
	function Permission_Info($Permission)
	{
		return $this->Permissions->GetRecord($this->PermissionCondition($Permission),$Permission);
    }
	
	/**
    Adds a new Permission
    Returns new Permission's ID
    @param String $PermissionTitle Title of the new Permission
    @param String $PermissionDescription Description of the new Permission
    @param String $PermissionParent ID or Title of the parent node in the Permissions hierarchy
    @return ID of the new Permission
    @see Permission_ID()
    */
	function Permission_Add($PermissionTitle,$PermissionDescription,$PermissionParent=0)
	{
		return $this->Permissions->InsertChildData(
    		array(reg("jf/rbac/tables/Permissions/table/PermissionTitle")=>$PermissionTitle
    		,reg("jf/rbac/tables/Permissions/table/PermissionDescription")=>$PermissionDescription)
    		,$this->PermissionCondition($PermissionParent),$PermissionParent
		);
    }
    /**
    Removes a permission and all its assignments
	@param String $Permission ID or Title of a Permission
	@param Boolean $Recursive to remove descendants as well or not
    @see Permission_ID()
    */
	function Permission_Remove($Permission,$Recursive=false)
	{
        $this->UnassignPermissionRoles($Permission);
	    if (!$Recursive)
         $this->Permissions->DeleteConditional($this->PermissionCondition($Permission),$Permission);
        else
            $this->Permissions->DeleteSubtreeConditional($this->PermissionCondition($Permission),$Permission);
	}
	/**
	 * Edits a Permission title and permission (not position)
	 *
	 * @param String $Permission ID or Title of node
	 * @param String $PermissionTitle new title
	 * @param String $PermissionDescription new description
	 */
	function Permission_Edit($Permission,$PermissionTitle=null,$PermissionDescription=null)
	{
        return $this->Permissions->EditData(array(reg("jf/rbac/tables/Permissions/table/PermissionTitle")=>$PermissionTitle
    		,reg("jf/rbac/tables/Permissions/table/PermissionDescription")=>$PermissionDescription)
    		,$this->PermissionCondition($Permission),$Permission);
	}
	/**
	 * Returns all direct children of a Permission
	 *
	 * @param String $Permission ID or Title of Permission
	 * @return Array Children
	 */
	function Permission_Children($Permission=0)
	{
	    return $this->Permissions->ChildrenConditional($this->PermissionCondition($Permission),$Permission);
	}
	/**
	 * Returns all level descendants of a Permission
	 *
	 * @param String $Permission ID or Title of Permission
	 * @return Array of Descendants (with Depth field)
	 */
	function Permission_Descendants($Permission=0)
	{
	    return $this->Permissions->DescendantsConditional(false,$this->PermissionCondition($Permission),$Permission);
	}
	/**
	 * Returns all Permissions in a Depth sorted manner
	 * includes the Depth field in each Permission
	 *
	 * @return Array Permissions
	 */
	function Permission_All()
	{
	    return $this->Permissions->FullTree();
	}
	
	function Permission_Reset($Ensure=false)
	{
		if ($Ensure!==true) 
		{
			trigger_error("Make sure you want to reset all permissions first!");
			return;
		}
		$this->DB->Execute("DELETE FROM `".reg("jf/rbac/tables/Permissions/table/name")."`");	    
        $this->Permission_Add("root","root");
        $this->DB->Execute("UPDATE `".reg("jf/rbac/tables/Permissions/table/name")."` SET `".reg("jf/rbac/tables/Permissions/table/PermissionID")."` = '0'");
        if (reg("app/db/default/type")=="mysql") 
        	$this->DB->Execute("ALTER TABLE `".reg("jf/rbac/tables/Permissions/table/name")."` AUTO_INCREMENT =1 ");
       	elseif (reg("app/db/default/type")=="sqlite")
        	$this->DB->Execute("delete from sqlite_sequence where name=? ",reg("jf/rbac/tables/Permissions/table/name"));
        else
			trigger_error("RBAC can not reset table on this type of database: ".reg("app/db/default/type"));
	}
	
	##############################
	######### User-Roles #########
	##############################
	
	/**
	 * Assigns a role to a user
	 *
	 * @param String $Role Title or ID
	 * @param String $UserID optional, UserID or the current user would be used (use 0 for guest)
	 * @param Boolean $Replace to replace the assignment if existing (only updates date)
	 */
	function User_AssignRole($Role,$UserID=null,$Replace=false)
	{
	    if (!is_numeric($Role)) $Role=$this->Role_ID($Role);
	    if ($UserID===null) $UserID=$this->App->Session->UserID;
        $Query=$Replace?"REPLACE":"INSERT INTO";
        $this->DB->Execute("{$Query} `".reg("jf/rbac/tables/RoleUsers/table/name")."` 
        (`".reg("jf/rbac/tables/RoleUsers/table/UserID")."`,`".reg("jf/rbac/tables/RoleUsers/table/RoleID")."`,`".reg("jf/rbac/tables/RoleUsers/table/AssignmentDate")."`)
        VALUES (?,?,?)
        ",$UserID,$Role,time());
	}
	/**
	 * Unassigns a role to a user
	 *
	 * @param String $Role Title or ID
	 * @param String $UserID optional, UserID or the current user would be used (use 0 for guest)
	 */
	function User_UnassignRole($Role,$UserID=null)
	{
	    if (!is_numeric($Role)) $Role=$this->Role_ID($Role);
	    if ($UserID===null) $UserID=$this->App->Session->UserID;
        $this->DB->Execute("DELETE FROM `".reg("jf/rbac/tables/RoleUsers/table/name")."` 
		WHERE `".reg("jf/rbac/tables/RoleUsers/table/UserID")."`=? AND `".reg("jf/rbac/tables/RoleUsers/table/RoleID")."`=?"
        ,$UserID,$Role);
	}
	
	/**
	 * Returns all Role-User relations as a 2D array of arrays with following fields:
	 * UserID, Username, AssignmentDate, RoleID, RoleTitle, RoleDescription
	 * @param Boolean $OnlyIDs if this is set to true, only a 2D array of RoleID-UserID is returned (the actual DB table)
	 * @param Integer $Offset to start the results from
	 * @param Integer $Limit to limit number of results
	 * @return Array 2D
	 */
	function User_AllAssignments($OnlyIDs=true,$SortBy=null,$Offset=null,$Limit=null)
	{
	    if ($Limit)
	        $Limit=" LIMIT {$Offset},{$Limit}";
	    else 
	        $Limit="";
	    if ($SortBy)
	        $SortBy=" ORDER BY {$SortBy}";
	    else 
	        $SortBy="";    
	    if ($OnlyIDs)
	        return $this->DB->Execute("SELECT * FROM `".reg("jf/rbac/tables/RoleUsers/table/name")."`{$Limit}");
	    else  
	        return $this->DB->Execute("SELECT TRel.`".reg("jf/rbac/tables/RoleUsers/table/AssignmentDate")."` AS AssignmentDate,
	        TU.`".reg("jf/users/table/UserID")."` AS UserID,TU.`".reg("jf/users/table/Username")."`
	        AS Username,TR.`".reg("jf/rbac/tables/Roles/table/RoleID")."` AS RoleID , TR.`".reg("jf/rbac/tables/Roles/table/RoleTitle")."`
	         AS RoleTitle,TR.`".reg("jf/rbac/tables/Roles/table/RoleDescription")."` AS RoleDescription 
	        FROM 
			`".reg("jf/rbac/tables/RoleUsers/table/name")."` AS `TRel` 
			JOIN `".reg("jf/users/table/name")."` AS `TU` ON 
			(`TRel`.`".reg("jf/rbac/tables/RoleUsers/table/UserID")."`=`TU`.`".reg("jf/users/table/UserID")."`)
			JOIN `".reg("jf/rbac/tables/Roles/table/name")."` AS `TR` ON 
			(`TRel`.`".reg("jf/rbac/tables/RoleUsers/table/RoleID")."`=`TR`.`".reg("jf/rbac/tables/Roles/table/RoleID")."`)
	        {$SortBy}{$Limit}"
	        );
	}
	function User_AllAssignmentsCount()
	{
        $Res=$this->DB->Execute("SELECT COUNT(*) AS Result FROM `".reg("jf/rbac/tables/RoleUsers/table/name")."`");
        return $Res[0]['Result'];
	}	
	
	##############################
	###### Role-Permission #######
	##############################
	/**
	 * Assigns a role to a permission (or vice-versa)
	 *
	 * @param String $Role Title or ID
	 * @param String $Permission Title or ID
	 * @param Boolean $Replace to replace if existing (would only update AssignmentDate)
	 */
	function Assign($Role,$Permission,$Replace=false)
	{
	    if (!is_numeric($Role)) $Role=$this->Role_ID($Role);
	    if (!is_numeric($Permission)) $Permission=$this->Permission_ID($Permission);
	    if ($Replace) $Query="REPLACE";
	    else $Query="INSERT INTO";
	    
	    
	    $this->DB->Execute("{$Query} `".reg("jf/rbac/tables/RolePermissions/table/name")."` 
	    (`".reg("jf/rbac/tables/RolePermissions/table/RoleID")."`,`".reg("jf/rbac/tables/RolePermissions/table/PermissionID")."`,`".reg("jf/rbac/tables/RolePermissions/table/AssignmentDate")."`)
	    VALUES (?,?,?)",$Role,$Permission,time());
	}
	/**
	 * Unassigns a role-permission relation
	 *
	 * @param String $Role Title or ID
	 * @param String $Permission Title or ID
	 */
	function Unassign($Role,$Permission)
	{
	    if (!is_numeric($Role)) $Role=$this->Role_ID($Role);
	    if (!is_numeric($Permission)) $Permission=$this->Permission_ID($Permission);
        $this->DB->Execute("DELETE FROM `".reg("jf/rbac/tables/RolePermissions/table/name")."` WHERE 
        `".reg("jf/rbac/tables/RolePermissions/table/RoleID")."`=? AND `".reg("jf/rbac/tables/RolePermissions/table/PermissionID")."`=?",$Role,$Permission );
	}
	
	function UnassignRolePermissions ($Role)
	{
		if (!is_numeric($Role)) $Role=$this->Role_ID($Role);
		$this->DB->Execute("DELETE FROM `".reg("jf/rbac/tables/RolePermissions/table/name")."` WHERE 
        `".reg("jf/rbac/tables/RolePermissions/table/RoleID")."`=? ",$Role);
	}
	function UnassignPermissionRoles ($Permission)
	{
	    if (!is_numeric($Permission)) $Permission=$this->Permission_ID($Permission);
        $this->DB->Execute("DELETE FROM `".reg("jf/rbac/tables/RolePermissions/table/name")."` WHERE 
        `".reg("jf/rbac/tables/RolePermissions/table/PermissionID")."`=?",$Permission );
	}
	function UnassignRoleUsers($Role)
	{
		if (!is_numeric($Role)) $Role=$this->Role_ID($Role);
		$this->DB->Execute("DELETE FROM `".reg("jf/rbac/tables/RoleUsers/table/name")."` WHERE 
        `".reg("jf/rbac/tables/RoleUsers/table/RoleID")."`=?",$Role);
	}
	function Assignment_Reset($Ensure=false)
	{
		if ($Ensure!==true) 
		{
			trigger_error("Make sure you want to reset all assignments first!");
			return;
		}
		$this->DB->Execute("DELETE FROM `".reg("jf/rbac/tables/RolePermissions/table/name")."`");
        if (reg("app/db/default/type")=="mysql") 
        	$this->DB->Execute("ALTER TABLE `".reg("jf/rbac/tables/RolePermissions/table/name")."` AUTO_INCREMENT =1 ");
       	elseif (reg("app/db/default/type")=="sqlite")
        	$this->DB->Execute("delete from sqlite_sequence where name=? ",reg("jf/rbac/tables/RolePermissions/table/name"));
        else
			trigger_error("RBAC can not reset table on this type of database:".reg("app/db/default/type"));
		$this->Assign("root","root",true);
		return true;
	}     
	/**
	 * Returns all permissions assigned to a role
	 *
	 * @param String $Role Title or ID
	 * @param Boolean $OnlyIDs if true, result would be a 1D array of IDs
	 * @return Array 2D or 1D
	 */
	function RolePermissions($Role,$OnlyIDs=true)
	{
	    if (!is_numeric($Role)) $Role=$this->Role_ID($Role);
	    if ($OnlyIDs)
	    {
	        $Res=$this->DB->Execute("SELECT `".reg("jf/rbac/tables/RolePermissions/table/PermissionID")."` AS `ID` FROM
			`".reg("jf/rbac/tables/RolePermissions/table/name")."` WHERE `".reg("jf/rbac/tables/RolePermissions/table/RoleID")."`=?"
	        ,$Role);
	        foreach ($Res as $R)
	            $out[]=$R['ID'];
	        return $out;
	    }
	    else
	        return $this->DB->Execute("SELECT `TP`.* FROM 
			`".reg("jf/rbac/tables/RolePermissions/table/name")."` AS `TR` RIGHT JOIN `".reg("jf/rbac/tables/Permissions/table/name")."` AS `TP` ON 
			(`TR`.`".reg("jf/rbac/tables/RolePermissions/table/PermissionID")."`=`TP`.`".reg("jf/rbac/tables/Permissions/table/PermissionID")."`)
			WHERE `".reg("jf/rbac/tables/RolePermissions/table/RoleID")."`=?"
	        ,$Role);
	}
	/**
	 * Returns all roles assigned to a permission
	 *
	 * @param String $Permission Title or ID
	 * @param Boolean $OnlyIDs if true, result would be a 1D array of IDs
	 * @return Array 2D or 1D
	 */
	function PermissionRoles($Permission,$OnlyIDs=true)
	{
	    if (!is_numeric($Permission)) $Permission=$this->Permission_ID($Permission);
	    if ($OnlyIDs)
	    {
	        $Res=$this->DB->Execute("SELECT `".reg("jf/rbac/tables/RolePermissions/table/RoleID")."` AS `ID` FROM
			`".reg("jf/rbac/tables/RolePermissions/table/name")."` WHERE `".reg("jf/rbac/tables/RolePermissions/table/PermissionID")."`=?"
	        ,$Permission);
	        foreach ($Res as $R)
	            $out[]=$R['ID'];
	        return $out;
	    }
	    else
	        return $this->DB->Execute("SELECT `TP`.* FROM 
			`".reg("jf/rbac/tables/RolePermissions/table/name")."` AS `TR` RIGHT JOIN `".reg("jf/rbac/tables/Roles/table/name")."` AS `TP` ON 
			(`TR`.`".reg("jf/rbac/tables/RolePermissions/table/RoleID")."`=`TP`.`".reg("jf/rbac/tables/Roles/table/RoleID")."`)
			WHERE `".reg("jf/rbac/tables/RolePermissions/table/PermissionID")."`=?"
	        ,$Permission);
	}
	/**
	 * Returns all Role-Permission relations as a 2D array of arrays with following fields:
	 * PermissionID, PermissionTitle, PermissionDescription, RoleID, RoleTitle, RoleDescription, AssignmentDate
	 * @param Boolean $OnlyIDs if this is set to true, only a 2D array of RoleID-PermissionIDs is returned (the actual DB table)
	 * @param Integer $Offset to start the results from
	 * @param Integer $Limit to limit number of results
	 * @return Array 2D
	 */
	function Assignments_All($OnlyIDs=true,$SortBy=null,$Offset=null,$Limit=null)
	{
	    if ($Limit)
	        $Limit=" LIMIT {$Offset},{$Limit}";
	    else 
	        $Limit="";
	    if ($SortBy)
	        $SortBy=" ORDER BY {$SortBy}";
	    else 
	        $SortBy="";    
	    if ($OnlyIDs)
	        return $this->DB->Execute("SELECT * FROM `".reg("jf/rbac/tables/RolePermissions/table/name")."`{$Limit}");
	    else 
	        return $this->DB->Execute("SELECT TRel.`".reg("jf/rbac/tables/RolePermissions/table/AssignmentDate")."` AS AssignmentDate,
	        TP.`".reg("jf/rbac/tables/Permissions/table/PermissionID")."` AS PermissionID,TP.`".reg("jf/rbac/tables/Permissions/table/PermissionTitle")."`
	        AS PermissionTitle, TP.`".reg("jf/rbac/tables/Permissions/table/PermissionDescription")."` AS PermissionDescription,TR.`".
	        reg("jf/rbac/tables/Roles/table/RoleID")."` AS RoleID , TR.`".reg("jf/rbac/tables/Roles/table/RoleTitle")."` AS RoleTitle,TR.`".reg("jf/rbac/tables/Roles/table/RoleDescription")."` AS RoleDescription 
	        FROM 
			`".reg("jf/rbac/tables/RolePermissions/table/name")."` AS `TRel` 
			JOIN `".reg("jf/rbac/tables/Permissions/table/name")."` AS `TP` ON 
			(`TRel`.`".reg("jf/rbac/tables/RolePermissions/table/PermissionID")."`=`TP`.`".reg("jf/rbac/tables/Permissions/table/PermissionID")."`)
			JOIN `".reg("jf/rbac/tables/Roles/table/name")."` AS `TR` ON 
			(`TRel`.`".reg("jf/rbac/tables/RolePermissions/table/RoleID")."`=`TR`.`".reg("jf/rbac/tables/Roles/table/RoleID")."`)
	        {$SortBy}{$Limit}"
	        );
	}
	function Assignments_Count()
	{
        $Res=$this->DB->Execute("SELECT COUNT(*) AS Result FROM `".reg("jf/rbac/tables/RolePermissions/table/name")."`");
        return $Res[0]['Result'];
	}
	
	###############
	### GENERAL ###
	###############
	private $PreparedStatement_Check=array(0=>null,1=>null);
	/**
	 * Checks whether a user has a permission or not.
	 *
	 * @param String $Permission Title or ID
	 * @param Integer $UserID optional
	 * @return true on success (a positive number) false on no permission (zero)
	 */
	function Check($Permission,$UserID=null)
	{
		//To different prepared statements, one for Title lookup another of ID lookup of permission
	    if (is_numeric($Permission)) 
	    {
	        $PermissionCondition="`".reg("jf/rbac/tables/Permissions/table/PermissionID")."`=?";
	        $Index=0;
	    }
	    else 
	    {
	        $PermissionCondition="`".reg("jf/rbac/tables/Permissions/table/PermissionTitle")."`=?";
	        $Index=1;
	    }
	    if ($UserID===null) $UserID=$this->App->Session->UserID;
	    if (!$this->PreparedStatement_Check[$Index])
	    {
	    	$this->PreparedStatement_Check[$Index]=$this->DB->Prepare
    ("SELECT COUNT(*) AS Result
    FROM /* Version 2.05 */ 
    	`".reg("jf/users/table/name")."` AS TU
    JOIN `".reg("jf/rbac/tables/RoleUsers/table/name")."` AS TUrel ON (TU.`".reg("jf/users/table/UserID")."`=TUrel.`".reg("jf/rbac/tables/RoleUsers/table/UserID")."`)
    	
    JOIN `".reg("jf/rbac/tables/Roles/table/name")."` AS TRdirect ON (TRdirect.`".reg("jf/rbac/tables/Roles/table/RoleID")."`=TUrel.`".reg("jf/rbac/tables/RoleUsers/table/RoleID")."`) 
    JOIN `".reg("jf/rbac/tables/Roles/table/name")."` AS TR ON ( TR.`".reg("jf/rbac/tables/Roles/table/RoleLeft")."` BETWEEN TRdirect.`".reg("jf/rbac/tables/Roles/table/RoleLeft")."` AND TRdirect.`".reg("jf/rbac/tables/Roles/table/RoleRight")."`)
    /* we join direct roles with indirect roles to have all descendants of direct roles */
    JOIN 
    (	`".reg("jf/rbac/tables/Permissions/table/name")."` AS TPdirect 
    	JOIN `".reg("jf/rbac/tables/Permissions/table/name")."` AS TP ON ( TPdirect.`".reg("jf/rbac/tables/Permissions/table/PermissionLeft")."` BETWEEN TP.`".reg("jf/rbac/tables/Permissions/table/PermissionLeft")."` AND TP.`".reg("jf/rbac/tables/Permissions/table/PermissionRight")."`)
    /* direct and indirect permissions */
    	JOIN `".reg("jf/rbac/tables/RolePermissions/table/name")."` AS TRel ON (TP.`".reg("jf/rbac/tables/Permissions/table/PermissionID")."`=TRel.`".reg("jf/rbac/tables/RolePermissions/table/PermissionID")."`)
    /* joined with role/permissions on roles that are in relation with these permissions*/
    ) ON ( TR.`".reg("jf/rbac/tables/Roles/table/RoleID")."` = TRel.`".reg("jf/rbac/tables/RolePermissions/table/RoleID")."`)
    WHERE 
    	TU.`".reg("jf/users/table/UserID")."`=? 
    AND
	    TPdirect.{$PermissionCondition}
	    ");
	    }
	    $this->PreparedStatement_Check[$Index]->Execute(
	   	$UserID
	    ,$Permission
	    );
        $Res=$this->PreparedStatement_Check[$Index]->AllResult();
	    
	    return $Res[0]['Result'];
	}
	/**
	 * Checks to see if a role has a permission or not
	 *
	 * @param String $Role Title or ID
	 * @param String $Permission Title or ID
	 * @return Integer 0 on no, number of paths to permission on yes
	 * @author AbiusX with all the SQL statements used!
	 */
	function CheckRolePermission($Role,$Permission)
	{
	    if (is_numeric($Permission)) $PermissionCondition="node.`".reg("jf/rbac/tables/Permissions/table/PermissionID")."`=?";
	    else $PermissionCondition="node.`".reg("jf/rbac/tables/Permissions/table/PermissionTitle")."`=?";
	    if (is_numeric($Role)) $RoleCondition="`".reg("jf/rbac/tables/Roles/table/RoleID")."`=?";
	    else $RoleCondition="`".reg("jf/rbac/tables/Roles/table/RoleTitle")."`=?";
	    
	    $Res=$this->DB->Execute("
    SELECT COUNT(*) AS Result
    FROM `".reg("jf/rbac/tables/RolePermissions/table/name")."` AS TRel
    JOIN `".reg("jf/rbac/tables/Permissions/table/name")."` AS TP ON ( TP.`".reg("jf/rbac/tables/Permissions/table/PermissionID")."`= TRel.`".reg("jf/rbac/tables/RolePermissions/table/PermissionID")."`)
    JOIN `".reg("jf/rbac/tables/Roles/table/name")."` AS TR ON ( TR.`".reg("jf/rbac/tables/Roles/table/RoleID")."` = TRel.`".reg("jf/rbac/tables/RolePermissions/table/RoleID")."`)
    WHERE TR.`".reg("jf/rbac/tables/Roles/table/RoleLeft")."` BETWEEN 
    	(SELECT `".reg("jf/rbac/tables/Roles/table/RoleLeft")."` FROM `".reg("jf/rbac/tables/Roles/table/name")."` WHERE {$RoleCondition}) 
    	AND 
    	(SELECT `".reg("jf/rbac/tables/Roles/table/RoleRight")."` FROM `".reg("jf/rbac/tables/Roles/table/name")."` WHERE {$RoleCondition})
/* the above section means any row that is a descendants of our role (if descendant roles have some permission, then our role has it two) */
    AND TP.`".reg("jf/rbac/tables/Permissions/table/PermissionID")."` IN (
                SELECT parent.`".reg("jf/rbac/tables/Permissions/table/PermissionID")."` 
                FROM `".reg("jf/rbac/tables/Permissions/table/name")."` AS node,
                `".reg("jf/rbac/tables/Permissions/table/name")."` AS parent
                WHERE node.`".reg("jf/rbac/tables/Permissions/table/PermissionLeft")."` BETWEEN parent.`".reg("jf/rbac/tables/Permissions/table/PermissionLeft")."` AND parent.`".reg("jf/rbac/tables/Permissions/table/PermissionRight")."`
                AND ( {$PermissionCondition} )
                ORDER BY parent.`".reg("jf/rbac/tables/Permissions/table/PermissionLeft")."`
    );
/*
the above section returns all the parents of (the path to) our permission, so if one of our role or its descendants
 has an assignment to any of them, we're good. 
*/
	    "
	    ,$Role,$Role
	    ,$Permission
	    );
        return $Res[0]['Result'];
}
	
	
	function Enforce($Permission)
	{
	    if (!$this->Check($Permission))
	    {
	    	define ("Permission",$Permission);
			$this->App->LoadApplicationModule(reg("jf/rbac/401ErrorView"));
			exit();
	    }
	}
	/**
	 * Checks to see whether a user has a role or not
	 * @param 	$Role Role Title or ID
	 * @param	$User User ID
	 * @return	Boolean true on yes, false on no
	 */
	function UserInRole($User=null,$Role)
	{
		
		if ($User===null) $User=$this->App->Session->UserID();
	    if (!is_numeric($Role)) $Role=$this->Role_ID($Role);
		$R=$this->App->DB->Execute("SELECT * FROM `".reg("jf/rbac/tables/RoleUsers/table/name")."` WHERE
		`".reg("jf/rbac/tables/RoleUsers/table/UserID")."`=? AND `".reg("jf/rbac/tables/RoleUsers/table/RoleID")."`=?",$User,$Role);
		if ($R) return true;
		else return false;
	}	
}
interface RBAC_RoleManagement 
{
	function Role_ID($RoleTitle);
	
	function Role_Info($Role); 
	
	function Role_Add($RoleTitle,$RoleDescription,$RoleParent=0);
	function Role_Remove($Role);
	function Role_Edit($Role,$RoleTitle=null,$RoleDescription=null);
	
    function Role_Children($Role=0);
    function Role_Descendants($Role=0);
	
	function Role_All();
		
}
interface RBAC_PermissionManagement
{
	function Permission_ID($PermissionTitle);
	
	function Permission_Info($Permission);
	
	function Permission_Add($PermissionTitle,$PermissionDescription,$PermissionParent=0);
	function Permission_Remove($Permission);
	function Permission_Edit($Permission,$PermissionTitle=null,$PermissionDescription=null);
	
	function Permission_Children($Permission=0);
	function Permission_Descendants($Permission=0);
	
	function Permission_All();
}
interface RBAC_UserManagement
{
	//function User_AssignRole($UserID,$RoleID);
	
	//function User_UnassignRole($UserID,$RoleID);
	
	//function User_UnassignAllRoles($UserID);
	
	//function User_Validate($PermissionID,$UserID);

	//function User_RoleList($UserID);
	
}
interface RBAC_Management
{
	
	//Role-Permission Relation
	#function Assign($RoleID,$PermissionID);
	#function Unassign($RoleID,$PermissionID);
	#-function UnassignRolePermissions($RoleID);
	#-function UnassignPermissionRoles($PermissionID);

	#function PermissionList($RoleID);
	#function RoleList($PermissionID);
	
	
	
	//Checks for existence of a permission
	#function ValidateRolePermission($RoleID,$PermissionID);
	

	
	
}
?>