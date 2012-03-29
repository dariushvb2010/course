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
	function Search(MyGroup $SenderGroup,ReviewTopic $ReceiverTopic=null, $State='all', $Num=null, $Subject=null)
	{
		if($Num)
			$Num="%".$Num."%";
		if($Subject)
			$Subject="%".$Subject."%";
		
		$s=" SELECT M FROM MailSend AS M JOIN M.SenderGroup I JOIN M.ReceiverTopic E ";
		$w=" WHERE I=? ";
		$o=" ORDER BY M.RetouchTimestamp DESC,M.ID DESC";
		if($Num AND $Subject AND $State!='all' AND $ReceiverTopic)
			$r=j::ODQL($s.$w."AND E=? AND M.State=? AND M.Num LIKE ? AND M.Subject LIKE ?".$o, $SenderGroup, $ReceiverTopic, $State, $Num, $Subject);
		//---------------33333333333333333--------------------------------------------
		elseif($ReceiverTopic AND $State!='all' AND $Num)
			$r=j::ODQL($s.$w."AND E=? AND M.State=? AND M.Num LIKE ?".$o, $SenderGroup, $ReceiverTopic, $State, $Num);
		elseif($ReceiverTopic AND $State!='all' AND $Subject)
			$r=j::ODQL($s.$w."AND E=? AND M.State=? AND M.Subject LIKE ?".$o, $SenderGroup, $ReceiverTopic, $State, $Subject);
		elseif($ReceiverTopic AND $Num AND $Subject)
			$r=j::ODQL($s.$w."AND E=? AND M.Num LIKE ? AND M.Subject LIKE ?".$o, $SenderGroup, $ReceiverTopic, $Num, $Subject);
		elseif($Num AND $Subject AND $State!='all')
			$r=j::ODQL($s.$w."AND M.State=? AND M.Num LIKE ? AND M.Subject LIKE ?".$o, $SenderGroup, $State, $Num, $Subject);
		//------------------------222222222222222222222-------------------------------------
		
		elseif($ReceiverTopic AND $State!='all')
			$r=j::ODQL($s.$w."AND E=? AND M.State=?".$o, $SenderGroup, $ReceiverTopic, $State);
		elseif($ReceiverTopic AND $Num)
			$r=j::ODQL($s.$w."AND E=? AND M.Num LIKE ?".$o, $SenderGroup, $ReceiverTopic, $Num);
		elseif($ReceiverTopic AND $Subject)
			$r=j::ODQL($s.$w."AND E=? AND M.Subject LIKE ?".$o, $SenderGroup, $ReceiverTopic, $Subject);
		elseif($State!='all' AND $Num)
			$r=j::ODQL($s.$w."AND M.State=? AND M.Num LIKE ?".$o, $SenderGroup, $State, $Num);
		elseif($State!='all' AND $Subject)
			$r=j::ODQL($s.$w."AND M.State=? AND M.Subject LIKE ?".$o, $SenderGroup, $State, $Subject);
		elseif($Num AND $Subject)
			$r=j::ODQL($s.$w."AND M.Num LIKE ? AND M.Subject LIKE ?".$o, $SenderGroup, $Num, $Subject);
		//-------------------------11111111111111111111111111-------------------------------------
		elseif($ReceiverTopic)
			$r=j::ODQL($s.$w."AND E=?".$o, $SenderGroup, $ReceiverTopic);
		elseif ($State!='all')
			$r=j::ODQL($s.$w."AND M.State=?".$o, $SenderGroup, $State);
		elseif($Num)
			$r=j::ODQL($s.$w."AND M.Num LIKE ?".$o,$SenderGroup, $Num);
		elseif ($Subject)
			$r=j::ODQL($s.$w."AND M.State=?".$o,$SenderGroup, $Subject);
		else
		{
			echo $s.$w.$o; 
			$r=j::ODQL($s.$w.$o,$SenderGroup);
			ORM::Dump($r);
		}
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