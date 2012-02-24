<?php
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity Table(name="ReviewMail")
 * @Entity(repositoryClass="ReviewMailRepository")
 * */
class ReviewMail
{
	 /**
     * @GeneratedValue @Id @Column(type="integer")
     * @var integer
     */
    public $ID;
	function ID()
	{
		return $this->ID;
	}
	/**
	* @Column(type="string")
	* @var string
	*/
	protected $Num;
	public function Num()
	{
		return $this->Num;
	}
	/**
	* @ManyToOne(targetEntity="ReviewTopic")
	* @JoinColumn(name="SenderID", referencedColumnName="ID")
	*/
	protected $Sender;
	public function Sender()
	{
		return $this->Sender;
	}
	/**
	* @ManyToOne(targetEntity="ReviewTopic")
	* @JoinColumn(name="ReceiverID", referencedColumnName="ID")
	*/
	protected $Receiver;
	public function Receiver()
	{
		return $this->Receiver;
	}
	/**
	* @Column(type="integer")
	* @var integer
	*/
	protected $State;
	function State()
	{
		return $this->State;
	}
	function SetState($state)
	{
		$this->State=$state;
	}
	/**
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $SendTimestamp;
	function SendTimestamp()
	{
		return $this->SendTimestamp;
	}
	function SetSendimestamp($Time)
	{
		$this->SendTimestamp=$Time;
	}
	function SendTime()
	{
		$jc=new CalendarPlugin();
		return $jc->JalaliFullTime($this->SendTimestamp);
	}
	/**
	*
	* @OneToMany(targetEntity="ReviewProgressPost", mappedBy="Mail")
	* @var ReviewProgressPost
	*/
	protected $Post;
	function Post()
	{
		return $this->Post;
	}
	/**
	* @Column(type="string")
	* @var string
	*/
	protected $Comment;
	public function Comment()
	{
		return $this->Comment;
	}
	
	
	function __construct(ReviewTopic $Sender=null,ReviewTopic $Receiver=null, $Num='', $State=0,$Comment='')
	{
		$this->Sender=$Sender;
		$this->Receiver=$Receiver;
		//if($Comment)
			$this->Comment=$Comment;
		if($Num)
			$this->Num=$Num;
		$this->State=$State;
		if($this->State==1)
			$this->SetSendimestamp(time());
		else 	
			$this->SendTimestamp=0;
		$this->Post= new ArrayCollection();
	}

}


use \Doctrine\ORM\EntityRepository;
class ReviewMailRepository extends EntityRepository
{
	
	
	public function GetMails(ReviewTopic $Sender,ReviewTopic $Receiver, $state)
	{
		$r =j::ODQL("SELECT M FROM ReviewMail AS M WHERE M.Sender=? AND M.Receiver=? AND M.State=?",$Sender,$Receiver,$state);
		return $r;
	}
	/**
	 * only for duplex mails 
	 */
	public function GetCountType(ReviewMail $mail,$type=0)
	{
		$r=j::DQL("SELECT COUNT(P) AS Result FROM ReviewProgressPost AS P  WHERE P.Mail=? AND P.IsSend=?",$mail,$type);
		return $r[0]['Result']*1;
	}
	
}