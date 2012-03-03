<?php
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity
 * @entity(repositoryClass="ReviewProgressGiveRepository")
 * */
class ReviewProgressGive extends ReviewProgress
{
	function CreateTimestamp()
	{
		if($this->MailGive)
		return $this->MailGive->EventTimestamp();
	}
	function CreateTime()
	{
		$jc=new CalendarPlugin();
		if($this->MailGive)
		return $jc->JalaliFullTime($this->MailGive->EventTimestamp());
	}
	/**
	*
	* @ManyToOne(targetEntity="MailGive", inversedBy="ProgressGive")
	* @JoinColumn(name="MailGiveID",referencedColumnName="ID")
	* @var MailGive
	*/
	protected $MailGive;
	function MailGive(){ return $this->MailGive; }
	function SetMailGive(MailGive $MailGive){ $this->MailGive=$MailGive; }
	function AssignMailGive($MailGive)
	{
		echo "AssignMailGive";
		$this->MailGive=$MailGive;
		$MailGive->ProgressGive()->add($this);
	}
	/**
	*
	* @OneToOne(targetEntity="ReviewProgressGet", mappedBy="ProgressGive")
	* @var ReviewProgressGet
	*/
	protected $ProgressGet;
	function ProgressGet(){return $this->ProgressGet;}
	
	function GiverGroup()
	{
		if($this->MailGive)
		return $this->MailGive->GiverGroup();
	}
	function GetterGroup()
	{
		if($this->MailGive)
		return $this->MailGive->GetterGroup();
	}
	function __construct(ReviewFile $File=null, MailGive $MailGive=null, $IfPersist=true)
	{
		$User=MyUser::CurrentUser();
		parent::__construct($File, $User, $IfPersist);
		if($MailGive) 
		$IfPersist ? $this->AssignMailGive($MailGive) : $this->SetMailGive($MailGive);
	}
	function  Summary()
	{
	
	}
	function Title()
	{
		return "تحویل به ";
	}
	function Event()
	{
		if(!$this->MailGive)
		throw new Exception("No MailGive provided!");
		$GiverGroup=$this->GiverGroup();
		$GetterGroup=$this->GetterGroup();
		if(!$GiverGroup OR !$GetterGroup)
		return;
		$Giver=strtolower($GiverGroup->Title());
		$Getter=strtolower($GetterGroup->Title());
		$res="Give_".$Giver."_to_".$Getter;
		return $res;
	}
}


use \Doctrine\ORM\EntityRepository;
class ReviewProgressGiveRepository extends EntityRepository
{
	/**
	 * 
	 * Enter description here ...
	 * @param ReviewFile|string|integer $File
	 * @param MailGive $MailGive
	 * @return string|Ambigous <string, number>
	 */
	public function AddToFile($File=null, MailGive $MailGive=null, $IfPersist=true)
	{
		$File=ReviewFile::GetRecentFile($File);
		if(!($File instanceof ReviewFile))
		{
			return "اظهارنامه یافت نشد.";
		}
		$P=new ReviewProgressGive($File,$MailGive,$IfPersist);
		$ch=$IfPersist ? $P->Apply() : $P->Check();
		if(is_string($ch))
		return $ch;
		if($IfPersist) 
		{
			echo "raft to if persist";
			ORM::Persist($P);
		}
		return $P;
	}
}