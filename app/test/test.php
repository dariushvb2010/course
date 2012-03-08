<?php 
class TestTest extends JTest
{
	function __construct(){}
	function setUp()
	{
		if(!MyUser::CurrentUser())
		{
			echo "No User Logged in!";
			die();
		}
	}
	function tearDown(){}
	function testStart()
	{
		$this->assertTrue(true);
		$p=ORM::Find("ReviewProgress", 131779);
		ORM::Dump($p);
	}
}