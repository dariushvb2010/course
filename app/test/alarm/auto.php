<?php
class AlarmAutoTest extends JTest
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
		$c=4564539;
		//$res=ORM::Query("ReviewProgressStart")->StartFile($c);
		ORM::Dump($res);
		//$res=ORM::Query("ReviewProgressRegisterarchive")->AddToFile($c);
		
		if(j::Check("Reassign")) echo "HOOOOOOOOOOOOOOOOOOOO";
		$File=ORM::Find1("ReviewFile","Cotag", $c);
		echo "coid:".$File->ID();

		//$res=ORM::Query("ReviewProgressAssign")->AddToFile($c);
		if(!File)
			die();
		$res=ORM::Query(new ReviewProgressRegisterarchive())->AddToFile($c);
		
		//$res=ORM::Query("AlarmAuto")->Add($File,$CA);
		ORM::Dump($res);
	}
	
	

}