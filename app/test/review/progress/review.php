<?php 
class ReviewProgressReviewTest extends JTest
{
	function __construct()
	{
	}
	/**
	 * 
	 * add new 'review' progress to the given file
	 * @param ReviewFile $f
	 * @param MyUser $u
	 * @return ReviewProgressReview
	 */
	public static function ReviewFile($f,$u=null,$result=true,$ifWrite=true)
	{
		if($u==null)
			$u=MyUserTest::CurrentUser();
		$res=new ReviewProgressReview($f,$u);
		$res->SetResult($result);//
		$res->SetProvision(104);//
		if($ifWrite)
			ORM::Write($res);
		else 
			ORM::Persist($res);
		return $res;
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
	function testAmount()
	{
		$post["Difference"]="Value";
		$post["Provision"]="528";
		$post["Amount"]="1000000";
		$post["Result"]=1;
		$post["Cotag"]=6065434;
		$files=ORM::Query("ReviewFile")->GetFilesWithLastProgress("RegisterArchive");
		$this->assertTrue(count($files)>0);
		$post["Cotag"]=$files[2]->Cotag();
		$res=ORM::Query("ReviewProgressReview")->AddReview($post["Cotag"],$post);
		echo $res;
	}

}
