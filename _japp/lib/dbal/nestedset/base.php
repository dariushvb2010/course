<?php

/**
 * BaseNestedSet Class
 * This class provides a means to implement Hierarchical data in flat SQL tables.
 * Queries extracted from http://dev.mysql.com/tech-resources/articles/hierarchical-data.html
 *
 * Tested and working properly
 * 
 * Usage:
 * have a table with at least 3 INT fields for ID,Left and Right.
 * Create a new instance of this class and pass the name of table and name of the 3 fields above
  */
//FIXME: many of these operations should be done in a transaction
class BaseNestedSet implements jFramework_DBAL_Hierarchical_Basic
{
    protected $DB;
    function __construct(DBAL $DB,$Table,$IDField="ID",$LeftField="Left",$RightField="Right")
    {
        $this->DB=$DB;
        $this->Assign($Table,$IDField,$LeftField,$RightField);
    }
    protected $Table;
    protected $ID,$Left,$Right;
    /**
     * Assigns fields of the table
     *
     * @param String $Table
     * @param String $ID
     * @param String $Left
     * @param String $Right
     */
    function Assign($Table,$ID,$Left,$Right)
    {
        $this->Table=$Table;
        $this->ID=$ID;
        $this->Left=$Left;
        $this->Right=$Right;
    }
    
