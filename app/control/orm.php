<?php
class OrmController extends JControl
{
	
	function Start()
	{
		$v=new ProgressValidator();
//	var_dump(preg_match("/abc{1,}/", "abcabc"));
//		var_dump($v->ValidationString2Regexp("Assign(1-)Review(1-2)"));
//		var_dump($v->ValidateProgressarray($d,"/^Assign{1,}Review{1,2}$/"));
//var_dump(ProgressValidator::Validate(5,"(Assign|Review)[1]*[1]"));
//		$u=new MyUser("admin","admin","عباس","نادری",true);
// 		ORM::Write($u);
		return $this->Present();
	}
	
}