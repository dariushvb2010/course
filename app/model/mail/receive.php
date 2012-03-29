<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * 
 * when Archive group or Raked group receive some mail with 1000 file in, this type of mail is used 
 * ReceiverGroup='Archive' or 'Raked'
 * @author dariush jafari
 * @Entity(repositoryClass="MailReceiveRepository")
 * 
 */
class MailReceive extends Mail
{
	/**
	* @ManyToOne(targetEntity="MyGroup", inversedBy="MailReceive")
	* @JoinColumn(name="ReceiverGroupID",referencedColumnName="ID")
	* @var MyGroup
	*/
	protected $ReceiverGroup;
	function ReceiverGroup(){return $this->ReceiverGroup;}
	function SetReceiverGroup($s){$this->ReceiverGroup=$s;}
	function AssignReceiverGroup(MyGroup $ReceiverGroup)
	{
		$this->ReceiverGroup=$ReceiverGroup;
		$ReceiverGroup->MailReceive()->add($this);
	}
	
	/**
	* @ManyToOne(targetEntity="ReviewTopic", inversedBy="MailReceive")
	* @JoinColumn(name="SenderTopicID",referencedColumnName="ID")
	* @var ReviewTopic
	*/
	protected $SenderTopic;
	function SenderTopic(){
		return $this->SenderTopic;
	}
	function SetSenderTopic($r){
		$this->SenderTopic=$r;
	}
	function AssignSenderTopic(ReviewTopic $SenderTopic)
	{
		$this->SenderTopic=$SenderTopic;
		$SenderTopic->MailReceive()->add($this);
	}
	
