<?php
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @TODO موقع نصب نصب در رجایی دقت شود rajaie => mygate
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
	const Type_mokatebat = 'mokatebat';
	const Type_archive = 'archive';
	const Type_raked = 'raked';
	const Type_iran = 'iran';
	const Type_other = 'other';
	const Type_othergates = 'othergates';
	
	/**
	* @Column(type="string")
	* @var string
	*/
	protected $Type;
	public function Type($persian=false)
	{
		return $this->Type;
	}
	 /**
    * @Column(type="integer", nullable="true")
    * @var integer
    */
    protected $GateCode;
    function GateCode()
    {
    	return $this->GateCode;
    }
    /**
     * @Column(type="boolean")
     * @var boolean
     */
    protected $DeleteAccess;
    function DeleteAccess()
    {
    	return $this->DeleteAccess;
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
	/**
	 *@OneToMany(targetEntity="ReviewProcessForward", mappedBy="Setad")
	 *@var ArrayCollection_of_ReviewProcessForward
	 */
	protected $ProcessForward;
	function ProcessForward(){ return $this->ProcessForward; }
	
	function __construct($topic=null,$comment='',$type='', $GateCode=GateCode, $DeleteAccess=true)
	{
		if($topic)
			$this->Topic=$topic;

		$this->Comment=$comment;
		$this->Type=$type;
		$this->GateCode = $GateCode;
		$this->DeleteAccess = $DeleteAccess;
		$this->MailReceive= new ArrayCollection();
		$this->MailSend= new ArrayCollection();
		$this->ProcessForward = new ArrayCollection();
	}
	
	static function TypeArray(){
		return $TYPES=array(
	 				"othergates"=>"گمرک های اجرایی",
	 				"mygate"=> "گمرک ".GateName,
	 				"iran"=>"گمرک ایران",
	 				"other"=>"سایر(ارسال بایگانی بازبینی)",
	 				"correspondent"=>"طرف مکاتبه",
					'mokatebat'=>'مکاتبات دفاتر ستادی'
	);		
	}
	static function GetPersianType($type)
	{
		$ar=self::TypeArray();
		return $ar[$type];
	}
	public static function Topics($type='*')
	{
		return ORM::Query(new ReviewTopic())->GetTopics($type);
	}
	/**
	 * returns an array convinient for making form  
	 * @param stirng $type
	 * @return array like: array([2]=>'kfjs', [24]=>'lkjdf')
	 */
	public static function TopicsArray($type='*'){
		$topics = ORM::Query('ReviewTopic')->GetTopics($type);
		foreach ($topics as $t){
			$ret[$t['ID']] = $t['Topic'];
		}
		return $ret;
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
	public static function Add($Subject,$Comment,$type='',$GateCode = GateCode, $DeleteAccess=true)
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
			$T=new ReviewTopic($Subject,$Comment,$type,$GateCode, $DeleteAccess);
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
			$r=j::DQL("SELECT T FROM ReviewTopic AS T order by T.Type");
		else
			$r=j::DQL("SELECT T FROM ReviewTopic AS T WHERE T.Type=? order by T.Type",$type);
		return $r;	
	}
	
	public function Delete(Topic $T)
	{
		j::DQL("DELETE FROM ReviewTopic AS C WHERE C.ID=? ",$T->ID());
	
	}
}