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
		return "Ø§Ø¸Ù‡Ø§Ø±Ù†Ø§Ù…Ù‡ Ø§Ø² Ø¯Ù�ØªØ± Ú©ÙˆØªØ§Ú˜ Ø¨Ù‡ Ø¨Ø§ÛŒÚ¯Ø§Ù†ÛŒ Ø¨Ø§Ø²Ø¨ÛŒÙ†ÛŒ ØªØ­ÙˆÛŒÙ„ Ø¯Ø§Ø¯Ù‡ Ø´Ø¯.";	
	}
	
	function Title()
	{
		return "ØªØ­ÙˆÛŒÙ„ Ø¨Ø§ÛŒÚ¯Ø§Ù†ÛŒ Ø¨Ø§Ø²Ø¨ÛŒÙ†ÛŒ";
	}
	function Event()
	{
		return "Give_cotag_to_archive";
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
		return 'this Progress is Deprecated';
		$Files=ORM::Query(new ReviewFile())->FilesInTimeRange($StartTime,$EndTime,MyUser::CurrentUser());
		if(!count($Files))
		{
			$Error="هیچ اظهارنامه ای در این بازه زمانی یافت نشد.";
			return $Error;
		}	
		foreach ($Files as $F)
		{
			if($F->LLP('Start'))
			{
				$F->LLP('Start')->SetMailNum($MailNum);
				$F->SetState(3);
			}
		}
		return $Files;
		
	}
}