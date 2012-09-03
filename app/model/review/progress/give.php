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
	/**
	 * it means the error of the stock, it is just used in the autolist 
	 */
	function Error()
	{
		if($this->File()->Stock())
		return $this->File()->Stock()->Error();
	}
	
	function kill(){
		parent::kill();
		$m = $this->MailGive();
		$f = $this->File();
		$s = $f->Stock();
 		if($s){
			$f->SetStock(null);
			$m->Stock()->remove($s);
			ORM::Delete($s);
 		}
	}
	function __construct(ReviewFile $File=null, MailGive $MailGive=null)
	{
		parent::__construct($File, null);
		if($MailGive) 
			$this->SetMailGive($MailGive);
	}
	function  Summary()
	{
		if(!$this->MailGive)
			return "نامه یافت نشد.";
		$href=ViewMailPlugin::GetHref($this->MailGive, "Give");
		$destGroup=$this->MailGive->GetterGroup()->PersianTitle();
		$fromGroup=$this->MailGive->GiverGroup()->PersianTitle();
		$r="اظهارنامه از ".$fromGroup." با شماره نامه ".v::link($this->MailGive->Num(),array('href'=>$href))." به ".v::b($destGroup)." تحویل داده شد.";
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
		$GiverGroup=$this->MailGive->GiverGroup();
		$GetterGroup=$this->MailGive->GetterGroup();
		if(!$GiverGroup OR !$GetterGroup)
		{
			return;
		}
		$Giver=strtolower($GiverGroup->Title());
		$Getter=strtolower($GetterGroup->Title());
		$res="Give_".$Giver."_to_".$Getter;
		return $res;
	}
	public function AddAssociations(){
		parent::AddAssociations();
		$this->MailGive->ProgressGive()->add($this);
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
	public function AddToFile($File=null, MailGive $MailGive=null)
	{
		$File=b::GetFile($File);
		if(!($File instanceof ReviewFile))
		{
			return "اظهارنامه یافت نشد.";
		}
		$P=new ReviewProgressGive($File,$MailGive);
		$ch=$P->Check();
		if(is_string($ch))
			return $ch;
		$P->Apply();
		ORM::Persist($P);
		return $P;
	}
}