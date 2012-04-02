<?php
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * this class is used to save constant and legal information about alarms
 * @Entity Table(name="ConfigEvent")
 * @Entity(repositoryClass="ConfigEventRepository")
 * */
class ConfigEvent extends Config
{
	/**
	 * Name of the progress and its Result
	* @Column(type="string", unique=true)
	* @var string
	*/
	protected $EventName;
	public function EventName()
	{
		return $this->EventName;
	}
	/**
	* persian name of the progress and its Result
	* @Column(type="string")
	* @var string
	*/
	protected $PersianName;
	public function PersianName()
	{
		return $this->PersianName;
	}
	/**
	*
	* @OneToMany(targetEntity="Alarm", mappedBy="MotherEvent")
	* @var Alarm
	*/
	protected $ChildAlarm;
	function ChildAlarm()
	{
		return $this->ChildAlarm;
	}
	/**
	* @ManyToMany(targetEntity="AlarmFree", mappedBy="Killer")
	*	@var Alarm
	* */
	protected $SlainAlarmFree;
	function SlainAlarmFree()
	{
		return $this->SlainAlarmFree;
	}
	function AddSlainAlarmFree( $Alarm)
	{
		$this->SlainAlarmFree[]=$Alarm;
	}
	/**
	*
	* @ManyToMany(targetEntity="ConfigAlarm", mappedBy="Maker")
	* @var ConfigAlarm
	*/
	protected $ChildConfigAlarm;
	function ChildConfigAlarm()
	{
		return $this->ChildConfigAlarm;
	}
	function AddChildConfigAlarm(ConfigAlarm $CA)
	{
		$this->ChildConfigAlarm[]=$CA;
	}
	/**
	* @ManyToMany(targetEntity="ConfigAlarm", mappedBy="Killer")
	*	@var ConfigAlarm
	* */
	protected $SlainConfigAlarm;
	function SlainConfigAlarm()
	{
		return $this->SlainConfigAlarm;
	}
	function AddSlainConfigAlarm(ConfigAlarm $Alarm)
	{
		$this->SlainConfigAlarm[]=$Alarm;
	}
	function __construct($Name, $PersianName,$DeleteAccess, $Comment)
	{
		parent::__construct($DeleteAccess,$Comment);
		
		$this->EventName=$Name;
		$this->PersianName=$PersianName;
	}
	
}


use \Doctrine\ORM\EntityRepository;
class ConfigEventRepository extends EntityRepository
{
	
	
	public function Add($Title, $PersianTitle, $DeleteAccess=true, $Comment=null)
	{
		if ($Title==null)
		{
			$Error="رکورد نمی تواند بدون نام باشد.";
			return $Error;
		}
		else
		{
			$r=new ConfigEvent($Title, $PersianTitle, $DeleteAccess, $Comment);
			ORM::Write($r);
			return true;
		}
	
	}
	public function GetAll()
	{
		$r=j::ODQL("SELECT E FROM ConfigEvent E");
		return $r;	
	}
	public function Delete(ConfigEvent $E)
	{
		if($E->DeleteAccess())
		{
			j::DQL("DELETE FROM ConfigEvent AS E WHERE E.ID=? ",$E->ID());
			return true;
		}
		else 
		{
			return "امکان حذف کردن وجود ندارد.";
		}
	}
}