<?php
class AutoformPluginTest extends JTest
{
	function __construct()
	{
		
	}
	function testStart()
	{
		$this->assertTrue(true);
	}
	function test1()
	{
		//ORM::Query("MyUser")->getRandomReviewer();
	}
	function getall(&$RS)
	{
		$Reviewers=j::ODQL("SELECT U FROM MyUser U WHERE U.isReviewer=1 AND U.State=1");
		$WSum=0;
		if($Reviewers)
		foreach($Reviewers as $R)
		{
			$W=ORM::Query("MyUser")->AssignedReviewableFileCount($R);
			$W=100-$W;
			if($W<5)
			$W=5;
			// R:Reviewer, W:Weight, WSB: weightSum befor me
			$RS[]=array("R"=>$R,"W"=>$W,"WSB"=>$WSum);
			$WSum+=$W;
		}
		return $WSum;
		
	}
	function getone($WSum, $RS)
	{
		$Rand=mt_rand(0,$WSum);
		for($i=count($RS)-1; $i>=0; $i--)
		{
			if($Rand>=$RS[$i]['WSB'])
			{
				$Selected=$RS[$i]["R"];
				break;
			}
		}
		return $Selected;
	}
	function testStress()
	{
		$WSum=$this->getall($RS);
		
		//ORM::Dump($RS);
		
		for($i=0; $i<1000; $i++)
		{
			
			$rev=$this->getone($WSum, $RS);;
			//var_dump($name);
			$name=$rev->getFullName();
			$R[$name]++;
			//$R[$Name]["class"]=$rev;
		}
// 		foreach ($R as $k=>$v)
// 		{
// 			$R[$k]["reviewablecount"]=ORM::Query("MyUser")->AssignedReviewableFileCount($R[$k]["class"]);
// 		}
		ORM::Dump($R);
	}
	
}