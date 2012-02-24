<?php
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity
 * @entity(repositoryClass="ReviewProgressReceivefileRepository")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="Type", type="string")
 * */
class ReviewProgressReceivefile extends ReviewProgress
{
	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $Sender;
	
	
	function __construct(ReviewFile $File=null,MyUser $User=null,$Sender=null,$Receivemailnum=null,$Comment=NULL)
	{
		parent::__construct($File,$User);
		$this->Sender="";
		$this->Receivemailnum=0;
		$this->Comment="";
		if($Sender)$this->Sender=$Sender;
		if($Receivemailnum)$this->Mailnum=$Receivemailnum;
		if($Comment)$this->Comment=$Comment;
	}
	
	function  Summary()
	{
		return " این پرونده از ".$this->Sender." با شماره نامه ".$this->Receivemailnum." دریافت شد ."."<br/>"
		."توضیحات:".substr($this->Comment, 0,40).(strlen($this->Comment)>40?'...':'');
	}
	function Title()
	{
		return "پس گرفتن پرونده";
	}
	
}


use \Doctrine\ORM\EntityRepository;
class ReviewProgressReceivefileRepository extends EntityRepository
{
	/**
	 * 
	 * Enter description here ...
	 * @param ReviewFile $File
	 * @return string on error object on sucsess
	 */
	public function AddToFile($Cotag=null,$Sender=null,$Receivemailnum=null,$Comment=null)
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
					$Error="این اظهارنامه هنوز برای بازبینی تخصیص نیافته است.";
					return $Error;
					
				}	
				elseif ($File->LastReview()==null)//i don't know about these IFs
				{
					$Error="بازبینی اظهارنامه هنوز تکمیل نگردیده است.";
					return $Error;
				}
				
				else #FIXME: ensure logic blacklisted all errors
				{
					$User=MyUser::CurrentUser();
					$R=new ReviewProgressReceivefile($File,$User,$Sender,$Receivemailnum,$Comment);
					ORM::Persist($R);
					return true;
				}
			}
		}
		
	}
	public function LastSend($Cotag)
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
				$lastProg=$File->LastProgress();
				if(!($lastProg instanceof ReviewProgressSendfile))
				{
					$Error=" آخرین فرایند این اظهار نامه ارسال نبوده است.";
					return $Error;
				}
				else 
				{
					return $lastProg;
				}
			}
		}
	}
}