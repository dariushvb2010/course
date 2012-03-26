<?php
use Doctrine\Common\Collections\ArrayCollection;
/**
 * 
 * @author sonukesh
 * @Entity
 * @Entity(repositoryClass="MailGiveRepository")
 */
class MailGive extends Mail
{
	/**
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
	function GiveTimestamp()
	{
		if($this->ProgressGive)
			return $this->ProgressGive[0]->CreateTimestamp();
		else
			return 0;
	}
	/**
	 * GetTimestamp = CloseTimestamp
	 * TODO correct that to work properly
	 */
	function GetTimestamp()
	{
		if($this->ProgressGive)
			if($this->ProgressGive[0]->ProgressGet())
				return $this->ProgressGive[0]->ProgressGet()->CreateTimestamp();
			else return 0;
		else 
			return 0;
	}
	/**
	 * Has to save the mail, usefull for both sides of givergroup and gettergroup
	 * @param array $Files
	 * @param boolean $RemoveCalled
	 * @param array $Error
	 * @return number of Errors
	 */
	function Save($Files, $RemoveCalled, & $Error)
	{
		if($this->State()==self::STATE_EDITING)
		{
			$this->SaveTimestamp=time();
			return $this->SaveGive($Files, $RemoveCalled, $Error);
		}
		else if($this->State()==self::STATE_GETTING)
		{
			$this->SaveTimestamp=time();
			return $this->SaveGet($Files, $RemoveCalled, $Error);
		}
		else
		{
			$Error[]="امکان ذخیره کردن وجود ندارد.";
			return 1;			
		}
	}
	function SaveGive($Files, $RemoveCalled,& $Error)
	{
		$ErrorCount=0;
		$time=time();
		foreach ($Files as $File)
		{
			if(!($File instanceof ReviewFile))
			{
				$Error[]=strval($File);
				continue;
			}
				$P=ORM::Query("ReviewProgressGive")->AddToFile($File,$this,false);//progress is not persist, it is just for error reporting
				
			if(is_string($P))
			{
				$E=$P;
				$ErrorCount++;
			}
			else
				$E=null;
			$this->UpdateStock($File, $E);
		}
		if($RemoveCalled)
		foreach($this->Stock as $s)
		if($s->EditTimestamp()<$time)
		{
			$s->File()->SetStock(null);
			$this->Stock->removeElement($s);
			$s->SetMail(null);
			ORM::Delete($s);
		}
		return $ErrorCount;
	}
	function SaveGet($Files, $RemoveCalled,& $Error)
	{
		$ErrorCount=0;
		$time=time();
		foreach ($Files as $File)
		{
			if(!($File instanceof ReviewFile))
			{
				$Error[]=strval($File);
				continue;
			}
			$P=ORM::Query("ReviewProgressGet")->AddToFile($File,$this,false);//progress is not persist, it is just for error reporting
			if(is_string($P))
			{
				$E=$P;
				$ErrorCount++;
			}
			else
			$E=null;
			$this->UpdateStock($File, $E);
		}	
		if($RemoveCalled)
			$this->RemoveOldStocks($time);
		return $ErrorCount;
	}
	function Give($Files, $RemoveCalled,& $Error)
	{
		$SaveResult=$this->Save($Files, $RemoveCalled, $Error);
		if($Files AND $SaveResult===0)
		{
			foreach ($Files as $File)
			{
				if(!($File instanceof ReviewFile))
					continue;
				$P=ORM::Query("ReviewProgressGive")->AddToFile($File,$this);//persist
				if(is_string($P))
				{
					$faulty=true;
					$this->UpdateStock($File, $P);
					$Error[]=$P;
					$Error[]="تعدادی از اظهارنامه ها ارسال نشد. این یک خطای ناجور است. در صورت مشاهده آن به مسئولین نرم افزار اطلاع دهید.";
					return false;
				}
				else // progress has been persisted successfully
				{
					$s=$File->Stock();
					$File->SetStock(null);
					$this->Stock->removeElement($s);
					$s->SetMail(null);
					ORM::Delete($s);
				}
			}
			if($faulty)
				$this->StateEditingFaulty();
			else 
				$this->StateInway();
			return true;
		}
		else 
			return false;
	}
	function Get()
	{
		
	}
// 	private function GetBelowTime($Time)
// 	{
// 		return ORM::Query("MailGive")->GetBelowTime($this, $Time);
// 	}
	function __construct($Num=null, $Subject=null, $GiverGroup=null, $GetterGroup=null, $Description=null)
	{
		parent::__construct($Num, $Subject, $Description);
		if($GiverGroup) $this->AssignGiverGroup($GiverGroup);
		if($GetterGroup) $this->AssignGetterGroup($GetterGroup);
		$this->ProgressGive= new ArrayCollection();
	}
}
use \Doctrine\ORM\EntityRepository;
class MailGiveRepository extends EntityRepository
{
	public function Add($Num=null, $Subject=null, $GiverGroup=null, $GetterGroup=null, $Description=null)
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
		
		$r=new MailGive($Num, $Subject, $GiverGroup, $GetterGroup, $Description);
		ORM::Persist($r);
		return $r;
	}
	function LastMail(MyGroup $GiverGroup, MyGroup $GetterGroup)
	{
		$r=j::ODQL("SELECT M FROM MailGive AS M JOIN M.GiverGroup I JOIN M.GetterGroup E
						WHERE I=? AND E=?
				 		ORDER BY M.RetouchTimestamp DESC,M.ID DESC LIMIT 1",$GiverGroup, $GetterGroup);
		if ($r)
		return $r[0];
		else
		return null;
	}
	function GetAll($GiverGroup='all', $GetterGroup='all', $State='all')
	{
		$s="SELECT M FROM MailGive AS M JOIN M.GiverGroup I JOIN M.GetterGroup E";
		$w=" WHERE ";
		$o=" ORDER BY M.RetouchTimestamp DESC,M.ID DESC";
		if($GiverGroup!='all' AND $GetterGroup!='all' AND $State!='all')
			$r=j::ODQL($s.$w."I=? AND E=?".$o, $GiverGroup, $GetterGroup);
		elseif($GiverGroup!='all' AND $GetterGroup!='all')
			;
		elseif($GiverGroup!='all' AND $State!='all')
			;
		elseif ($GetterGroup!='all' AND $State!='all')
			;
		elseif ($GiverGroup!='all')// && GetterGroup!='all'
			$r=j::ODQL($s.$w."E=?".$o,$GetterGroup);
		elseif($GetterGroup!='all')// && GiverGroup!='all'
			$r=j::ODQL($s.$w."I=?".$o, $GiverGroup);
		elseif ($State!='all')
		;
		else // GiverGroup!='all' && GetterGroup!='all'
			$r=j::ODQL($s.$o);
		return $r;
	}
// 	public function GetBelowTime($Mail, $Time)
// 	{
// 		$r=j::ODQL("SELECT S FROM FileStock S JOIN S.Mail M WHERE M=? AND S.EditTimestamp<?", $Mail, $Time);
// 		if(count($r))
// 			return $r;
// 		else
// 			return null;
// 	}
}