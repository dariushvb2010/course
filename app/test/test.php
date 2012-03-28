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
		$M=ORM::Find("Mail", 14);
		ORM::Dump($M);
	}
}