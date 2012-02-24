<?php
use Symfony\Component\Console\Input\StringInput;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity
 * @entity(repositoryClass="ReviewProgressFinishcorrespondenceRepository")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="Type", type="string")
 * */
class ReviewProgressFinishcorrespondence extends ReviewProgress
{
	
	function __construct(ReviewFile $File=null,MyUser $User=null)
	{
		parent::__construct($File,$User);
	}
	
	function  Summary()
	{
		return  " ختم مکاتبات این پرونده اعلام گردید";
	}
	function Title()
	{
		return "ختم مکاتبات ";
	}
	
}


use \Doctrine\ORM\EntityRepository;
class ReviewProgressFinishcorrespondenceRepository extends EntityRepository
{
	/**
	 * 
	 * Enter description here ...
	 * @param ReviewFile $File
	 * @return string on error object on sucsess
	 */
	public function AddToFile($Cotag=null)
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
			else if( $File->LastProgress() instanceof ReviewProgressManual)
			{
				$Error="این اظهارنامه قبلا به عنوان پرونده مختومه قبلی ثبت گردیده.";
				return $Error;
				
			}
			else
			{
				if ($File->LastReviewer()==null)
				{
					$Error="این اظهارنامه هنوز برای بازبینی تخصیص نیافته است.";
					return $Error;
					
				}	
				elseif ($File->LastReview()==null)
				{
					$Error="بازبینی اظهارنامه هنوز تکمیل نگردیده است.";
					return $Error;
				}
				else if( $File->LastProgress() instanceof ReviewProgressFinish)
				{
					$Error="این اظهارنامه قبلا مختومه شده.";
					return $Error;
					
				}
				else if( $File->LastProgress() instanceof ReviewProgressFinishCorrespondence)
				{
					$Error="مکاتبات این اظهارنامه قبلا مختومه شده.";
					return $Error;
					
				}
				else #FIXME: ensure logic blacklisted all errors
				{
					$User=MyUser::CurrentUser();
					$R=new ReviewProgressFinishcorrespondence($File,$User);
					ORM::Persist($R);
					return true;
				}
				
			}
		}
		
	}
}