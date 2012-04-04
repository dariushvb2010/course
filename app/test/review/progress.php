<?php
class ReviewProgressTest extends JTestSuite {
	function __construct() {
		parent::__construct();
		$this->addFile(dirname(__FILE__).'/progress/assign.php');
		$this->addFile(dirname(__FILE__).'/progress/finish.php');
		$this->addFile(dirname(__FILE__).'/progress/review.php');
		$this->addFile(dirname(__FILE__).'/progress/start.php');
	}
}