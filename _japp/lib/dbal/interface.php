<?php
interface jFramework_DBAL_Abstract
{
	
	function LastID ();
    function Escape ();
    function ResultCount ();
    /**
     * Returns affected rows
     * if last query was a prepared statement, should return the statements affected rows
     * otherwise the db's affected rows
     */
    function AffectedRows ();
    function AutoQuery ($QueryString);
    /**
     * this should unset the internal Statement variable, since last query wasnt prepared stmt
     * @param $QueryString
     */
    function Query ($QueryString);
    function NextResult ();
    function AllResult ();
    /**
     * Should prepare the statement (with ?s) and call AutoQuery on prepared statement.
     * If query does not have any arguments, should pass it to AutoQuery(), to support SQL commands
     * which are not queries (e.g BEGIN TRANSACTION)
     * This should also set an internal Statement variable to determine last query was statement or not
     */
    function Execute ();
    function Prepare ($QueryString);
}
interface jFramework_DBAL extends jFramework_DBAL_Abstract
{
}
interface jFramework_DBAL_PreparedStatement extends jFramework_DBAL_Abstract
{
    function Bind ();
    
    //TODO: add interface functions to send blob and large datas using send_long_data mysqli
}

interface jFramework_DBAL_Hierarchical_Basic
{
    public function InsertChild($PID=0);
    public function InsertSibling($ID=0);
    
    function DeleteSubtree($ID);
    function Delete($ID);
    
    //function Move($ID,$NewPID);
    //function Copy($ID,$NewPID);
    
    function FullTree();
    function Children($ID);
    function Descendants($ID,$AbsoluteDepths=false);
    function Leaves($PID=null);
    function Path($ID);
    
    function Depth($ID);
    function ParentNode($ID);
    function Sibling($ID,$SiblingDistance=1);
}
interface jFramework_DBAL_Hierarchical_Full extends jFramework_DBAL_Hierarchical_Basic 
{
    //All functions with ConditionString, accept other parameters in variable numbers
    function GetID($ConditionString);

    function InsertChildData($FieldValueArray=array(),$ConditionString=null);
    function InsertSiblingData($FieldValueArray=array(),$ConditionString=null);
    
    function DeleteSubtreeConditional($ConditionString);
    function DeleteConditional($ConditionString);
    
    
    function ChildrenConditional($ConditionString);
    function DescendantsConditional($AbsoluteDepths=false,$ConditionString);
    function LeavesConditional($ConditionString=null);
    function PathConditional($ConditionString);
    
    function DepthConditional($ConditionString);
    function ParentNodeConditional($ConditionString);
    function SiblingConditional($SiblingDistance=1,$ConditionString);
	/**/    
}
?>