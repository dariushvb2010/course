<?php
/** 
 * @Entity
 * @Entity(repositoryClass="ReviewProgressRegisterarchiveRepository")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="Type", type="string")
 * */
class ReviewProgressRegisterarchive extends ReviewProgress
{
	function __construct(ReviewFile $File=null,MyUser $User=null)
	{
		parent::__construct($File,$User);

	}
	
	function  Summary()
	{
		return "اظهارنامه توسط بایگانی بازبینی وصول گردید.";	
	}
	
	function Title()
	{
		return "وصول بایگانی بازبینی";
	}
	function Event()
	{
		return "Get_archive_from_cotag";
	}
}
use \Doctrine\ORM\EntityRepository;
class ReviewProgressRegisterarchiveRepository extends EntityRepository
{
	/**
	 * 
	 * @param integer $Cotag
	 * @return string for error boolean for true;
	 */
	public function AddToFile($Cotag,$Time=null)
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
				if($File->LLP() instanceof ReviewProgressRegisterarchive)
				{
					$Error="یک بار وصول شده است .";
					return $Error;
				}
				else
				{
					$lp=$File->LLP() ;
					if($lp instanceof ReviewProgressDeliver or $lp =null || $lp instanceof ReviewProgressSendfile ||  $lp instanceof ReviewProgressStart )//felan oke ta badan 
					{
						$thisUser=MyUser::CurrentUser();
						$Registerarchive=new ReviewProgressRegisterarchive($File,$thisUser);
						if($Time)$Registerarchive->SetCreateTimestamp($Time);
						$ch=$Registerarchive->Apply();
						if(is_string($ch))
							return $ch;
						ORM::Persist($Registerarchive);  
						return true;
					}
					else 
					{
						$Error="کوتاژ قابل وصول نیست .";
						return $Error;
					}
				}
			}
			else
			{
				$Error="اظهارنامه یافت نشد!";
				return $Error;
			}
		}
	}
	public function AddByFile(ReviewFile $File)
	{
		
		if ($File)
		{
			if($File->LLP() instanceof ReviewProgressRegisterarchive)
			{
				$Error="اظهارنامه با کوتاژ".$File->Cotag()."یک بار وصول شده است .";
				return $Error;
			}
			else
			{
				$lp=$File->LLP() ;
				if($lp instanceof ReviewProgressDeliver or $lp =null || $lp instanceof ReviewProgressSendfile ||  $lp instanceof ReviewProgressStart )//felan oke ta badan 
				{
					$thisUser=MyUser::CurrentUser();
					$Registerarchive=new ReviewProgressRegisterarchive($File,$thisUser);
					if($Time)$Registerarchive->SetCreateTimestamp($Time);
					ORM::Persist($Registerarchive);    		
					return true;
				}
				else 
				{
					$Error="کوتاژ".$File->Cotag()." قابل وصول نیست .";
					return $Error;
				}
			}
		}
		else
		{
			$Error="اظهارنامه به کوتاژ".$File->Cotag()." یافت نشد!";
			return $Error;
		}
		
	}
	public function CancelCotag($Cotag)
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
				$Error="این کوتاژ وصول نشده است.";
				return $Error;
			}
			else 
			{
				$LastProg=$File->LLP();
				if(!($LastProg instanceof ReviewProgressRegisterarchive))
				{
					$Error="آخرین فرایند روی این کوتاژ وصول بایگانی بازبینی نبوده است.";
					return $Error;
				}
				else if($LastProg instanceof ReviewProgressRegisterarchive)
				{
					$thisUser=MyUser::CurrentUser();
					ORM::Delete($LastProg);
					return true;
				}
				else 
				{
					$Error="احتمالا خطایی در سیستم رخ داده. با مسئولین نرم افزار تماس بگیرید";
					return $Error;
				}
			}
		}
	}
}