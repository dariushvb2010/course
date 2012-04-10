<?php
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity
 * @entity(repositoryClass="ReviewProgressReceiveRepository")
 * */
class ReviewProgressReceive extends ReviewProgress
{
	/**
	*
	* @ManyToOne(targetEntity="MailReceive", inversedBy="ProgressReceive")
	* @JoinColumn(name="MailReceiveID",referencedColumnName="ID")
	* @var MailReceive
	*/
	protected $MailReceive;
	function MailReceive(){ return $this->MailReceive; }
	function SetMailReceive(MailReceive $Mail){ $this->MailReceive=$Mail; }
	function AssignMailReceive( MailReceive $var)
	{
		$this->MailReceive=$var;
		$var->ProgressReceive()->add($this);
	}
	function __construct(ReviewFile $File=null, MailReceive $Mail=null, $IfPersist=true)
	{
		parent::__construct($File, null, $IfPersist);
		$IfPersist ? $this->AssignMailReceive($Mail) : $this->SetMailReceive($Mail);
	}
	
	function  Summary()
	{
		if(!$this->MailReceive)
			return "نامه یافت نشد.";
		$href=ViewMailPlugin::GetHref($this->MailReceive, "Receive");
		$r="اظهارنامه توسط ".$this->MailReceive->ReceiverGroup()->PersianTitle()." با شماره نامه <a href='".$href."'>".$this->MailReceive->Num()."</a> از <b>".$this->MailReceive->SenderTopic()->Topic()."</b> دریافت شد.";
		return $r;
	}
	function Title()
	{
		return "دریافت ";
	}
	function Event()
	{
		$R=$this->MailReceive->ReceiverGroup()->Title();
		$r="Receive_".strtolower($R)."_from_out";
		return $r;
	}
}


use \Doctrine\ORM\EntityRepository;
class ReviewProgressReceiveRepository extends EntityRepository
{
	public function AddToFile(ReviewFile $File,MailReceive $Mail, $IfPersist=true)
	{
		$File=ReviewFile::GetRecentFile($File);
		if(!$File)
		{
			return "اظهارنامه یافت نشد.";
		}
		$P=new ReviewProgressReceive($File, $Mail, false);
		$ch=$P->Check();
		if(is_string($ch))
			return $ch;
		if($IfPersist) 
		{
			$P=new ReviewProgressReceive($File, $Mail, true);
			$P->Apply();
			ORM::Persist($P);
		}
		return $P;
	}
}