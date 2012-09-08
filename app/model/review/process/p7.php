<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @entity(repositoryClass="ReviewProcessP7Repository")
 * */
class ReviewProcessP7 extends ReviewProgress
{
	
	function __construct(ReviewFile $File=null,MyUser $User=null)
	{
		parent::__construct($File,$User);
	}	
	function  Summary()
	{
		$str="صاحب کالا در لیست ماده ۷ و ۸ ثبت شد.";
		return $str;
	}
	function Title()
	{
		return "ماده ۷ و ۸";
	}
	function Manner()
	{
		return "ProcessP78";
	}
}


use \Doctrine\ORM\EntityRepository;
class ReviewProcessP7Repository extends EntityRepository
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
			$R=new ReviewProcessP78($File, $CurrentUser);
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