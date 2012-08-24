<?php

use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity
 * @entity(repositoryClass="AlarmRepository")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="Type", type="string")
 * @DiscriminatorMap({"Base" = "Alarm",
 * 	"Free"="AlarmFree",
 * 	"Auto"="AlarmAuto"
 * 	})
 * */
abstract class Alarm
{
	/**
	 * @GeneratedValue @Id @Column(type="integer")
	 * @var integer
	 */
	protected $ID;
	function ID()
	{
		return $this->ID; 
	}
	
	/**
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $CreateTimestamp;
	function CreateTimestamp()
	{
		return $this->CreateTimestamp;
	}
	function SetCreateTimestamp($Time)
	{
		$this->CreateTimestamp=$Time;
	}
	function CreateTime()
	{
		$jc=new CalendarPlugin();
		return $jc->JalaliFromTimestamp($this->CreateTimestamp);
	}
	/**
	*
	* @ManyToOne(targetEntity="ReviewFile", inversedBy="Alarm")
	* @JoinColumn(name="FileID", referencedColumnName="ID")
	* @var ReviewFile
	*/
	protected $File;
	function File()
	{
		return $this->File;
	}
	/**
	 * 
	 * @ManyToOne(targetEntity="MyUser", inversedBy="ChildAlarm")
	 * @JoinColumn(name="MotherUserID", referencedColumnName="ID")
	 * @var MyUser
	 */
	protected $MotherUser;
	function MotherUser()
	{
		return $this->MotherUser;
	}
	
	/**
	* TODO: 
	* @ManyToOne(targetEntity="ConfigEvent", inversedBy="ChildAlarm")
	* @JoinColumn(name="MotherEventID", referencedColumnName="ID")
	* @var ConfigEvent
	*/
	protected $MotherEvent;
	public function MotherEvent()
	{
		return $this->MotherEvent;
	}
	function SetMotherEvent($MotherEvent)
	{
		$this->MotherEvent=$MotherEvent;
		$MotherEvent->ChildAlarm()->add($this);
	}
	function __construct($File, $MotherEvent=null)
	{
		$this->MotherUser=MyUser::CurrentUser();
		$this->CreateTimestamp=time();
		if($MotherEvent instanceof ConfigEvent)
			$this->SetMotherEvent($MotherEvent);
		if($File instanceof ReviewFile)
			$this->File=$File;
	}
 	abstract function Title();
 	abstract function Context();
 	abstract function MoratoriumToDays();
 	abstract function User();
 	abstract function Group();
 	abstract function Killer();
}


use \Doctrine\ORM\EntityRepository;
class AlarmRepository extends EntityRepository
{
	public function CurrentUserAlarms_Personal()
	{
		$now=time();
		$CurrentUser=MyUser::CurrentUser($offset=0, $limit=50);
		$Q1="SELECT A From AlarmFree A JOIN A.User AS U WHERE
				 	 U=? AND A.CreateTimestamp+A.Moratorium<=? Limit {$offset},{$limit}";
		$Q2="SELECT A From AlarmAuto A JOIN A.ConfigAlarm AS C JOIN C.User AS U 
			WHERE U=? AND A.CreateTimestamp+C.Moratorium<=? Limit {$offset},{$limit}";
		if($CurrentUser)
		{
			$r1=j::ODQL($Q1,$CurrentUser,$now);
			$r2=j::ODQL($Q2,$CurrentUser,$now);			
		}
		if(is_array($r1) AND is_array($r2))
			$r=array_merge($r1,$r2);
		else if(is_array($r1))
			$r=$r1;
		else 
			$r=$r2;
		return $r;
	}
	public function CurrentUserAlarms_Group($offset=0, $limit=50)
	{
		$now=time();
		$CurrentUser=MyUser::CurrentUser();
		$Q1="SELECT A From AlarmFree A JOIN A.Group AS G WHERE
					 	 G=? AND A.CreateTimestamp+A.Moratorium<=? Limit {$offset},{$limit}";
		$Q2="SELECT A From AlarmAuto A JOIN A.ConfigAlarm AS C JOIN C.Group AS G
				WHERE G=? AND A.CreateTimestamp+C.Moratorium<=? Limit {$offset},{$limit}";
		if($CurrentUser)
		{
			$r1=j::ODQL($Q1,$CurrentUser->Group(),$now);
			$r2=j::ODQL($Q2,$CurrentUser->Group(),$now);
		}
		if(is_array($r1) AND is_array($r2))
		$r=array_merge($r1,$r2);
		else if(is_array($r1))
		$r=$r1;
		else
		$r=$r2;
		return $r;
	}
	
}