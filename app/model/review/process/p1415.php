<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @entity(repositoryClass="ReviewProcessP1415Repository")
 * */
class ReviewProcessP1415 extends ReviewProgress
{
	
	function __construct(ReviewFile $File=null,MyUser $User=null)
	{
		parent::__construct($File,$User);
	}	
	function  Summary()
	{
		$str="صاحب کالا در لیست ماده 14و15 ثبت شد.";
		return $str;
	}
	function Title()
	{
		return "ماده 14و 15";
	}
	function Event()
	{
		return "ProcessP1415";
	}
}


use \Doctrine\ORM\EntityRepository;
class ReviewProcessP1415Repository extends EntityRepository
{
	/**
	 * 
	 * @param ReviewFile $File
	 * @return string on error object on sucsess
	 */
	public function AddToFile(ReviewFile $File=null)
	{
		$CurrentUser=MyUser::CurrentUser();
		if($File)
		{
			$R=new ReviewProcessP1415($File, $CurrentUser);
			$err=$R->Apply();
			if(is_string($err)){
				$res['Error']=$err;
				return $res;
			}
			ORM::Persist($File);
			ORM::Write($R);
			$res['Class']=$File->GetClass();
		}
		else 
		{
			$res['Error']="اظهارنامه وجود ندارد.";	
		}
		return $res;
	}
}