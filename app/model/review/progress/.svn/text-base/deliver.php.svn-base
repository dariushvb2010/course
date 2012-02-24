<?php
/** 
 * @Entity
 * @entity(repositoryClass="ReviewProgressDeliverRepository")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="Type", type="string")
 * */
class ReviewProgressDeliver extends ReviewProgress
{
	function __construct(ReviewFile $File=null,MyUser $User=null)
	{
		parent::__construct($File,$User);

	}
	
	function  Summary()
	{
		return "اظهارنامه از دفتر کوتاژ به بایگانی بازبینی تحویل داده شد.";	
	}
	
	function Title()
	{
		return "تحویل بایگانی بازبینی";
	}
}
use \Doctrine\ORM\EntityRepository;
class ReviewProgressDeliverRepository extends EntityRepository
{
	/**
	 * 
	 * @param integer $Cotag
	 * @return boolean for sucsess string for error
	 */
	public Function AddToFiles($StartTime,$EndTime,$MailNum)
	{
		
		$Files=ORM::Query(new ReviewFile())->FilesInTimeRange($StartTime,$EndTime,MyUser::CurrentUser());
		if(!count($Files))
		{
			$Error="هیچ اظهارنامه ای در بازه مشخص شده وجود ندارد.";
			return $Error;
		}	
		foreach ($Files as $F)
		{
			if($F->LastProgress('Start'))
				$F->LastProgress('Start')->SetMailNum($MailNum);
		}
		return $Files;
		
	}
}