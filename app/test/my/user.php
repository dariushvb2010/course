<?php
class MyUserTest extends JTest
{
	public $R;
	function __construct()
	{
	}
	public static function CurrentUser()
	{
		$u=ORM::Find("MyUser",j::UserID());
		return $u;
	}
	function setUp()
	{
		$R=new MyUser($this->rand_string(5),$this->rand_string(5));
		ORM::Write($R);
		ORM::Delete($R);
	}
	function tearDown()
	{
		$this->R=null;
	}
	function testStart()
	{
		$this->assertTrue(true);
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
	function testGetRandomReviewer()
	{
		ORM::Query("MyUser")->getRandomReviewer();
		die();
	}
	function testCreateUser()
	{		
		
		$R=new MyUser($this->rand_string(20),$this->rand_string(20));
		ORM::Write($R);
		$this->assertNotNull($R);
		ORM::Delete($R);
		$R=null;
		
		$R=new MyUser($this->rand_string(20),$this->rand_string(20),$this->rand_string(20),$this->rand_string(20));
		ORM::Write($R);
		$this->assertNotNull($R);
		ORM::Delete($R);
		$R=null;
		
		$R=new MyUser($this->rand_string(20),$this->rand_string(20),$this->rand_string(20),$this->rand_string(20),true);
		ORM::Write($R);
		$this->assertNotNull($R);
		ORM::Delete($R);
		$R=null;
	}
	
	function testgetReviewerCount(){
		$c1=ORM::Query(new MyUser())->getReviewerCount();
		
		$R1=new MyUser($this->rand_string(20),$this->rand_string(20),$this->rand_string(20),$this->rand_string(20));
		ORM::Write($R1);
		
		$c2=ORM::Query(new MyUser())->getReviewerCount();
		$this->assertEqual($c1, $c2);
		
		$R2=new MyUser($this->rand_string(20),$this->rand_string(20),$this->rand_string(20),$this->rand_string(20),true);
		ORM::Write($R2);
		
		$c3=ORM::Query(new MyUser())->getReviewerCount();
		$this->assertEqual($c3-1, $c2);
		
		ORM::Delete($R2);
		ORM::Delete($R1);
	}
// 	function testgetRandomReviewer()
// 	{
// 		$c=5;
// 		for($i=0;$i<$c;$i++){
// 			$R[$i]=new MyUser($this->rand_string(5),$this->rand_string(5),$this->rand_string(5),$this->rand_string(5),true);
// 			ORM::Write($R[$i]);
// 		}
		
// 		$t=50;
// 		$f=false;
// 		$u1=ORM::Query(new MyUser())->getRandomReviewer();
// 		for($i=0;$i<$c;$i++){
// 			$u=ORM::Query(new MyUser())->getRandomReviewer();
// 			if($u!=$u1)$f=true;
// 		}
		
// 		$this->assertTrue($f);
		
// 		for($i=0;$i<$c;$i++){
// 			ORM::Delete($R[$i]);
// 		}
// 	}
	function testAssignedReviewableFileCount(){
		$r=ORM::Query(new MyUser())->getRandomReviewer();
		if($r==null){
			$r=new MyUser($this->rand_string(5),$this->rand_string(5),$this->rand_string(5),$this->rand_string(5),true);	
			ORM::Write($r);
			$this->assertEqual(0,1);
		}
		$bayg=MyUser::CurrentUser();
		
		$c=rand(2,5);
		for($i=0;$i<$c;$i++){
			$File[$i]=new ReviewFile(mt_rand(1,100000));
			ORM::Persist($File[$i]);
			$start[$i]=new ReviewProgressStart($File[$i],$bayg);
			ORM::Write($start[$i]);
			$assign[$i]=new ReviewProgressAssign($File[$i],$bayg,$r);
			ORM::Write($assign[$i]);
		}
	
		$f=ORM::Query(new MyUser())->AssignedReviewableFileCount($r);
		$this->assertEqual($f,$c);
	
		for($i=0;$i<$c;$i++){
			ORM::Delete($assign[$i]);
			ORM::Delete($start[$i]);
			ORM::Delete($File[$i]);
		}
	}

}