<?php
class ReviewMailTest extends JTest
{
	public $myFile;
	public $newUser;
	function __construct()
	{
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
	
	function testAdd()
	{
			
	}
	
	
	
}