    /**
     * Returns number of descendants 
     *
     * @param Integer $ID
     * @return Integer Count
     */
    function DescendantCount($ID)
    {
        $Res=$this->DB->Execute("SELECT (`".$this->Right."`-`".$this->Left."`-1)/2 AS `Count` FROM
        `".$this->Table."` WHERE `".$this->ID."`=?",$ID);
        return sprintf("%d",$Res[0]["Count"])*1;
    }
    
    /**
     * Returns the depth of a node in the tree
     * Note: this uses Path
     * @param Integer $ID
     * @return Integer Depth from zero upwards
     * @seealso Path
     */
    function Depth($ID)
    {
        return count($this->Path($ID))-1;
    }
    /**
     * Returns a sibling of the current node
     * Note: You can't find siblings of roots 
     * Note: this is a heavy function on nested sets, uses both Children (which is quite heavy) and Path
     * @param Integer $ID
     * @param Integer $SiblingDistance from current node (negative or positive)
     * @return Array Node on success, null on failure 
     */
    function Sibling($ID,$SiblingDistance=1)
    {
        $Parent=$this->ParentNode($ID);
        $Siblings=$this->Children($Parent[$this->ID]);
        if (!$Siblings) return null;
        foreach ($Siblings as &$Sibling)
        {
            if ($Sibling[$this->ID]==$ID) break;
            $n++;
        }
        return $Siblings[$n+$SiblingDistance];
    }
    /**
     * Returns the parent of a node
     * Note: this uses Path
     * @param Integer $ID
     * @return Array ParentNode (null on failure)
     * @seealso Path
     */
    function ParentNode($ID)
    {
        $Path=$this->Path($ID);
        if (count($Path)<2) return null;
        else return $Path[count($Path)-2];        
    }
	/**
     * Deletes a node and shifts the children up
     *
     * @param Integer $ID
     */
    function Delete($ID)
    {
        //$this->DB->AutoQuery("LOCK TABLE `".$this->Table."` WRITE;");
        $Info=$this->DB->Execute("SELECT `".$this->Left."` AS `Left`,`".$this->Right."` AS `Right` 
			FROM `".$this->Table."`
			WHERE `".$this->ID."` = ?;
        ",$ID);
        $Info=$Info[0];

        $this->DB->Execute("DELETE FROM `".$this->Table."` WHERE `".$this->Left."` = ?",$Info["Left"]);


        $this->DB->Execute("UPDATE `".$this->Table."` SET `".$this->Right."` = `".$this->Right."` - 1, `".
            $this->Left."` = `".$this->Left."` - 1 WHERE `".$this->Left."` BETWEEN ? AND ?",$Info["Left"],$Info["Right"]);
        $this->DB->Execute("UPDATE `".$this->Table."` SET `".$this->Right."` = `".$this->Right."` - 2 WHERE `".
            $this->Right."` > ?",$Info["Right"]);
        $this->DB->Execute("UPDATE `".$this->Table."` SET `".$this->Left."` = `".$this->Left."` - 2 WHERE `".
            $this->Left."` > ?",$Info["Right"]);
        //$this->DB->AutoQuery("UNLOCK TABLES");
    }
    /**
     * Deletes a node and all its descendants
     *
     * @param Integer $ID
     */
    function DeleteSubtree($ID)
    {
//        $this->DB->AutoQuery("LOCK TABLE `".$this->Table."` WRITE;");
        $Info=$this->DB->Execute("SELECT `".$this->Left."` AS `Left`,`".$this->Right."` AS `Right` ,`".$this->Right."`-`".$this->Left."`+ 1 AS Width
			FROM `".$this->Table."`
			WHERE `".$this->ID."` = ?;
        ",$ID);
        $Info=$Info[0];
        
        $this->DB->Execute("
            DELETE FROM `".$this->Table."` WHERE `".$this->Left."` BETWEEN ? AND ?
        ",$Info["Left"],$Info["Right"]);
        
        $this->DB->Execute("
            UPDATE `".$this->Table."` SET `".$this->Right."` = `".$this->Right."` - ? WHERE `".$this->Right."` > ?
        ",$Info["Width"],$Info["Right"]);
        $this->DB->Execute("
            UPDATE `".$this->Table."` SET `".$this->Left."` = `".$this->Left."` - ? WHERE `".$this->Left."` > ?
        ",$Info["Width"],$Info["Right"]);
//        $this->DB->AutoQuery("UNLOCK TABLES");
        
    }
    /**
     * Returns all descendants of a node
     *
     * @param Integer $ID
     * @param Boolean $AbsoluteDepths to return Depth of sub-tree from zero or absolutely from the whole tree  
	 * @return Rowset including Depth field
	 * @seealso Children
     */
    function Descendants($ID,$AbsoluteDepths=false)
    {
           if (!$AbsoluteDepths)
               $DepthConcat="- (sub_tree.depth )";
        $Res=$this->DB->Execute("
            SELECT node.*, (COUNT(parent.`".$this->ID."`)-1 $DepthConcat ) AS Depth
            FROM `".$this->Table."` AS node,
            	`".$this->Table."` AS parent,
            	`".$this->Table."` AS sub_parent,
            	(
            		SELECT node.`".$this->ID."`, (COUNT(parent.`".$this->ID."`) - 1) AS depth
            		FROM `".$this->Table."` AS node,
            		`".$this->Table."` AS parent
            		WHERE node.`".$this->Left."` BETWEEN parent.`".$this->Left."` AND parent.`".$this->Right."`
            		AND node.`".$this->ID."` = ?
            		GROUP BY node.`".$this->ID."`
            		ORDER BY node.`".$this->Left."`
            	) AS sub_tree
            WHERE node.`".$this->Left."` BETWEEN parent.`".$this->Left."` AND parent.`".$this->Right."`
            	AND node.`".$this->Left."` BETWEEN sub_parent.`".$this->Left."` AND sub_parent.`".$this->Right."`
            	AND sub_parent.`".$this->ID."` = sub_tree.`".$this->ID."`
            GROUP BY node.`".$this->ID."`
            HAVING Depth > 0
            ORDER BY node.`".$this->Left."`",$ID);
        return $Res;
    }
    /**
     * Returns immediate children of a node
     * Note: this function performs the same as Descendants but only returns results with Depth=1
     * @param Integer $ID
     * @return Rowset not including Depth
     * @seealso Descendants
     */
    function Children($ID)
    {
        $Res=$this->DB->Execute("
            SELECT node.*, (COUNT(parent.`".$this->ID."`)-1 - (sub_tree.depth )) AS Depth
            FROM `".$this->Table."` AS node,
            	`".$this->Table."` AS parent,
            	`".$this->Table."` AS sub_parent,
           	(
            		SELECT node.`".$this->ID."`, (COUNT(parent.`".$this->ID."`) - 1) AS depth
            		FROM `".$this->Table."` AS node,
            		`".$this->Table."` AS parent
            		WHERE node.`".$this->Left."` BETWEEN parent.`".$this->Left."` AND parent.`".$this->Right."`
            		AND node.`".$this->ID."` = ?
            		GROUP BY node.`".$this->ID."`
            		ORDER BY node.`".$this->Left."`
            ) AS sub_tree
            WHERE node.`".$this->Left."` BETWEEN parent.`".$this->Left."` AND parent.`".$this->Right."`
            	AND node.`".$this->Left."` BETWEEN sub_parent.`".$this->Left."` AND sub_parent.`".$this->Right."`
            	AND sub_parent.`".$this->ID."` = sub_tree.`".$this->ID."`
            GROUP BY node.`".$this->ID."`
            HAVING Depth = 1
            ORDER BY node.`".$this->Left."`;
        ",$ID);
       if ($Res)
       foreach ($Res as &$v)
           unset($v["Depth"]);
        return $Res;
    }    
	/**
     * Returns the path to a node, including the node
     *
     * @param Integer $ID
     * @return Rowset nodes in path
     */
    function Path($ID)
    {
        $Res=$this->DB->Execute("
            SELECT parent.* 
            FROM `".$this->Table."` AS node,
            ".$this->Table." AS parent
            WHERE node.`".$this->Left."` BETWEEN parent.`".$this->Left."` AND parent.`".$this->Right."`
            AND node.`".$this->ID."` = ?
            ORDER BY parent.`".$this->Left."`",$ID);
        return $Res;
    }
    
    /**
     * Finds all leaves of a parent
     *	Note: if you don' specify $PID, There would be one less AND in the SQL Query
     * @param Integer $PID
     * @return Rowset Leaves
     */
    function Leaves($PID=null)
    {
        if ($PID) 
        $Res=$this->DB->Execute("SELECT *
            FROM `".$this->Table."`
            WHERE `".$this->Right."` = `".$this->Left."` + 1 
        	AND `".$this->Left."` BETWEEN 
            (SELECT `".$this->Left."` FROM `".$this->Table."` WHERE `".$this->ID."`=?)
            	AND 
            (SELECT `".$this->Right."` FROM `".$this->Table."` WHERE `".$this->ID."`=?)",$PID,$PID);
        else
        $Res=$this->DB->Execute("SELECT *
            FROM `".$this->Table."`
            WHERE `".$this->Right."` = `".$this->Left."` + 1");
        return $Res;
    }
    /**
     * Adds a sibling after a node
     *
     * @param Integer $ID
     * @return Integer SiblingID
     */
    function InsertSibling($ID=0)
    {
//        $this->DB->AutoQuery("LOCK TABLE `".$this->Table."` WRITE;");
        //Find the Sibling
        $Sibl=$this->DB->Execute("SELECT `".$this->Right."` AS `Right`".
        	" FROM `".$this->Table."` WHERE `".$this->ID."` = ?",$ID);
        $Sibl=$Sibl[0];
        if ($Sibl==null)
        {
            $Sibl["Right"]=0;
        }
        $this->DB->Execute("UPDATE `".$this->Table."` SET `".$this->Right."` = `".$this->Right."` + 2 WHERE `".$this->Right."` > ?",$Sibl["Right"]);
        $this->DB->Execute("UPDATE `".$this->Table."` SET `".$this->Left."` = `".$this->Left."` + 2 WHERE `".$this->Left."` > ?",$Sibl["Right"]);
        $Res= $this->DB->Execute("INSERT INTO `".$this->Table."` (`".$this->Left."`,`".$this->Right."`) ".
        	"VALUES(?,?)",$Sibl["Right"]+1,$Sibl["Right"]+2);
//        $this->DB->AutoQuery("UNLOCK TABLES");
        return $Res;
    }
    /**
     * Adds a child to the beginning of a node's children
     *
     * @param Integer $PID
     * @return Integer ChildID
     */
    function InsertChild($PID=0)
    {
        //Find the Sibling
//        $this->DB->AutoQuery("LOCK TABLE `".$this->Table."` WRITE;");
        $Sibl=$this->DB->Execute("SELECT `".$this->Left."` AS `Left`".
        	" FROM `".$this->Table."` WHERE `".$this->ID."` = ?",$PID);
        $Sibl=$Sibl[0];
        if ($Sibl==null)
        {
            $Sibl["Left"]=0;
        }
        $this->DB->Execute("UPDATE `".$this->Table."` SET `".$this->Right."` = `".$this->Right."` + 2 WHERE `".$this->Right."` > ?",$Sibl["Left"]);
        $this->DB->Execute("UPDATE `".$this->Table."` SET `".$this->Left."` = `".$this->Left."` + 2 WHERE `".$this->Left."` > ?",$Sibl["Left"]);
        $Res=$this->DB->Execute("INSERT INTO `".$this->Table."` (`".$this->Left."`,`".$this->Right."`) ".
        	"VALUES(?,?)",$Sibl["Left"]+1,$Sibl["Left"]+2);
//        $this->DB->AutoQuery("UNLOCK TABLES");
        return $Res;
    }
    /**
     * Retrives the full tree including Depth field.
     *
     * @return 2DArray Rowset
     */
    function FullTree()
    {
        $Res=$this->DB->Execute("SELECT node.*, (COUNT(parent.`".$this->ID."`) - 1) AS Depth
            FROM `".$this->Table."` AS node,
            `".$this->Table."` AS parent
            WHERE node.`".$this->Left."` BETWEEN parent.`".$this->Left."` AND parent.`".$this->Right."`
            GROUP BY node.`".$this->ID."`
            ORDER BY node.`".$this->Left."`");
        return $Res;
    }
    /**
     * This function converts a 2D array with Depth fields into a multidimensional tree in an associative array
     *
     * @param Array $Result
     * @return Array Tree
     */
    #FIXME: think how to implement this!
    /**
    function Result2Tree($Result)
    {
        $out=array();
        $stack=array();
        $cur=&$out;
        foreach($Result as $R)
        {
            if ($cur[$LastKey]['Depth']==$R['Depth'])
            {
                echo "adding 0 ".$R['Title'].BR;
                $cur[$R[$this->ID]]=$R;
                $LastKey=$R[$this->ID];
            }
            elseif ($cur[$LastKey]['Depth']<$R['Depth'])
            {
                echo "adding 1 ".$R['Title'].BR;
                array_push($stack,$cur);
                $cur=&$cur[$LastKey];
                $cur[$R[$this->ID]]=$R;
                $LastKey=$R[$this->ID];
            }
            elseif ($cur[$LastKey]['Depth']>$R['Depth'])
            {
                echo "adding 2 ".$R['Title'].BR;
                $cur=array_pop($stack);
                $cur[$R[$this->ID]]=$R;
                $LastKey=$R[$this->ID];
            }
            
        }
        return $out;
    }
	/**/
}

?>