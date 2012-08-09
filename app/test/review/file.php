<?php
class ReviewFileTest extends JTest
{
	public $myFile;
	public $newUser;
	function __construct()
	{
	}
	
	
	/**
	*
	* Enter description here ...
	* @param unknown_type $cot
	* @return ReviewFile
	*/
	function WriteFile($cot)
	{
		$f= new ReviewFile($cot);
		ORM::Write($f);
		return $f;
	}
	function PersistFile($cot)
	{
		$f=new ReviewFile($cot);
		ORM::Persist($f);
		return $f;
	}
	
	function rand_string($len, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789')
	{
		$string = '';
		for ($i = 0; $i < $len; $i++)
		{
		$pos = rand(0, strlen($chars)-1);
		$string .= $chars{$pos};
		}
		return $string;
	}
	function MakeNewMyFile($ifWrite=true,$cotag=null)
	{
		if($cotag==null)
		$cotag=rand(1,1000000);
		$this->myFile=new ReviewFile($cotag);
		if($ifWrite)
			ORM::Write($this->myFile);
		else
			ORM::Persist($this->myFile);
		/*
		$username=$this->rand_string(15);
		$password=$this->rand_string(15);
		$firstName=$this->rand_string(15);
		$lastName=$this->rand_string(15);
		$this->newUser=new Myuser($username,$password,$firstName,$lastName,true);
		ORM::Persist($this->newUser);
		*/
	}
	function setUp()
	{
		$this->MakeNewMyFile();
		//$this->R=new ReviewFile();
		//ORM::Write($this->R);
		/*
		$this->myFile=new ReviewFile(rand(1,1000000));
		ORM::Write($this->myFile);
		$username=$this->rand_string(15);
		$password=$this->rand_string(15);
		$firstName=$this->rand_string(15);
		$lastName=$this->rand_string(5);
		$this->newUser=new Myuser($username,$password,$firstName,$lastName,true);
		ORM::Persist($this->newUser);
		*/
	}
	function tearDown()
	{
		$this->R=null;
	}
	function testStart()
	{
		$this->assertTrue(true);
		$this->assertNotNull(MyUserTest::CurrentUser());
	}
	
	function testCreateFile()
	{
		$newCotag=13;
		$now=time();
		$f=new ReviewFile($newCotag);
		ORM::Write($f);
		$f2=$this->WriteFile(14);
		$this->assertNotNull($f);
	
		
		$ID=($f->ID());
		$cotag=$f->Cotag();
		$progress=$f->Progress();
		
		$this->assertTrue($ID>0);
		$this->assertTrue($cotag==$newCotag);
		$this->assertTrue($f->CreateTime()>0);
		$this->assertTrue($f->FinishTime()>0);
		$this->assertNotNull($progress);
		$this->assertTrue(count($progress)==0);
		ORM::Clear();
		$f2=b::GetFile($ID);
		//ORM::Dump($f);
		$this->assertTrue($f->ID()==$f2->ID());
		$this->assertEqual($f->Cotag(),$f2->Cotag());
		
		$this->assertEqual($f->CreateTime(),$f2->CreateTime());
		$this->assertEqual($f->FinishTime(),$f2->FinishTime());
		$this->assertEqual(count($f->Progress()),count($f2->Progress()));
	}
	
	function testSetFinishTime()
	{
		$t=time()+1000;
		$f=new ReviewFile(rand(1,100000));
		$this->myFile->setFinishTime($t);
		$tc = new CalendarPlugin();
		$t=$tc->JalaliFromTimestamp($t)." ".date("H:i:s",$t);
		$this->assertEqual($this->myFile->FinishTime(),$t);
	}
	function testLastReviewer()
	{
		$this->MakeNewMyFile();
		ReviewProgressStartTest::StartFile($f);
		$progAssign = ReviewProgressAssignTest::AssignFile($this->myFile);
		ReviewProgressReviewTest::ReviewFile($this->myFile);
		
		$flr=$this->myFile->lastReviewer();//file last reviewer

		$this->assertTrue($flr->FirstName(),MYUser::CurrentUser()->FirstName());
		$this->assertTrue($flr->LastName(),MyUser::CurrentUser()->LastName());
		$this->assertTrue($flr->IsReviewer());
		$this->assertEqual($progAssign->Reviewer(),$flr);
		$this->myFile->Finish();
		$this->assertEqual($progAssign->Reviewer(),$flr);
		//ORM::Dump($f->LastReviewer());
	}
	function testLastProgress()
	{
		$this->MakeNewMyFile();
		$this->assertNull($this->myFile->LastProgress());
		$progStart=ReviewProgressStartTest::StartFile($this->myFile);
		
		$this->assertEqual($progStart,$this->myFile->lastProgress());
		$progAssign = ReviewProgressAssignTest::AssignFile($this->myFile);
		$this->assertEqual($progAssign,$this->myFile->lastProgress());
		$progReview=ReviewProgressReviewTest::ReviewFile($this->myFile);
		$this->assertEqual($progReview,$this->myFile->lastProgress());
		
		$this->assertEqual($this->myFile->LastProgress("Start"),$progStart);
		$this->assertEqual($this->myFile->LastProgress("Assign"),$progAssign);
		$this->assertEqual($this->myFile->LastProgress("Review"),$progReview);
	}
	function testAllProgress()
	{
		$this->MakeNewMyFile();
		$progStart=ReviewProgressStartTest::StartFile($this->myFile);
		$progAssign = ReviewProgressAssignTest::AssignFile($this->myFile);
		$progReview=ReviewProgressReviewTest::ReviewFile($this->myFile);
		
		$this->assertTrue(ProgressValidator::Validate($this->myFile,"Start[1]Assign[1]Review[1]"));
		$this->myFile->Finish();
		ORM::Flush();
		$this->assertTrue(ProgressValidator::Validate($this->myFile,"Start[1]Assign[1]Review[1]Finish[1]"));
		$allP=$this->myFile->AllProgress();
// 		ORM::Dump($this->myFile);
// 		ORM::Dump($allP);
	}
	
}