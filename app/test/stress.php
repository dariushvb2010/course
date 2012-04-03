<?php
class StressTest extends JTest
{
	function __construct()
	{
		parent::__construct();
	}
	function setUp()
	{
		
	}
	function tearDown()
	{
		
	}
	function testStressFiles()
	{
		$stressCount=1000;
		$m=new WebTracker();
		for ($i=0;$i<$stressCount;$i++)
		{
			$f=new ReviewFile(1000+$i);
			$p=new ReviewProgressStart($f,MyUser::CurrentUser());
			ORM::Persist($f);
			ORM::Persist($p);
			
			if ($i % 500 == 499)
			{
				ORM::Flush();
				ORM::Clear();
			}
		}
		
		$this->assertTrue($i>$stressCount-1);
		echo "Stress testing {$stressCount} files with only start progress took ".($t=$m->Calculate())." seconds.".BR;
		echo "Makes for ".( $t/$stressCount)." seconds for each.".BR;
	}
	function testStressProgress()
	{
		$stressCount=100;
		$stressDepth=100;
		$m=new WebTracker();
		for ($i=0;$i<$stressCount;$i++)
		{
			$f=ORM::Find("ReviewFile", 1000+$i);
			for ($j=0;$j<$stressDepth;++$j)
			{
				$x=mt_rand(1,1200);
				if ($x<250) $p=new ReviewProgressAssign($f,MyUser::CurrentUser(),MyUser::CurrentUser());
				elseif ($x<500) $p=new ReviewProgressManual($f,MyUser::CurrentUser());
				elseif ($x<750) $p=new ReviewProgressReview($f,MyUser::CurrentUser());
				elseif ($x<1000) $p=new ReviewProgressFinish($f,MyUser::CurrentUser());
				else $p=new ReviewProgressStart($f,MyUser::CurrentUser());
				ORM::Persist($p);
				if ($i*$stressDepth+$j % 100 == 99)
				{
					ORM::Flush();
					ORM::Clear();
				}
			}
			
		}
		$this->assertTrue($i>$stressCount-1);
		echo "Stress testing {$stressCount} files with {$stressDepth} progresses each ".($t=$m->Calculate())." seconds.".BR;
		echo "Makes for ".( $t/$stressCount)." seconds for each file.".BR;
		echo "Makes for ".( $t/$stressCount/$stressDepth)." seconds for each progress.".BR;
	}
	
}