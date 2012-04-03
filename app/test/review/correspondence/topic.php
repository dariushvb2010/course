<?php 
class ReviewCorrespondenceTopicTest extends JTest
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
		$this->assertTrue(true);

	}
	function testCreateReviewCorrespondenceTopic()
	{
		$t=new ReviewCorrespondenceTopic("to","co");
		ORM::Write($t);
		$f = new ReviewFile(13);
		ORM::Write($f);
		$m=new ReviewProgressManualcorrespondence($f,MyUser::CurrentUser(),4,$t->ID(),"ali","co");
		ORM::write($m);
		
		
	}
	function testDeleteQuery()
	{
		$t=new ReviewCorrespondenceTopic("ali","vlai");
		ORM::Write($t);
		$d=ORM::Delete($t);
		//ORM::Dump($d);
	}
}
