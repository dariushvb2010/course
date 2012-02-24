<?php
class MainTest extends JTestSuite 
{
	function __construct() {
		parent::__construct();
		$this->addFile(dirname(__FILE__).'/review/main.php');
		$this->addFile(dirname(__FILE__).'/my/user.php');
	}
}

