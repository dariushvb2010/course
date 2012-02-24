<?php
class FileFsmTest extends JTest
{
	function __construct()
	{
	}
	
	
	function setUp()
	{
		//$a=(FileState::IsPre(4,5));
		//var_dump($a);
		//$this->assertTrue(FileState::IsPre(4,5));
		
	}
	function tearDown()
	{
		$this->R=null;
	}
	function testStart()
	{
		
// 		$this->assertTrue(true);
// 		$this->assertNotNull(MyUserTest::CurrentUser());
	}
	
	function testAdd()
	{
		FileFsm::Moderate();
	}
	
	
	
}