<?php
class ReviewProgressAssignTest extends JTest
{
	public $R;
	function __construct()
	{
	}
	/**
	*
	* add new 'assgin' progress to the given file
	* @param ReviewFile $f
	* @param MyUser $u
	* @return ReviewProgressAssign
	*/
	public static function AssignFile($f,$u=null,$r=null,$ifWrite=true)
	{
		if($u==null)
			$u=MyUserTest::CurrentUser();
		if($r==null)
			$r=ORM::Query(new MyUser)->getRandomReviewer();
		$res=new ReviewProgressAssign($f,$u,$r);
		if($ifWrite)
			ORM::Write($res);
		else 
			ORM::Persist($res);
		return $res;
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

	function testCreateProgressAssign()
	{
		$f=new ReviewFile(13);
		ORM::Write($f);
		
		$ass=ReviewProgressAssignTest::AssignFile($f);
		$this->assertNotNull($ass);
		
		

		ORM::Clear();
		$R2=ORM::Find1("ReviewProgressAssign","CreateTimestamp",1314433956);
		$this->assertEqual($R, $R2);
		
	}
	function testGetLast()
	{
		
		
		$f=new ReviewFile(12);
		ORM::Write($f);
		ReviewProgressAssignTest::AssignFile($f);
	}
	function AddToFileQuery()
	{
		$f=new ReviewFile(rand(1-100000));
		ORM::Write($f);
		ReviewProgressStartTest::StartFile($f);
		ORM::Query(new ReviewProgressAssign)->AddToFile($f);
		
		
	}

}