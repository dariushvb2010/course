<?php
class ReviewFile2Test extends JTest
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
	}
	function testRegulate()
	{
		$Files=array(123,234,456,567,606232);
		$Files=ReviewFile::Regulate($Files);
		$IsReviewFile=true;
		foreach ($Files as $File)
		{
			if(!($File instanceof ReviewFile))
			$IsReviewFile=false;
		}
		$this->assertTrue($IsReviewFile);
	}
	function testRegulateWithError()
	{
		$Files=array(123,234,456,567,6062323);
		$Files=ReviewFile::RegulateWithError($Files);
		$IsReviewFile=true;
		foreach ($Files as $File)
		{
			if(!($File instanceof ReviewFile OR is_string($File)))
			$IsReviewFile=false;
		}
		$this->assertTrue($IsReviewFile);
	}
	
}