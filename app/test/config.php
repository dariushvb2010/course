<?php 
class ConfigTest extends JTest
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

	}
	function testSeeAlarm()
	{
		$jc = new CalendarPlugin();
		var_dump(2764800/(24*3600));
		$ali = $jc->JalaliFromTimestamp(2764800/TIMESTAMP_DAY);
		var_dump($ali);
		
	}
	

}