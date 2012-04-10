<?php
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity
 * @entity(repositoryClass="ReviewProgressSendRepository")
 * */
class ReviewProgressSend extends ReviewProgress
{
	
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
		parent::__construct($File, null, $IfPersist);
		$IfPersist ? $this->AssignMailSend($Mail) : $this->SetMailSend($Mail);
		
	}
	function  Summary()
	{
		if(!$this->MailSend)
			return "نامه یافت نشد.";
		$href=ViewMailPlugin::GetHref($this->MailSend, "Send");
		$r="اظهارنامه از ".$this->MailSend->SenderGroup()->PersianTitle()." با شماره نامه <a href='".$href."'>".$this->MailSend->Num()."</a> به <b>".$this->MailSend->ReceiverTopic()->Topic()."</b> ارسال شد.";
		return $r;
	}
	function Title()
	{
		return "ارسال ";
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
		$P=new ReviewProgressSend($File, $Mail, false);
		$ch=$P->Check();
		if(is_string($ch))
			return $ch;
		if($IfPersist) 
		{
			$P=new ReviewProgressSend($File, $Mail, true);
			$P->Apply();
			ORM::Persist($P);
		}
		return $P;
	}
}