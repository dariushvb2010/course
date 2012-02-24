<?php
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity
 * @entity(repositoryClass="ReviewProgressSendfileRepository")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="Type", type="string")
 * */
class ReviewProgressSendfile extends ReviewProgress
{
	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $Requester;
	function Requester()
	{
		return $this->Requester;
	}
	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $Operator;
	function Operator()
	{
		return $this->Operator;
	}
	
	function __construct(ReviewFile $File=null,MyUser $User=null,$Requester=null,$Mailnum=null,$Comment=NULL,$Operator=NULL)
	{
		parent::__construct($File,$User);
		$this->Requester="";
		$this->Sendmailnum=0;
		$this->Comment="";
		$this->Operator="";
		if($Requester)$this->Requester=$Requester;
		if($Mailnum)$this->Mailnum=$Mailnum;
		if($Comment)$this->Comment=$Comment;
		if($Operator)$this->Operator=$Operator;
		
	}
	
	function  Summary()
	{
		return " این پرونده به درخواست ".$this->Requester." با شماره نامه ".$this->Sendmailnum." برایشان فرستاده شد ."."<br/>"
		."توضیحات:".substr($this->Comment, 0,40).(strlen($this->Comment)>40?'...':'');
		
	}
	function Title()
	{
		return "فرستادن پرونده";
	}
	
}


use \Doctrine\ORM\EntityRepository;
class ReviewProgressSendfileRepository extends EntityRepository
{
	/**
	 * 
	 * Enter description here ...
	 * @param ReviewFile $File
	 * @return string on error true on sucsess
	 */
	public function AddToFile($Cotag=null,$Requester=null,$Sendmailnum=null,$Comment=null,$Operator)
	{
		
		if ($Cotag<1)
		{
			$Error="کوتاژ ".$Cotag." ناصحیح است.";
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
					$Error="اظهارنامه با کوتاژ ".$Cotag."هنوز برای بازبینی تخصیص نیافته است.";
					return $Error;
					
				}	
				elseif (!$File->LastReview() || $File->LastReview()->Result()==false)//i don't know about these IFs
				{
					$Error="بازبینی اظهارنامه با کوتاژ".$Cotag." هنوز تکمیل نگردیده است.";
					return $Error;
				}
				
				else #FIXME: ensure logic blacklisted all errors
				{
					$User=MyUser::CurrentUser();
					$R=new ReviewProgressSendfile($File,$User,$Requester,$Sendmailnum,$Comment,$Operator);
					ORM::Persist($R);
					return true;
				}
			}
		}
		
	}
}