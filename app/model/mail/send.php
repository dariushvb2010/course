<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * 
 * @author dariush jafari
 * @Entity(repositoryClass="MailSendRepository")
 */
class MailSend extends Mail
{
	/**
	* @ManyToOne(targetEntity="MyGroup", inversedBy="MailSend")
	* @JoinColumn(name="SenderGroupID",referencedColumnName="ID")
	* @var MyGroup
	*/
	protected $SenderGroup;
	function SenderGroup(){ return $this->SenderGroup; }
	function SetSenderGroup($s){$this->SenderGroup=$s; }
	function AssignSenderGroup(MyGroup $SenderGroup)
	{
		$this->SenderGroup=$SenderGroup;
		$SenderGroup->MailSend()->add($this);
	}
	/**
	 *
	 * @ManyToOne(targetEntity="ReviewTopic", inversedBy="MailSend")
	 * @JoinColumn(name="ReceiverTopicID",referencedColumnName="ID")
	 * @var ReviewTopic
	 */
	protected $ReceiverTopic;
	function ReceiverTopic(){ return $this->ReceiverTopic;}
	function SetReceiverTopic($r){ $this->ReceiverTopic=$r;}
	function AssignReceiverTopic(ReviewTopic $ReceiverTopic)
	{
		$this->ReceiverTopic=$ReceiverTopic;
		$ReceiverTopic->MailSend()->add($this);
	}
	/**
	 * @OneToMany(targetEntity="ReviewProgressSend", mappedBy="MailSend")
	 * @var arrayCollectionOfReviewProgressSend
	 */
	protected $ProgressSend;
	function ProgressSend(){ return $this->ProgressSend; }
	function MyBox()
	{
		if($this->State()==self::STATE_CLOSED)
			return $this->ProgressSend;
		
	}
	/**
	 * 
	 * Enter description here ...
	 * @param array_of_ReviewFile $Files
	 * @param boolean $RemoveCalled
	 * @param array_of_string $Error
	 * @return number
	 */
	function Save($Files, $RemoveCalled=true, &$Error)
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
			$P=ORM::Query("ReviewProgressSend")->AddToFile($File,$this,false);//progress is not persist, it is just for error reporting
		
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
	function Send($Files, $RemoveCalled, &$Error)
	{
		$SaveResult=$this->Save($Files, $RemoveCalled, $Error);
		if($Files AND $SaveResult===0)
		{
			foreach ($Files as $File)
			{
				if(!($File instanceof ReviewFile))
				continue;
				$P=ORM::Query("ReviewProgressSend")->AddToFile($File,$this);//persist
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
				$this->StateClosed();
			return true;
		}
		else
		return false;
	}
	function __construct($Num=null, $Subject=null, $SenderGroup=null, $ReceiverTopic=null, $Description=null)
	{
		parent::__construct($Num, $Subject, $Description);
		if($SenderGroup) $this->AssignSenderGroup($SenderGroup);
		if($ReceiverTopic) $this->AssignReceiverTopic($ReceiverTopic);
		$this->ProgressSend= new ArrayCollection();
	}

}

use \Doctrine\ORM\EntityRepository;
class MailSendRepository extends EntityRepository
{
	function Add($Num, $Subject, MyGroup $SenderGroup, ReviewTopic $ReceiverTopic, $Description)
	{
		if(is_string($SenderGroup))
		{
			$SenderGroup=ORM::Find1("MyGroup","Title",$SenderGroup);
			if(!$SenderGroup)
			return "نام بخش تحویل دهنده به درستی وارد نشده است.";
		}
		if(!($SenderGroup instanceof MyGroup))
			return "بخش تحویل دهنده یافت نشد.";
		if(is_numeric($ReceiverTopic))
		{
			$ReceiverTopic=ORM::Find1("ReviewTopic", $ReviewTopic);
			if(!$ReceiverTopic)
			return "نام بخش تحویل گیرنده به درستی وارد نشده است.";
		}
		if(!($ReceiverTopic instanceof ReviewTopic))
		return "بخش تحویل گیرنده یافت نشد.";
		
		$r=new MailSend($Num, $Subject, $SenderGroup, $ReceiverTopic, $Description);
		ORM::Persist($r);
		return $r;
	}
	function GetAll($SenderGroup='all', $ReceiverTopic='all', $State='all')
	{
		$s=" SELECT M FROM MailSend AS M JOIN M.SenderGroup I JOIN M.ReceiverTopic E ";
		$w=" WHERE ";
		$o=" ORDER BY M.RetouchTimestamp DESC,M.ID DESC";
		if($SenderGroup!='all' AND $ReceiverTopic!='all' AND $State!='all')
		$r=j::ODQL($s.$w."I=? AND E=? AND M.State=?".$o, $SenderGroup, $ReceiverTopic, $State);
		elseif($SenderGroup!='all' AND $ReceiverTopic!='all')
		$r=j::ODQL($s.$w."I=? AND E=?".$o, $SenderGroup, $ReceiverTopic);
		elseif($SenderGroup!='all' AND $State!='all')
		$r=j::ODQL($s.$w."I=? AND M.State=?".$o, $SenderGroup, $State);
		elseif ($ReceiverTopic!='all' AND $State!='all')
		$r=j::ODQL($s.$w."E=? AND M.State=?".$o, $ReceiverTopic, $State);
		elseif ($SenderGroup!='all')
		$r=j::ODQL($s.$w."I=?".$o, $SenderGroup);
		elseif($ReceiverTopic!='all')
		$r=j::ODQL($s.$w."E=?".$o,$ReceiverTopic);
		elseif ($State!='all')
		$r=j::ODQL($s.$w."M.State=?".$o,$State);
		else
		$r=j::ODQL($s.$o);
		return $r;
	}
	function LastMail(MyGroup $SenderGroup)
	{
		$r=j::ODQL("SELECT M FROM MailSend AS M JOIN M.SenderGroup S 
							WHERE S=?
					 		ORDER BY M.RetouchTimestamp DESC,M.ID DESC LIMIT 1",$SenderGroup);
		if ($r)
		return $r[0];
		else
		return null;
	}
}