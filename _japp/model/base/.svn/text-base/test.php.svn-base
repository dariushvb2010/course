<?php
abstract class BaseTestClass extends UnitTestCase
{
	protected static $Count=0;
	function __construct($Title=null)
	{
		BaseTestClass::$Count++;
		if ($Title===null)
		$Title=get_class($this);
		parent::__construct($Title);
	}
	function __destruct()
	{
		BaseTestClass::$Count--;
		if (BaseTestClass::$Count==0)
		echo "<div style='font-weight:bold;font-size:smaller;padding:2px;'><a href='http://jframework.info/'>jFramework</a> Testing Framework 1.0 - Powered By <a href='http://simpletest.org'>SimpleTest</a></div>";
	}
}

abstract class JTest extends BaseTestClass {}

abstract class JTestSuite extends TestSuite
{
	function __construct() {
		parent::__construct();
	}
}
?>