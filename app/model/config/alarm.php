<?php
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * this class is used to save constant and legal information about alarms
 * @Entity
 * @Entity(repositoryClass="ConfigAlarmRepository")
 * */
class ConfigAlarm extends Config
{
	
	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $Title;
	function Title()
	{
		return $this->Title;
	}
	function SetTitle($Title)
	{
		$this->Title=$Title;
	}
	/**
	 * Reliable Title: if Title==null returns Style
	 */
	function RTitle()
	{
		return ($this->Title ? $this->Title : ConfigData::$CONFIG_STYLE[$this->Style]);
	}
	/**
	* متن هشدار
	* @Column(type="string", nullable=true)
	* @var string
	*/
	protected $Context;
	function Context()
	{
		return $this->Context;
	}
	function SetContext($Context)
	{
		$this->Context=$Context;
	}
	/**
	* Number of seconds in Moratorium time
	* مهلت فانونی 
	* @Column(type="integer")
	* @var integer
	*/
	protected $Moratorium;
	public function Moratorium()
	{
		return $this->Moratorium;
	}
	function SetMoratorium($Moratorium)
	{
		$this->Moratorium=$Moratorium;
	}
	function SetMoratoriumInDays($Moratorium)
	{
		$this->Moratorium=$Moratorium*TIMESTAMP_DAY;
	}
	public function MoratoriumToDays()
	{
		return $this->Moratorium/TIMESTAMP_DAY;
	}
	/**
	*
	* @OneToMany(targetEntity="AlarmAuto", mappedBy="ConfigAlarm")
	* @var Alarm
	*/
	protected $AlarmAuto;
	function AlarmAuto()
	{
		return $this->AlarmAuto;
	}
	/**
	* @ManyToMany(targetEntity="MyUser", inversedBy="ConfigAlarm")
	* @JoinTable(name="app_ConfigAlarm_User",
	* 		joinColumns={@JoinColumn(name="ConfigAlarmID", referencedColumnName="ID")},
	*      inverseJoinColumns={@JoinColumn(name="UserID", referencedColumnName="ID")})
	* array of users Alarmed with this Alarm
	* @var ArrayCollectionOfMyUser
	*/
	protected $User;
	function User()
	{
		return $this->User;
	}
	function AddUser($User)
	{
		if(!$User)
			return;
		if(!$this->User->contains($User) AND !$this->Group->contains($User->Group()))
		{
			$User->AddConfigAlarm($this);
			$this->User[]=$User;
		}
	}
	/**
	* @ManyToMany(targetEntity="MyGroup", inversedBy="ConfigAlarm")
	* @JoinTable(name="app_ConfigAlarm_Group",
	* 		joinColumns={@JoinColumn(name="ConfigAlarmID", referencedColumnName="ID")},
	*      inverseJoinColumns={@JoinColumn(name="GroupID", referencedColumnName="ID")})
	*
	* @var ArrayCollectionOfMyGroup
	*/
	protected $Group;
	function Group()
	{
		return $this->Group;
	}
	function AddGroup($Group)
	{
		if(!$this->Group->contains($Group))
		{
			$Group->AddConfigAlarm($this);
			$this->Group[]=$Group;
		}
	}
	/**
	* @ManyToMany(targetEntity="ConfigEvent", inversedBy="ChildConfigAlarm")
	* @JoinTable(name="app_ConfigAlarm_MakerEvent",
	* 		joinColumns={@JoinColumn(name="ConfigAlarmID", referencedColumnName="ID")},
	*      inverseJoinColumns={@JoinColumn(name="MakerID", referencedColumnName="ID")})
	*
	* @var ArrayCollectionOfConfigEvent
	*/
	protected $Maker;
	public function Maker()
	{
		return $this->Maker;
	}
	function AddMaker($Maker)
	{
		$Maker->AddChildConfigAlarm($this);
		$this->Maker[]=$Maker;
	}
	/**
	* @ManyToMany(targetEntity="ConfigEvent", inversedBy="SlainConfigAlarm")
	* @JoinTable(name="app_ConfigAlarm_KillerEvent",
	* 		joinColumns={@JoinColumn(name="ConfigAlarmID", referencedColumnName="ID")},
	*      inverseJoinColumns={@JoinColumn(name="KillerID", referencedColumnName="ID")})
	*
	* @var ArrayCollectionOfConfigEvent
	*/
	protected $Killer;
	public function Killer()
	{
		return $this->Killer;
	}
	function AddKiller($Killer)
	{
		$Killer->AddSlainConfigAlarm($this);
		$this->Killer[]=$Killer;
	}
	/**
	 * 
	 * @param string $Title
	 * @param string $Context
	 * @param string $Moratorium
	 * @param boolean $DeleteAccess
	 * @param string $Comment
	 * @param string $Style recognize type of ConfigAlarm
	 */
	function __construct($Title=null, $Context=null, $Moratorium=null, $DeleteAccess=false, $Comment=null, $Style=null)
	{
		parent::__construct($DeleteAccess, $Comment,$Style);
			$this->Title=$Title;
			$this->Context=$Context;
			if($Moratorium)
				$this->SetMoratoriumInDays($Moratorium);
			else 
				$this->Moratorium=0;
			$this->AlarmAuto= new ArrayCollection();
			$this->User=new ArrayCollection();
			$this->Group=new ArrayCollection();
			$this->Maker=new ArrayCollection();
			$this->Killer=new ArrayCollection();
			
	}
	
}


use \Doctrine\ORM\EntityRepository;
class ConfigAlarmRepository extends EntityRepository
{
	public function Add($Title=null, $Context=null, $Moratorium=null,
		 $Users=null, $Groups=null, $Makers=null, $Killers=null, $DeleteAccess=false, $Comment=null,$Style=null)
	{

			$CA=new ConfigAlarm($Title, $Context, $Moratorium, $DeleteAccess, $Comment, $Style);
			$Groups=ORM::Query("AlarmFree")->AddGroups($Groups,$CA,$Error);
			ORM::Query("AlarmFree")->AddUsers($Users,$Groups,$CA,$Error);
			ORM::Query("AlarmFree")->AddKillers($Killers,$CA);
			$this->AddMakers($Makers,$CA);
			
			if(ORM::Query("AlarmFree")->Validation($CA))
			ORM::Persist($CA);
			else
			$Error[]="حداقل يک گروه کاربري يا يک کاربر بايد انتخاب شود.";
			
			if(count($Error))
				return $Error;
			else
				return $CA;
	
	}
	private function AddMakers($Makers, $CAlarm)
	{
		if(count($Makers))
		{
			if(is_numeric($Makers[0]))
			{
				foreach($Makers as $Maker)
				{
					$K=ORM::Find("ConfigEvent",$Maker);
					if($K)
					$NewMakers[]=$K;
				}
				$Makers=$NewMakers;
			}
				
			if($Makers && $Makers[0] instanceof ConfigEvent)
			foreach($Makers as $Maker)
			{
				$CAlarm->AddMaker($Maker);
			}
		}
	}
	public function GetAll()
	{
		$r=j::ODQL("SELECT C FROM ConfigAlarm C");
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