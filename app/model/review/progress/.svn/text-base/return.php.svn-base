<?php
/** 
 * @Entity
 * @entity(repositoryClass="ReviewProgressReturnRepository")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="Type", type="string")
 * */
class ReviewProgressReturn extends ReviewProgress
{
	function __construct(ReviewFile $File=null,MyUser $User=null)
	{
		parent::__construct($File,$User);

	}
	
	function  Summary()
	{
		return "اظهارنامه از بایگانی راکد  به بایگانی بازبینی فرستاده شد.";	
	}
	
	function Title()
	{
		return "فرستادن از بایگانی راکد به بایگانی بازبینی";
	}
}
use \Doctrine\ORM\EntityRepository;
class ReviewProgressReturnRepository extends EntityRepository
{
	/**
	 * 
	 * @param integer $Cotag
	 * @return boolean for sucsess string for error
	 */
	public function AddToFile($Cotag)
	{
		if ($Cotag<1)
		{
				$Error="کوتاژ ناصحیح است.";
				return $Error;
		}
		else 
		{
			$File=ORM::Query(new ReviewFile)->GetRecentFile($Cotag);
			if ($File)
			{
				$thisUser=MyUser::CurrentUser();
				$start=new ReviewProgressReturn($File,$thisUser);
				ORM::Persist($start);    		
				return true;
			}
			else
			{
				$Error="اظهارنامه ای با این کوتاژ یافت نشد !";
				return $Error;
			}
		}
	}
}