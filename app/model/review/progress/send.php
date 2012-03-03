<?php
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity
 * @entity(repositoryClass="ReviewProgressSendRepository")
 * */
class ReviewProgressSend extends ReviewProgress
{
	function CreateTimestamp()
	{
		if($this->MailSend)
		return $this->MailSend->EventTimestamp();
	}
	function CreateTime()
	{
		$jc=new CalendarPlugin();
		if($this->MailSend)
		return $jc->JalaliFullTime($this->MailSend->EventTimestamp());
	}
	/**
	*
	* @ManyToOne(targetEntity="MailSend", inversedBy="ProgressSend")
	* @JoinColumn(name="MailSendID",referencedColumnName="ID")
	* @var MailSend
	*/
	protected $MailSend;
	function MailSend(){ return $this->MailSend; }
	function SetMailSend($MailSend){ $this->MailSend=$MailSend; }
	
	function __construct(ReviewFile $File=null, $MailSend=null)
	{
		parent::__construct($File);
		$this->SetMailSend($MailSend);
	}
	
	function  Summary()
	{
		if($this->Reviewer)
			return "اظهارنامه به کارشناس بازبینی "."<b>".$this->Reviewer()->getFullName()."</b>"." تخصیص داده شد.";
		else 
			return "خطا در گزارش گیری";
	}
	function Title()
	{
		return "تحویل به ";
	}
	function Event()
	{
		return "Assign";
	}
}


use \Doctrine\ORM\EntityRepository;
class ReviewProgressSendRepository extends EntityRepository
{
	public function AddToFile(ReviewFile $File,ReviewMail $MailSend)
	{
		$File=ReviewFile::GetRecentFile($File);
		if(!$File)
		{
			return "اظهارنامه یافت نشد.";
		}
		$P=new ReviewProgressSend();
		
		if($Last->isSend()!=1)
		{
			$Error="آخرین فرایند اظهارنامه با کوتاژ ".$File->Cotag()."ارسال نبوده است.بلکه دریافت بوده  است.";
			return $Error;
		}
		$countSend=ORM::Query(new ReviewMail)->GetCountType($Mail,1);
		$countReceive=ORM::Query(new ReviewMail)->GetCountType($Mail,0);
		if($countReceive+1==$countSend)
		{

			$Mail->SetState(2);

		}
				
		$User=MyUser::CurrentUser();
		$R=new ReviewProgressPost($File,$User,$Mail,$IsSend);
		$ch=$R->Apply();
		if(is_string($ch))
		return $ch;
		ORM::Persist($R);
		ORM::Flush();
		return true;
	}
}