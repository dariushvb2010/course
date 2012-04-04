<?php
class ReviewMainTest extends JTestSuite {
	function __construct() {
		parent::__construct();
		$this->addFile(dirname(__FILE__).'/file.php');
		$this->addFile(dirname(__FILE__).'/progress.php');
	}
	
	function testCreateReview()
	{
	
	}
}