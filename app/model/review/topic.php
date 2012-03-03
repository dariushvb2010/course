<?php
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity Table(name="ReviewTopic")
 * @Entity(repositoryClass="ReviewTopicRepository")
 * */
class ReviewTopic
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
	protected $Topic;
	public function Topic()
	{
		return $this->Topic;
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
	
	/**
	* @Column(type="string")
	* @var string
	*/
	protected $Type;
	public function Type()
	{
		return $this->Type;
	}
	/**
	* @OneToMany(targetEntity="MailReceive", mappedBy="SenderTopic")
	* @var arrayCollectionOfMailReceive
	*/
	protected $MailReceive;
	function MailReceive(){ return $this->MailReceive; }
	/**
	* @OneToMany(targetEntity="MailSend", mappedBy="ReceiverTopic")
	* @var arrayCollectionOfMailSend
	*/
	protected $MailSend;
	function MailSend(){ return $this->MailSend; }
	
	function __construct($topic=null,$comment='',$type='')
	{
		if($topic)
			$this->Topic=$topic;

		$this->Comment=$comment;
		$this->Type=$type;
		$this->MailReceive= new ArrayCollection();
		$this->MailSend= new ArrayCollection();
	}
	
	public static function Topics($type='*')
	{
		return ORM::Query(new ReviewTopic())->GetTopics($type);
	}
	public static function Raked()
	{
		$r=ORM::Find(new ReviewTopic,"Type","raked");
		return $r[0];
	}
	public static function Archive()
	{
		$r=ORM::Find(new ReviewTopic,"Type","archive");
		return $r[0];
	}
	/**
	*
	* Enter description here ...
	* @param string
	* @return string on error object on sucsess
	*/
	public static function Add($Subject,$Comment,$type='')
	{
		if ($Subject==null)
		{
			$Error="لطفا یک عنوان انتخاب نمایید.";
			return $Error;
		}
		elseif ($type==null)
		{
			$Error="لطفا یک نوع را انتخاب کنید.";
			return $Error;
		}
		else
		{
			if($Comment==null)
				$Comment="";
			$T=new ReviewTopic($Subject,$Comment,$type);
			ORM::Persist($T);
			return true;
		}
	
	}
}


use \Doctrine\ORM\EntityRepository;
class ReviewTopicRepository extends EntityRepository
{
	public function GetTopics($type='*')
	{
		if($type=='*')
			$r=j::DQL("SELECT T FROM ReviewTopic AS T");
		else
			$r=j::DQL("SELECT T FROM ReviewTopic AS T WHERE T.Type=?",$type);
		return $r;	
	}
	
	public function Delete(Topic $T)
	{
		j::DQL("DELETE FROM ReviewTopic AS C WHERE C.ID=? ",$T->ID());
	
	}
}