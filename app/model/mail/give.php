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
	function PersianSource()
	{
		return $this->GiverGroup->PersianTitle();
	}
	function PersianDest()
	{
		return $this->GetterGroup->PersianTitle();
	}
	
	function SaveGet($Files, &$Error)
	{
		if($this->State()==self::STATE_GETTING)
		{
			$this->RetouchTimestamp=time();
		}
		elseif($this->State()==self::STATE_INWAY)
		{
			$this->StateGetting();
			$this->RetouchTimestamp=time();
		}
		else
		{
			$Error[]="امکان ذخیره کردن وجود ندارد.";
			return 1;
		}
		$ErrorCount=0;
		foreach ($this->Stock as $s)
			$s->SetIfSaveGet(false);
		$time=time();
		foreach ($Files as $File)
		{
			if(!($File instanceof ReviewFile))
			{
				$Error[]=strval($File);
				continue;
			}
			if($File->Stock())
			if($File->Stock()->Act())
			{
				$File->Stock()->SetIfSaveGet(true);
				continue;
			}
			$ProgressGive=$File->LLP("Give");
			if($this->ProgressGive->contains($ProgressGive))
			{
				$P=ORM::Query("ReviewProgressGet")->AddToFile($ProgressGive,false);//progress is not persist, it is just for error reporting
				if(is_string($P))
				{
					$E=$P;
					$ErrorCount++;
				}
				else
					$E=null;
			}
			else 
			{
				j::Log("Unexpected", "به این بخش تحویل داده نشده است. in mail/give");
				$E="به این بخش تحویل داده نشده است.";
				$ErrorCount++;
			}
			$this->UpdateStock($File, $E);
			$File->Stock()->SetIfSaveGet(true);
		}	
		return $ErrorCount;
	}
	
	function Get($Files, &$Error)
	{
		$SaveResult=$this->SaveGet($Files, $Error);
		if($Files AND $SaveResult===0)
		{
			foreach ($Files as $File)
			{
				if(!($File instanceof ReviewFile))
				continue;
				if(!$File->Stock()->Act())
				{
					$ProgressGive=$File->LLP("Give");
					if($this->ProgressGive->contains($ProgressGive))
					{
						$P=ORM::Query("ReviewProgressGet")->AddToFile($ProgressGive);//persist
						
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
							$File->Stock()->SetAct(true);
							$this->UpdateStock($File, "انجام شد");
						}
					}
					else
					{
						j::Log("Unexpected", "به این بخش تحویل داده نشده است. in mail/give");
						$this->UpdateStock($File, " به این بخش تحویل داده نشده است.");
					}
				}
			}
			if($faulty)
				$this->StateEditingFaulty();
			return true;
		}
		else
		return false;
	}
	function Complete($Files, $RemoveCalled, &$Error)
	{
		if($this->State()==self::STATE_EDITING)
		{
			if($this->Act($Files, $RemoveCalled, $Error))
			{
				return $this->StateInway();
			}
			else
				return false;
		}
		elseif($this->State()==self::STATE_GETTING)
		{
			if($this->Get($Files, $Error))
			{
				return $this->StateClosed();
			}
			else
				return false;
		}
		else 
			return false;
	}
	function MyBox()
	{
		if($this->State()==self::STATE_CLOSED)
		return $this->ProgressGive;
	
	}
	function GetProgress()
	{
		return ORM::Query($this)->GetProgress($this);
	}
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
	function LastMail(MyGroup $GiverGroup, MyGroup $GetterGroup, $State)
	{
		$r=j::ODQL("SELECT M FROM MailGive AS M JOIN M.GiverGroup I JOIN M.GetterGroup E
						WHERE I=? AND E=? AND M.State=?
				 		ORDER BY M.RetouchTimestamp DESC,M.ID DESC LIMIT 1",$GiverGroup, $GetterGroup, $State);
		if ($r)
		return $r[0];
		else
		return null;
	}
	function GetAll($GiverGroup='all', $GetterGroup='all', $State='all')
	{
		$s=" SELECT M FROM MailGive AS M JOIN M.GiverGroup I JOIN M.GetterGroup E ";
		$w=" WHERE ";
		$o=" ORDER BY M.RetouchTimestamp DESC,M.ID DESC";
		if($GiverGroup!='all' AND $GetterGroup!='all' AND $State!='all')
			$r=j::ODQL($s.$w."I=? AND E=? AND M.State=?".$o, $GiverGroup, $GetterGroup, $State);
		elseif($GiverGroup!='all' AND $GetterGroup!='all')
			$r=j::ODQL($s.$w."I=? AND E=?".$o, $GiverGroup, $GetterGroup);
		elseif($GiverGroup!='all' AND $State!='all')
			$r=j::ODQL($s.$w."I=? AND M.State=?".$o, $GiverGroup, $State);
		elseif ($GetterGroup!='all' AND $State!='all')
			$r=j::ODQL($s.$w."E=? AND M.State=?".$o, $GetterGroup, $State);
		elseif ($GiverGroup!='all')
			$r=j::ODQL($s.$w."I=?".$o, $GiverGroup);
		elseif($GetterGroup!='all')
			$r=j::ODQL($s.$w."E=?".$o,$GetterGroup);
		elseif ($State!='all')
			$r=j::ODQL($s.$w."M.State=?".$o,$State);
		else 
			$r=j::ODQL($s.$o);
		return $r;
	}
	function Search(MyGroup $GiverGroup,MyGroup $GetterGroup, $State='all', $Num=null, $Subject=null)
	{
		if($Num)
			$Num="%".$Num."%";
		if($Subject)
			$Subject="%".$Subject."%";
		$s=" SELECT M FROM MailGive AS M JOIN M.GiverGroup I JOIN M.GetterGroup E ";
		$w=" WHERE I=? AND E=? ";
		$o=" ORDER BY M.RetouchTimestamp DESC,M.ID DESC";
		if($Num AND $Subject AND $State!='all' )
		$r=j::ODQL($s.$w."AND M.State=? AND M.Num LIKE ? AND M.Subject LIKE ?".$o, $GiverGroup, $GetterGroup, $State, $Num, $Subject);
		elseif($State!='all' AND $Num)
		$r=j::ODQL($s.$w."AND M.State=? AND M.Num LIKE ?".$o, $GiverGroup, $GetterGroup, $State, $Num);
		elseif($State!='all' AND $Subject)
		$r=j::ODQL($s.$w."AND M.State=? AND M.Subject LIKE ?".$o, $GiverGroup, $GetterGroup, $State, $Subject);
		elseif($Num AND $Subject)
		$r=j::ODQL($s.$w."AND M.Num LIKE ? AND M.Subject LIKE ?".$o, $GiverGroup, $GetterGroup, $Num, $Subject);
		elseif ($State!='all')
		$r=j::ODQL($s.$w."AND M.State=?".$o, $GiverGroup, $GetterGroup, $State);
		elseif($Num)
		$r=j::ODQL($s.$w."AND M.Num LIKE ?".$o,$GiverGroup, $GetterGroup, $Num);
		elseif ($Subject)
		$r=j::ODQL($s.$w."AND M.State=?".$o,$GiverGroup, $GetterGroup, $Subject);
		else
		{
			$r=j::ODQL($s.$w.$o,$GiverGroup, $GetterGroup);
		}
		return $r;
	}
	function GetProgress($Mail)
	{
		$r=j::ODQL("SELECT P FROM ReviewProgressGive P JOIN P.MailGive M JOIN P.File F WHERE M=? ORDER BY F.Cotag", $Mail);
		return $r;
	}
}