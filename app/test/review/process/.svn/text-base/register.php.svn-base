<?php
class ReviewProcessRegisterTest extends JTest
{
	public $R;
	function __construct()
	{
	}
	
	function setUp()
	{
		
		$this->R=new ReviewProgressAssign();
		
		ORM::Write($this->R);
	}
	function tearDown()
	{
		$this->R=null;
	}
	function testStart()
	{
		$this->assertTrue(true);
		
	}

	function testAdd()
	{
		
		$id=123;
		do {
			$f=ORM::Find("ReviewFile",$id);
			$id++;
		}
		while(!$f);
		
		$p=$f->Progress();
		//ORM::Dump($p);
		ORM::Dump($p[2]);
		echo "hi".$p[2]->CreateTimestamp();
		ORM::Query("ReviewProcessRegister")->AddToFile($f);
		
		//ORM::Query("ReviewFile")->GetFilesWithLastProgress("ReviewProcessRegister");
	}

}