	/**
	* @OneToMany(targetEntity="ReviewProgressReceive", mappedBy="MailReceive")
	* @var arrayCollectionOfReviewProgressReceive
	*/
	protected $ProgressReceive;
	function ProgressReceive(){ return $this->ProgressReceive; }
	function MyBox()
	{
		if($this->State()==self::STATE_CLOSED)
			return $this->ProgressReceive;
	}
	function __construct($Num=null, $Subject=null, $SenderTopic=null, $ReceiverGroup=null, $Description=null)
	{
		parent::__construct($Num, $Subject, $Description);
		if($ReceiverGroup) $this->AssignSenderTopic($SenderTopic);
		if( $SenderTopic) $this->AssignReceiverGroup($ReceiverGroup);
		$this->ProgressReceive=new ArrayCollection();
	}
}
use \Doctrine\ORM\EntityRepository;
class MailReceiveRepository extends EntityRepository
{
	function Add($Num, $Subject, MyGroup $ReceiverGroup, ReviewTopic $SenderTopic, $Description)
	{
		if(is_string($ReceiverGroup))
		{
			$ReceiverGroup=ORM::Find1("MyGroup","Title",$ReceiverGroup);
			if(!$ReceiverGroup)
			return "نام بخش تحویل دهنده به درستی وارد نشده است.";
		}
		if(!($ReceiverGroup instanceof MyGroup))
		return "بخش تحویل دهنده یافت نشد.";
		if(is_numeric($SenderTopic))
		{
			$SenderTopic=ORM::Find1("ReviewTopic", $ReviewTopic);
			if(!$SenderTopic)
			return "نام بخش تحویل گیرنده به درستی وارد نشده است.";
		}
		if(!($SenderTopic instanceof ReviewTopic))
		return "بخش تحویل گیرنده یافت نشد.";
	
		$r=new MailReceive($Num, $Subject, $SenderTopic, $ReceiverGroup, $Description);
		ORM::Persist($r);
		return $r;
	}
	function GetAll($ReceiverGroup='all', $SenderTopic='all', $State='all')
	{
		$s=" SELECT M FROM MailReceive M JOIN M.SenderTopic I JOIN M.ReceiverGroup E ";
		$w=" WHERE ";
		$o=" ORDER BY M.RetouchTimestamp DESC,M.ID DESC";
		if($SenderTopic!='all' AND $ReceiverGroup!='all' AND $State!='all')
			$r=j::ODQL($s.$w."I=? AND E=? AND M.State=?".$o, $SenderTopic, $ReceiverGroup, $State);
		elseif($SenderTopic!='all' AND $ReceiverGroup!='all')
			$r=j::ODQL($s.$w."I=? AND E=?".$o, $SenderTopic, $ReceiverGroup);
		elseif($SenderTopic!='all' AND $State!='all')
			$r=j::ODQL($s.$w."I=? AND M.State=?".$o, $SenderTopic, $State);
		elseif ($ReceiverGroup!='all' AND $State!='all')
			$r=j::ODQL($s.$w."E=? AND M.State=?".$o, $ReceiverGroup, $State);
		elseif ($SenderTopic!='all')
			$r=j::ODQL($s.$w."I=?".$o, $SenderTopic);
		elseif($ReceiverGroup!='all')
			$r=j::ODQL($s.$w."E=?".$o,$ReceiverGroup);
		elseif ($State!='all')
			$r=j::ODQL($s.$w."M.State=?".$o,$State);
		else
			$r=j::ODQL($s.$o);
		return $r;
	}
	function Search(MyGroup $ReceiverGroup,ReviewTopic $SenderTopic=null, $State='all', $Num=null, $Subject=null)
	{
		if($Num)
		$Num="%".$Num."%";
		if($Subject)
		$Subject="%".$Subject."%";
		$s=" SELECT M FROM MailReceive AS M JOIN M.ReceiverGroup I JOIN M.SenderTopic E ";
		$w=" WHERE I=? ";
		$o=" ORDER BY M.RetouchTimestamp DESC,M.ID DESC";
		if($Num AND $Subject AND $State!='all' AND $SenderTopic)
			$r=j::ODQL($s.$w."AND E=? AND M.State=? AND M.Num LIKE ? AND M.Subject LIKE ?".$o, $ReceiverGroup, $SenderTopic, $State, $Num, $Subject);
		//---------------33333333333333333--------------------------------------------
		elseif($SenderTopic AND $State!='all' AND $Num)
			$r=j::ODQL($s.$w."AND E=? AND M.State=? AND M.Num LIKE ?".$o, $ReceiverGroup, $SenderTopic, $State, $Num);
		elseif($SenderTopic AND $State!='all' AND $Subject)
			$r=j::ODQL($s.$w."AND E=? AND M.State=? AND M.Subject LIKE ?".$o, $ReceiverGroup, $SenderTopic, $State, $Subject);
		elseif($SenderTopic AND $Num AND $Subject)
			$r=j::ODQL($s.$w."AND E=? AND M.Num LIKE ? AND M.Subject LIKE ?".$o, $ReceiverGroup, $SenderTopic, $Num, $Subject);
		elseif($Num AND $Subject AND $State!='all')
			$r=j::ODQL($s.$w."AND M.State=? AND M.Num LIKE ? AND M.Subject LIKE ?".$o, $ReceiverGroup, $State, $Num, $Subject);
		//------------------------222222222222222222222-------------------------------------
	
		elseif($SenderTopic AND $State!='all')
			$r=j::ODQL($s.$w."AND E=? AND M.State=?".$o, $ReceiverGroup, $SenderTopic, $State);
		elseif($SenderTopic AND $Num)
			$r=j::ODQL($s.$w."AND E=? AND M.Num LIKE ?".$o, $ReceiverGroup, $SenderTopic, $Num);
		elseif($SenderTopic AND $Subject)
			$r=j::ODQL($s.$w."AND E=? AND M.Subject LIKE ?".$o, $ReceiverGroup, $SenderTopic, $Subject);
		elseif($State!='all' AND $Num)
			$r=j::ODQL($s.$w."AND M.State=? AND M.Num LIKE ?".$o, $ReceiverGroup, $State, $Num);
		elseif($State!='all' AND $Subject)
			$r=j::ODQL($s.$w."AND M.State=? AND M.Subject LIKE ?".$o, $ReceiverGroup, $State, $Subject);
		elseif($Num AND $Subject)
			$r=j::ODQL($s.$w."AND M.Num LIKE ? AND M.Subject LIKE ?".$o, $ReceiverGroup, $Num, $Subject);
		//-------------------------11111111111111111111111111-------------------------------------
		elseif($SenderTopic)
			$r=j::ODQL($s.$w."AND E=?".$o, $ReceiverGroup, $SenderTopic);
		elseif ($State!='all')
			$r=j::ODQL($s.$w."AND M.State=?".$o, $ReceiverGroup, $State);
		elseif($Num)
			$r=j::ODQL($s.$w."AND M.Num LIKE ?".$o,$ReceiverGroup, $Num);
		elseif ($Subject)
			$r=j::ODQL($s.$w."AND M.State=?".$o,$ReceiverGroup, $Subject);
		else
		{
			echo $s.$w.$o;
			$r=j::ODQL($s.$w.$o,$ReceiverGroup);
			ORM::Dump($r);
		}
		return $r;
	}
	function LastMail(MyGroup $ReceiverGroup)
	{
		$r=j::ODQL("SELECT M FROM MailReceive AS M JOIN M.ReceiverGroup D 
							WHERE D=? 
					 		ORDER BY M.RetouchTimestamp DESC,M.ID DESC LIMIT 1", $ReceiverGroup);
		ORM::Dump($r);
		if ($r)
		return $r[0];
		else
		return null;
	}
}