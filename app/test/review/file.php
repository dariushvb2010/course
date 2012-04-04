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
		$f2=ORM::Find("ReviewFile",$ID);
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
	function testGetOnlyProgressStart()
	{
// 		$cotag=309;
// 		$r=ORM::Query(new ReviewProgressStart)->StartFile($cotag);
// 		var_dump($r);
// 		ORM::Flush();
// 		$File=ORM::Find(new ReviewFile,"Cotag",$cotag);
// 		ORM::Dump($File);
		
		//$File = new ReviewFile(2309);
		//ORM::Write($File);
		$r=ORM::Query(new ReviewFile)->GetOnlyProgressStart();
		ORM::Dump($r);
		
	}
	function testGetUnfinishedCountQuery()
	{
		$wt=new WebTracker();
		$start=$wt->LoadStart;
		$UFC1 = ORM::Query(new ReviewFile)->GetUnfinishedCount();
		
		for($i=0; $i<3; $i++)
		{
			$this->MakeNewMyFile(false);
			$progStart=ReviewProgressStartTest::StartFile($this->myFile,false);
			$progAssign = ReviewProgressAssignTest::AssignFile($this->myFile,null,null,false);
			$progReview=ReviewProgressReviewTest::ReviewFile($this->myFile,null,false);
		}
		ORM::Flush();
		$end=$wt->LoadEnd;
		//$wt->calculate($start,$end);
		$UFC2 = ORM::Query(new ReviewFile)->GetUnfinishedCount();
		$this->assertEqual($UFC2-$UFC1,3);
		$this->myFile->Finish();
		ORM::Flush();
		$UFC3 = ORM::Query(new ReviewFile)->GetUnfinishedCount();
		$this->assertEqual($UFC2-$UFC3,1);
	}
	function testGetFinishedCountQuery()
	{
		$FC1 = ORM::Query(new ReviewFile)->GetfinishedCount();
		for($i=0; $i<3; $i++)
		{
			$this->MakeNewMyFile(false);
			$progStart=ReviewProgressStartTest::StartFile($this->myFile,false);
			$progAssign = ReviewProgressAssignTest::AssignFile($this->myFile,null,null,false);
			$progReview=ReviewProgressReviewTest::ReviewFile($this->myFile,null,false);
			$this->myFile->Finish();
		}
		ORM::Flush();
		$FC2 = ORM::Query(new ReviewFile)->GetfinishedCount();
		$this->assertEqual($FC2-$FC1,3);
	}
	
	function testUnassignedFilesInRangeQuery()
	{
		//ReviewProgress::TruncateTables();
		$UAFIR1=ORM::Query(new ReviewFile)->UnassignedFilesInRange(0,21);
		for($i=1; $i<=10; $i++)
		{
			$this->MakeNewMyFile(false,$i);
			$progStart=ReviewProgressStartTest::StartFile($this->myFile,false);
			if($i%2==0)$progAssign = ReviewProgressAssignTest::AssignFile($this->myFile,null,null,false);
			if($i%4==0)$progReview=ReviewProgressReviewTest::ReviewFile($this->myFile,null,false);
			if($i%5==0)
			{
				$this->myFile->Finish();
				ORM::Flush();
			}
			ORM::Flush();
		}
		
		
		$UAFIR2=ORM::Query(new ReviewFile)->UnassignedFilesInRange(0,21);
		for($i=0; $i<5; $i++)
			$a1[]=$UAFIR2[$i]->Cotag();
		
		for($i=0; $i<5; $i++)
			$a2[]=$i*2+1;
		$this->assertEqual(count($UAFIR2)-count($UAFIR1),5);
	}
	function testGetCountQuery()
	{
		$R=new ReviewFile(13);
		$C=ORM::Query($R)->GetCount();
		ORM::Write($R);
		$C2=ORM::Query($R)->GetCount();
		$this->assertEqual($C,$C2-1);
	}

	function testGetMaxIDQuery()
	{
		$R=new ReviewFile(mt_rand(1,1000000000));
		$this->assertNotNull($R);
		ORM::Write($R);
		$ID=($R->ID());
		$r=ORM::Query(ReviewFile, "GetMaxID");
		$this->assertEqual($ID,$r);
	}
	
	
	function testGetUnfinishedListQuery()
	{
		ORM::Clear();
		
		//ReviewProgress::TruncateTables();
		$UFL=ORM::Query(new ReviewFile)->GetUnfinishedList(0,10,"Cotag","DESC");
// 		for($i=0; $i<count($UFL); $i++)
// 		ORM::Dump($UFL[$i]["Cotag"]);
		$maxCot=$UFL[0]['Cotag'];
		if($maxCot==null)$maxCot=0;
		$newCot=$maxCot+1;
		$f=ORM::Find(new ReviewFile, "Cotag",$newCot);  
		if($f==null)
		{
			$f=new ReviewFile($newCot);
			ORM::Write($f);
			$progStart=ReviewProgressStartTest::StartFile($f);
			//$progAssign = ReviewProgressAssignTest::AssignFile($f,null,$this->newUser,false);
			ORM::Query(new ReviewProgressAssign)->AddToFile($f);
			$progReview=ReviewProgressReviewTest::ReviewFile($f,$this->newUser);
			//$f->Finish();
			ORM::Flush();
		}
		else 
		{
			$f[0]->Finish();
			ORM::Flush();
		}
		$UFL=ORM::Query(new ReviewFile)->GetUnfinishedList(0,11,"Cotag","DESC");
		$addedCot=$UFL[0]['Cotag'];
		$this->assertEqual($newCot,$addedCot);
// 		for($i=0; $i<count($UFL); $i++)
// 		ORM::Dump($UFL[$i]["Cotag"]);
	}
	
	
}