<?php
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * this class is used to save constant and legal information about alarms
 * @Entity Table(name="MyGroup")
 * @Entity(repositoryClass="MyGroupRepository")
 * */
class MyGroup
{
	
	/**
	* @GeneratedValue
	*  @Id @Column(type="integer")
	* @var integer
	*/
	protected $ID;
	function ID()
	{
		return $this->ID;
	}
	/**
	 * @Column(type="string", unique=true)
	 * @var string
	 */
	protected $Title;
	function Title()
	{
		return $this->Title;
	}
	
	/**
	* @Column(type="string", nullable=true)
	* @var string
	*/
	protected $PersianTitle;
	function PersianTitle()
	{
		return $this->PersianTitle;
	}
	/**
	 * Reliable Persian Title, if PersianTitle==null return Title
	 */
	function RTitle()
	{
		return $this->PersianTitle ? $this->PersianTitle : $this->Title;
	}
	/**
	* @Column(type="string", nullable=true)
	* @var string
	*/
	protected $Description;
	function Description()
	{
		return $this->Description;
	}
	/**
	 * @OneToMany(targetEntity="MyUser", mappedBy="Group")
     * @var arrayCollectionOfMyUser
     */
    protected $User;
    function User()
    {
        return $this->User;
    }
	/**
	*
	* @ManyToMany(targetEntity="AlarmFree", mappedBy="Group")
	* @var AlarmFree
	*/
	protected $AlarmFree;
	function AlarmFree()
	{
		return $this->AlarmFree;
	}
	public function AddAlarmFree($AlarmFree)
	{
		$this->AlarmFree[]=$AlarmFree;
	}
	/**
	*
	* @ManyToMany(targetEntity="ConfigAlarm", mappedBy="Group")
	* @var ConfigAlarm
	*/
	protected $ConfigAlarm;
	function ConfigAlarm()
	{
		return $this->ConfigAlarm;
	}
	public function AddConfigAlarm(ConfigAlarm $ConfigAlarm)
	{
		$this->ConfigAlarm[]=$ConfigAlarm;
	}
	/**
	 * Mails given by this group
	* @OneToMany(targetEntity="MailGive", mappedBy="GiverGroup")
	* @var arrayCollectionOfMailGive
	*/
	protected $MailGive;
	function MailGive(){ return $this->MailGive;}
	/**
	 * Mails got by this group
	* @OneToMany(targetEntity="MailGive", mappedBy="GetterGroup")
	* @var arrayCollectionOfMailGive
	*/
	protected $MailGet;
	function MailGet(){ return $this->MailGet;}
	/**
	* @OneToMany(targetEntity="MailReceive", mappedBy="ReceiverGroup")
	* @var arrayCollectionOfMailReceive
	*/
	protected $MailReceive;
	function MailReceive(){ return $this->MailReceive; }
	/**
	* @OneToMany(targetEntity="MailSend", mappedBy="SenderGroup")
	* @var arrayCollectionOfMailSend
	*/
	protected $MailSend;
	function MailSend(){ return $this->MailSend; }
	
	function __construct($Title=null, $PersianTitle=null, $Description=null)
	{
			$this->Title=$Title;
			$this->PersianTitle=$PersianTitle;
			$this->Description=$Description;
			$this->User=new ArrayCollection();
			$this->MailReceive=new ArrayCollection();
			$this->MailSend=new ArrayCollection();
			$this->MailGive=new ArrayCollection();
			$this->MailGet= new ArrayCollection();
	}
	
}


use \Doctrine\ORM\EntityRepository;
class MyGroupRepository extends EntityRepository
{
	
	
	public static function Add($Title, $PersianTitle=null, $Description=null)
	{
		if ($Title==null)
		{
			$Error="رکورد نمی تواند بدون نام باشد.";
			return $Error;
		}
		else
		{
			$F=ORM::Find1("MyGroup", "Title",$Title);
			if($F)
			{
				$Error="این نام قبلا ثبت شده است.";
				return $Error;
			}
			$G=new MyGroup($Title, $PersianTitle, $Description);
			ORM::Dump($G);
			ORM::Write($G);
			return $G;
		}
	
	}
	/**
	 * get the roles from rbac_role table
	 */
	public function Refresh()
	{
		
	}
	public function GetAll()
	{
		$r=j::ODQL("SELECT G FROM MyGroup G");
		return $r;	
	}
	
	public function Delete(ConfigAlarm $A)
	{
		if($C->DeleteAccess())
		{
			j::DQL("DELETE FROM ConfigAlarm AS C WHERE C.ID=? ",$A->ID());
			return true;
		}
		else 
		{
			return "امکان حذف کردن وجود ندارد.";
		}
	}
}