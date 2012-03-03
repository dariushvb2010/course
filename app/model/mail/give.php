<?php
use Doctrine\Common\Collections\ArrayCollection;
/**
 * 
 * Enter description here ...
 * @author sonukesh
 * @Entity
 * @Entity(repositoryClass="MailGiveRepository")
 */
class MailGive extends Mail
{
	
	/**
	 * 
	 * @ManyToOne(targetEntity="MyGroup", inversedBy="MailGive")
	 * @JoinColumn(name="GiverGroupID",referencedColumnName="ID")
	 * @var MyGroup
	 */
	protected $GiverGroup;
	function GiverGroup(){return $this->GiverGroup;}
	function SetGiverGroup($s){$this->GiverGroup=$s;}
	function AssignGiverGroup(MyGroup $GiverGroup)
	{
		$this->GiverGroup=$GiverGroup;
		$GiverGroup->MailGive()->add($this);
	}
	/**
	*
	* @ManyToOne(targetEntity="MyGroup", inversedBy="MailGet")
	* @JoinColumn(name="GetterGroupID",referencedColumnName="ID")
	* @var MyGroup
	*/
	protected $GetterGroup;
	function GetterGroup(){return $this->GetterGroup;}
	function SetGetterGroup($r){$this->GetterGroup=$r;}
	function AssignGetterGroup(MyGroup $GetterGroup)
	{
		$this->GetterGroup=$GetterGroup;
		$GetterGroup->MailGet()->add($this);
	}
	
	/**
	* @OneToMany(targetEntity="ReviewProgressGive", mappedBy="MailGive")
	* @var arrayCollectionOfReviewProgressGive
	*/
	protected $ProgressGive;
	function ProgressGive(){ return $this->ProgressGive;}
	
	function Save($Files)
	{
		$ErrorCount=0;
		if($Files)
		foreach ($Files as $File)
		{
			if(!$File) continue;
			$P=ORM::Query("ReviewProgressGive")->AddToFile($File,$this,false);//progress is not persist, it is just for error reporting
			if(is_string($P))
			{
				$Error=$P;
				$ErrorCount++;
			}
			else
				$Error=null;
			$this->UpdateStock($File, $Error);
		}
		return $ErrorCount;
	}
	function Give($Files)
	{
		$this->State=2;
		if($Files)
		if($this->Save($Files)===0)
		foreach ($Files as $File)
		{
			if(!$File) continue;
			$P=ORM::Query("ReviewProgressGive")->AddToFile($File,$this);//persist
			if(is_string($P))
			{
				b::$Error[]=$P;
			}
		}
	}
	
	function __construct($Num=null, $GiverGroup=null, $GetterGroup=null, $Comment=null)
	{
		parent::__construct($Num, $Comment);
		if($GiverGroup) $this->AssignGiverGroup($GiverGroup);
		if($GetterGroup) $this->AssignGetterGroup($GetterGroup);
		$this->ProgressGive= new ArrayCollection();
	}
}
use \Doctrine\ORM\EntityRepository;
class MailGiveRepository extends EntityRepository
{
	public function Add($Num=null, $GiverGroup=null, $GetterGroup=null, $Comment=null)
	{
		if(is_string($GiverGroup))
		{
			$GiverGroup=ORM::Find1("MyGroup","Title",$GiverGroup);
			if(!$GiverGroup)
				return "نام بخش تحویل دهنده به درستی وارد نشده است.";
		}
		if(!($GiverGroup instanceof MyGroup))
			return "بخش تحویل دهنده یافت نشد.";
		if(is_string($GetterGroup))
		{
			$GetterGroup=ORM::Find1("MyGroup","Title",$GetterGroup);
			if(!$GetterGroup)
			return "نام بخش تحویل گیرنده به درستی وارد نشده است.";
		}
		if(!($GetterGroup instanceof MyGroup))
		return "بخش تحویل گیرنده یافت نشد.";
		
		if(!is_numeric($Num))
			return "شماره نامه به درستی وارد نشده است.";
		$r=new MailGive($Num, $GiverGroup, $GetterGroup, $Comment);
		ORM::Persist($r);
		return $r;
	}
}