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
	function AssignMailReceive( MailReceive $var)
	{
		$this->MailReceive=$var;
		$var->ProgressReceive()->add($this);
	}
	function __construct(ReviewFile $File=null, MailReceive $MailReceive=null)
	{
		parent::__construct($File);
		if($MailReceive) $this->AssignMailReceive($MailReceive);
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
class ReviewProgressReceiveRepository extends EntityRepository
{
	
}