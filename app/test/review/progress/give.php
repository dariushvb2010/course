<?php
class ReviewProgressGiveTest extends JTest
{
	function __construct()
	{
		
	}
	function setUp()
	{
		if(!MyUser::CurrentUser())
		{
			echo "there are no user logged in!";
			die();
		}	
	}
	function tearDown()
	{
		
	}
	function testStart()
	{
		
		
		
		$MailGive1=ORM::Query("MailGive")->Add(rand(1,1000),"Archive","Raked","this is commet for MailGive");
		//$r=new ReviewProgressGive($File, $MailGive1);
		//$MailGive2=ORM::Query("MailGive")->Add(rand(1,1000),"Raked","Archive","this is commet for MailGive");
		//ORM::Dump($MailGive1);
		for($i=0; $i<1; $i++)
		{
			$File=TestPlugin::NewFile_Start();
			$File->SetState(10);
			$Files[]=$File;
		}
		ReviewFile::Regulate($Files);
		
		$MailGive1->Save($Files);
		
		foreach ($Files as $File)
		$this->assertTrue($File->Stock() instanceof FileStock);
 		$MailGive1->Give($Files);
//  		foreach ($Files as $File)
//  		$this->assertTrue($File->Stock()==null);
	}
	function testAdd()
	{	return;
		$MailGive=ORM::Query("MailGive")->Add(rand(1,1000),"Archive","Raked","this is comment for MailGive");
		
		if(is_string($Mail))
		{
			echo $Mail;
			return;
		}
		for($i=0; $i<1; $i++)
		{
			$File=TestPlugin::NewFile_Start();
			$File->SetState(11);
			$res=ORM::Query("ReviewProgressGive")->AddToFile($File, $MailGive);
			$this->assertTrue($res instanceof ReviewProgressGive);
			if(is_string($res))
			echo $File->Cotag().": ".$res."<br/>";
		}
		//echo ORM::Query("ReviewProgressStart")->CancelCotag($File->Cotag());
		//ORM::Query("ReviewProgressGive")->AddToFile($File);
	}
	function testSeeAlarm()
	{
		
		
	}
	

}