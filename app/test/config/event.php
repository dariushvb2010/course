<?php
class ConfigEventTest extends JTest
{
	function __construct()
	{
	}
	function setUp()
	{
		
	}
	function tearDown()
	{
	}
	function testStart()
	{
		$this->assertTrue(true);
		
	}
	function testAdd()
	{	
		$list=FsmGraph::$ProcessList;
		if($list)
		foreach ($list as $Title=>$PersianTitle)
		{
			$res=ORM::Query("ConfigEvent")->Add($Title, $PersianTitle);
			var_dump($res);
		}
	}
	
	

}