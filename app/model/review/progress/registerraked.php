<?php
/** 
 * @Entity
 * @Entity(repositoryClass="ReviewProgressRegisterrakedRepository")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="Type", type="string")
 * */
class ReviewProgressRegisterraked extends ReviewProgress
{
	function __construct(ReviewFile $File=null,MyUser $User=null)
	{
		parent::__construct($File,$User);

	}
	
	function  Summary()
	{
		return "اظهارنامه توسط بایگانی راکد وصول گردید.";	
	}
	
	function Title()
	{
		return "وصول بایگانی راکد";
	}
}
use \Doctrine\ORM\EntityRepository;
class ReviewProgressRegisterrakedRepository extends EntityRepository
{
	/**
	 * 
	 * @param integer $Cotag
	 * @return string for error boolean for true;
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
				$Register=new ReviewProgressRegisterraked($File,$thisUser);
				ORM::Persist($Register);    		
				return true;
			}
			else
			{
				$Error="اظهارنامه یافت نشد!";
				return $Error;
			}
		}
	}
}