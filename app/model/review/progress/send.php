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
	function SetMailSend($Mail){ $this->MailSend=$Mail; }
	function AssignMailSend($Mail)
	{
		$this->MailSend=$Mail;
		$Mail->ProgressSend()->add($this);
	}
	function __construct(ReviewFile $File=null, MailSend $Mail=null, $IfPersist=true)
	{
		$User=MyUser::CurrentUser();
		parent::__construct($File, $User, $IfPersist);
		$IfPersist ? $this->AssignMailSend($Mail) : $this->SetMailSend($Mail);
		
	}
	
	function  Summary()
	{
		$href=ViewMailPlugin::GetHref($this, "Send");
		$r="اظهارنامه از ".$this->MailSend->SenderGroup()->PersianTitle()." با شماره نامه <a href='".$href."'>".$this->MailSend->Num()."</a> به ".$this->MailSend->ReceiverTopic()->Topic()." ارسال شد.";
		return $r;
	}
	function Title()
	{
		return "ارسال به خارج ";
	}
	function Event()
	{
		$r="Send_";
		$Sender=$this->MailSend->SenderGroup()->Title();
		$r="Send_".strtolower($Sender)."_to_out";
		return $r;
	}
}


use \Doctrine\ORM\EntityRepository;
class ReviewProgressSendRepository extends EntityRepository
{
	public function AddToFile(ReviewFile $File,MailSend $Mail, $IfPersist=true)
	{
		$File=ReviewFile::GetRecentFile($File);
		if(!$File)
		{
			return "اظهارنامه یافت نشد.";
		}
		$P=new ReviewProgressSend($File, $Mail, $IfPersist);
		$ch=$IfPersist ? $P->Apply() : $P->Check();
		if(is_string($ch))
			return $ch;
		if($IfPersist) 
		{
			ORM::Persist($P);
		}
		return $P;
	}
}