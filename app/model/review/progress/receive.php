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
	function kill(){
		parent::kill();
// 		$m = $this->MailReceive();
// 		$m->ProgressReceive()->remove($this);
// 		$this->MailReceive=null;
	}
	function __construct(ReviewFile $File=null, MailReceive $Mail=null)
	{
		parent::__construct($File, null, $IfPersist);
		if($Mail)
			$this->SetMailReceive($Mail);
	}
	
	function  Summary()
	{
		if(!$this->MailReceive)
			return "نامه یافت نشد.";
		$href=ViewMailPlugin::GetHref($this->MailReceive, "Receive");
		$senderTopic=$this->MailReceive->SenderTopic()->Topic();
		$rcvGroup=$this->MailReceive->ReceiverGroup()->PersianTitle();
		$r="اظهارنامه توسط ".$rcvGroup." با شماره نامه ".v::link($this->MailReceive->Num(),array('href'=>$href))." از ".v::b($senderTopic)." دریافت شد.";
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
	public function AddAssociations(){
		parent::AddAssociations();
		$this->MailReceive->ProgressReceive()->add($this);
	}
}


use \Doctrine\ORM\EntityRepository;
class ReviewProgressReceiveRepository extends EntityRepository
{
	public function AddToFile(ReviewFile $File,MailReceive $Mail)
	{
		$P=new ReviewProgressReceive($File, $Mail);
		$ch=$P->Check();
		if(is_string($ch))
			return $ch;
		
		//$P=new ReviewProgressReceive($File, $Mail, true);
		$P->Apply();
		ORM::Persist($P);
		
		return $P;
	}
}