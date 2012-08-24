<?php 
class ReviewProgressStartTest extends JTest
{
	function __construct()
	{
	}
	/**
	 * 
	 * add new 'start' progress to the given file
	 * @param ReviewFile $f
	 * @param MyUser $u
	 * @return ReviewProgressStart
	 */
	public static function StartFile($f,$u=null,$ifWrite=true)
	{
		if($u==null)
			$res=new ReviewProgressStart($f,MyUserTest::CurrentUser());
		else 
			$res=new ReviewProgressStart($f,$u);
		if($ifWrite)
			ORM::Write($res);
		else 
			ORM::Persist($res);
		return $res;
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

}
