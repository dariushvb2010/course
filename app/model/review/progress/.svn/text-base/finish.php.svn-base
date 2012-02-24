<?php
/** 
 * @Entity
 * @entity(repositoryClass="ReviewProgressFinishRepository")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="Type", type="string")
 * */
class ReviewProgressFinish extends ReviewProgress
{
	function __construct(ReviewFile $File=null,MyUser $User=null)
	{
		parent::__construct($File,$User);

	}
	
	function  Summary()
	{
		return "اظهارنامه مختومه اعلام گردید.";	
	}
	
	function Title()
	{
		return "ارسال به بایگانی بازبینی";
	}
}
use \Doctrine\ORM\EntityRepository;
class ReviewProgressFinishRepository extends EntityRepository
{
	/**
	 * 
	 * @param integer $Cotag
	 * @return boolean for sucsess string for error
	 */
	public Function FinishByCotag($Cotag)
	{
		if ($Cotag<1)
		{
			$Error="کوتاژ ناصحیح است.";
			return $Error;
		}
		else 
		{
			$File=ORM::Query(new ReviewFile)->GetRecentFile($Cotag);
			if ($File==null)
			{
				$Error="اظهارنامه ای با شماره کوتاژ ".$Cotag." اصلا وارد سیستم نگردیده است.";
				return $Error;
			}
			else
			{
				if ($File->LastReviewer()==null)
				{
					$Error="این اظهارنامه برای بازبینی تخصیص نیافته است.";
					return $Error;
					
				}	
				elseif ($File->LastReview()==null || $File->LastReview()->Result()==false)
				{
					$Error="بازبینی اظهارنامه هنوز تکمیل نگردیده است.";
					return $Error;
				}
				elseif($File->LastProgress() instanceof  ReviewProgressFinish)
				{
					
					$Error="آخرین پروسه این اظهار نامه ارسال به بایگانی راکد بوده است .";
					return $Error;
				}
				else #FIXME: ensure logic blacklisted all errors
				{
					$File->Finish();
					return true;
				}
			}
		}
	}
	public Function AddToFile(ReviewFile $File)
	{
		if (!$File)
		{
			$Error="خطا.";
			return $Error;
		}
		else 
		{
			if ($File->LastReviewer()==null)
			{
				$Error="این اظهارنامه هنوز برای بازبینی تخصیص نیافته است.";
				return $Error;
				
			}	
			elseif ($File->LastReview()==null || $File->LastReview()->Result()==false)
			{
				$Error="بازبینی اظهارنامه هنوز تکمیل نگردیده است.";
				return $Error;
			}
			elseif($File->LastProgress() instanceof  ReviewProgressFinish)
			{
					
					
					$Error="آخرین پروسه این اظهار نامه ارسال به بایگانی راکد بوده است .";
					return $Error;
			}
			else #FIXME: ensure logic blacklisted all errors
			{
				$File->Finish();
		 		return true;
			}
		
		}
	}
}