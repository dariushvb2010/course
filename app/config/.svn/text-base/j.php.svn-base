<?php
############################################################
### Add all your customized j:: functions and vars here	 ###
############################################################

class jExtend 

{
	protected static function DQLprepare($Query)
	{
		$args=func_get_args();
		$q=new Doctrine\ORM\Query(j::$ORM->entityManager);
		$n=1;
		$p=0;
		while($p<strlen($Query) && $p=strpos($Query, "?",$p+1))
			$Query=substr_replace($Query, "?".($n++), $p,1);
		
		$Offset=null;
		$Limit=null;
		if (($p=stripos($Query,"limit"))!==false)
		{
			$Limit=sscanf(substr($Query,$p+5),"%d");
			$p2=strpos($Query, ",",$p+2);
			if ($p2!==false)
			{
				$Offset=$Limit;
				$Limit=sscanf(substr($Query,$p2+1),"%d");
			}
			
			$Query=substr($Query,0,$p-1);
			if ($Offset) $Offset=$Offset[0];
			$Limit=$Limit[0];
// 			var_dump($Query);
// 			var_dump($Offset);
// 			var_dump($Limit);
		}
		
		
		$q->setDQL($Query);
		array_shift($args);
		$params=array();
		$n=1;
		foreach ($args as $a)
			$params[$n++]=$a;
		
		
		$q->setParameters($params);
		if ($Offset) $q->setFirstResult($Offset);
		if ($Limit) $q->setMaxResults($Limit);
		return $q;
		
	}
	public static function DQL($Query)
	{
		$args=func_get_args();
		$q=call_user_func_array(array("j","DQLprepare"),$args);
		return $q->getArrayResult();
	}
	public static function ODQL($Query)
	{
		$args=func_get_args();
		$q=call_user_func_array(array("j","DQLprepare"),$args);
		return $q->getResult();
	}
	
	/**
	@var DoctrinePlugin
	 */
    public static $ORM;
    /**
    *
    * @var DBAL_MSSQL
    */
    public static $MSSQL;
    
    
    /**
     * 
     * Returns entity manager, or persistent object on parameter fill
     * @param new object $newEntity
     * @return Doctrine\ORM\EntityManager
     * @var Doctrine\ORM\EntityManager
     */
    public static function ORM()
    {
    	
    	return j::$ORM->entityManager;
    }

	
}
