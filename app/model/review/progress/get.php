<?php
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity
 * @entity(repositoryClass="ReviewProgressGetRepository")
 * */
class ReviewProgressGet extends ReviewProgress
{
	/**
	*
	* @OneToOne(targetEntity="ReviewProgressGive", inversedBy="ProgressGet")
	* @JoinColumn(name="ProgressGiveID",referencedColumnName="ID", nullable=false)
	* @var ReviewProgressGive
	*/
	protected $ProgressGive;
	function ProgressGive(){ return $this->ProgressGive; }
	function SetProgressGive(ReviewProgressGive $ProgressGive)
	{
		$this->ProgressGive=$ProgressGive;
		
	}
	function AssignProgressGive(ReviewProgressGive $ProgressGive)
	{
		$this->ProgressGive=$ProgressGive;
		$ProgressGive->SetProgressGet($this);
	}
	function __construct(ReviewProgressGive $ProgressGive=null)
	{
		$User=MyUser::CurrentUser();
		parent::__construct($ProgressGive->File(), $User);
		$this->SetProgressGive($ProgressGive);
		
	}
	function  Summary()
	{
		if(!$this->ProgressGive)
			return "فرآیند تحویل یافت نشد.";
		$Mail=$this->ProgressGive->MailGive();
		if(!$Mail)
			return "نامه یافت نشد.";
		$href=ViewMailPlugin::GetHref($Mail, "Get");
		$r="اظهارنامه توسط ".$Mail->GetterGroup()->PersianTitle()." با شماره نامه ".v::link($Mail->Num(),$href)." از ".v::b($Mail->GiverGroup()->PersianTitle())." تحویل گرفته شد.";
		return $r;
	}
	function Title()
	{
		return "تحویل گرفتن ";
	}
	function Event()
	{
		$GiverGroup=$this->ProgressGive->MailGive()->GiverGroup();
		$GetterGroup=$this->ProgressGive->MailGive()->GetterGroup();
		if(!$GiverGroup OR !$GetterGroup)
		{
			return;
		}
		$Giver=strtolower($GiverGroup->Title());
		$Getter=strtolower($GetterGroup->Title());
		$res="Get_".$Getter."_from_".$Giver;
		return $res;
	}
	public function AddAssociations(){
		parent::AddAssociations();
		$this->ProgressGive->SetProgressGet($this);
	}
}


use \Doctrine\ORM\EntityRepository;
class ReviewProgressGetRepository extends EntityRepository
{
	/**
	* @param ReviewFile|string|integer $File
	* @return string|Ambigous <string, number>
	*/
	public function AddToFile(ReviewProgressGive $ProgressGive=null)
	{
		$P=new ReviewProgressGet($ProgressGive);
		$ch= $P->Check();
		if(is_string($ch))
			return $ch;
		
		//$P=new ReviewProgressGet($ProgressGive, true);
		$P->Apply();
		ORM::Persist($P);
		return $P;
	}
}