<?php
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity
 * @entity(repositoryClass="ReviewProgressReceiveRepository")
 * */
class ReviewProgressReceive extends ReviewProgress
{
	function CreateTimestamp()
	{
		if($this->MailReceive)
		return $this->MailReceive->EventTimestamp();
	}
	function CreateTime()
	{
		$jc=new CalendarPlugin();
		if($this->MailReceive)
		return $jc->JalaliFullTime($this->MailReceive->EventTimestamp());
	}
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
	function __construct(ReviewFile $File=null, MailReceive $MailReceive=null, $IfPersist=true)
	{
		$User=MyUser::CurrentUser();
		parent::__construct($File, $User, $IfPersist);
		$IfPersist ? $this->AssignMailReceive($Mail) : $this->SetMailReceive($Mail);
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
		return "Receive";
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
		$P=new ReviewProgressReceive($File, $Mail, $IfPersist);
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