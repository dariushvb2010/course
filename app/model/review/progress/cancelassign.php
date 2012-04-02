<?php
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity
 * @entity(repositoryClass="ReviewProgressCancelassignRepository")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="Type", type="string")
 * */
class ReviewProgressCancelassign extends ReviewProgress
{
	function __construct(ReviewFile $File=null,MyUser $User=null)
	{
		parent::__construct($File,$User);
	}
	
	function  Summary()
	{
		return "تخصیص اظهارنامه لغو شد.";
	}
	function Title()
	{
		return "لغو تخصیص";
	}
	
}


use \Doctrine\ORM\EntityRepository;
class ReviewProgressCancelassignRepository extends EntityRepository
{
	/**
	 * 
	 * Enter description here ...
	 * @param ReviewFile $File
	 * @return string on error object on sucsess
	 */
	public function AddToFile(ReviewFile $File=null,$Comment=null)
	{
		$CurrentUser=MyUser::CurrentUser();
		if ($File==null)
		{
			$Error="اظهارنامه‌ای با شماره کوتاژ داده شده در سیستم ثبت نشده است.";
			return $Error;
		}
		else{
			$lp = $File->LastProgress();
			if($lp instanceof ReviewProgressAssign AND j::Check('Reassign'))
			{
				$R=new ReviewProgressCancelassign($File,$CurrentUser);
				ORM::Persist($R);
				return true;
			}
			else if($lp instanceof ReviewProgressReview AND j::Check('Reassign'))
			{
				$Error="این اظهارنامه توسط کارشناس  ". $File->LastReviewer()."  باربینی شده است.";
				return $Error;
			}
			else 
			{
				$Error="در این مرحله نمی توان تخصیص را لغو کرد. از کوتاژ مورد نظر گزارش گیری نمایید.";
				return $Error;
			}
		
		}
		
	}
}