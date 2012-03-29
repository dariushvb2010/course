<?php
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity
 * @entity(repositoryClass="ReviewProgressGiveRepository")
 * */
class ReviewProgressGive extends ReviewProgress
{
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
		$this->MailGive=$MailGive;
		$MailGive->ProgressGive()->add($this);
	}
	/**
	* @OneToOne(targetEntity="ReviewProgressGet", mappedBy="ProgressGive")
	* @var ReviewProgressGet
	*/
	protected $ProgressGet;
	function ProgressGet(){return $this->ProgressGet;}
	function SetProgressGet(ReviewProgressGet $ProgressGet){ $this->ProgressGet=$ProgressGet;}
	function GiverGroup()
	{
		return $this->MailGive->GiverGroup();
	}
	function GetterGroup()
	{
		return $this->MailGive->GetterGroup();
	}
	/**
	 * it means the error of the stock, it is just used in the autolist 
	 */
	function Error()
	{
		return null;
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
		$href=ViewMailPlugin::GetHref($this->MailGive, "Give");
		$r="اظهارنامه از ".$this->MailGive->GiverGroup()->PersianTitle()." با شماره نامه <a href='".$href."'>".$this->MailGive->Num()."</a> به <b>".$this->MailGive->GetterGroup()->PersianTitle()."</b> تحویل داده شد.";
		return $r;
	}
	function Title()
	{
		return "تحویل دادن ";
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
			ORM::Persist($P);
		}
		return $P;
	}
}