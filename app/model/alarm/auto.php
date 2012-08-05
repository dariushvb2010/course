<?php

use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity 
 * @entity(repositoryClass="AlarmAutoRepository")
 * */
class AlarmAuto extends Alarm
{
	/**
	*
	* @ManyToOne(targetEntity="ConfigAlarm", inversedBy="AlarmAuto")
	* @JoinColumn(name="ConfigAlarmID", referencedColumnName="ID")
	* @var ConfigAlarm
	*/
	protected $ConfigAlarm;
	function ConfigAlarm()
	{
		return $this->ConfigAlarm;
	}
	function SetConfigAlarm(ConfigAlarm $ConfigAlarm)
	{
		$this->ConfigAlarm=$ConfigAlarm;
		$ConfigAlarm->AlarmAuto()->add($this);
	}
	function Title()
	{
		if($this->ConfigAlarm) return $this->ConfigAlarm->Title();
	}
	function Context()
	{
		if($this->ConfigAlarm) return $this->ConfigAlarm->Context();
	}
	function MoratoriumToDays()
	{
		if($this->ConfigAlarm) return $this->ConfigAlarm->MoratoriumToDays();
	}
	function User()
	{
		if($this->ConfigAlarm) return $this->ConfigAlarm->User();
	}
	function Group()
	{
		if($this->ConfigAlarm) return $this->ConfigAlarm->Group();
	}
	function Killer()
	{
		if($this->ConfigAlarm) return $this->ConfigAlarm->Killer();
	}
	function __construct(ReviewFile $File=null, $MotherEvent=null, ConfigAlarm $ConfigAlarm=null)
	{
		parent::__construct($File, $MotherEvent);
		if($ConfigAlarm)
			$this->SetConfigAlarm($ConfigAlarm);
	}
}


use \Doctrine\ORM\EntityRepository;
class AlarmAutoRepository extends EntityRepository
{	
	
	public function Add($File, $MotherEvent=null, $ConfigAlarm=null)
	{
		$Error=array();
		$User=MyUser::CurrentUser();
		//if the cotag of file
		if(is_numeric($File))
		{
			$Cotag=$File;
			$File=ORM::Query("ReviewFile")->GetRecentFile($Cotag);
		}
		if(!$File)
			$Error[]=v::Ecnf($Cotag);
		if(!$ConfigAlarm)
			$Error[]="ConfigAlarm not provided!";
		if($User==null)
			$Error[]="هیچ کاربری فعال نیست";
		
		if(count($Error))
			return $Error;
		$Alarm=new AlarmAuto($File, $MotherEvent, $ConfigAlarm);
		ORM::Persist($Alarm);
		return $Alarm;
	}
	function Delete($File, $ConfigAlarm)
	{
		$res=j::ODQL("SELECT A from AlarmAuto A  WHERE A.File=? AND A.ConfigAlarm=? ", $File, $ConfigAlarm);
		if($res)
		foreach ($res as $r)
		{
			ORM::Delete($r);
		}
		return $res;
	}
	public function CurrentUserAlarms_Personal()
	{
		$now=time();
		$CurrentUser=MyUser::CurrentUser();
		$Query="SELECT A From AlarmAuto A JOIN A.ConfigAlarm AS C JOIN C.User AS U 
			WHERE U=? AND A.CreateTimestamp+C.Moratorium<=?";
		if($CurrentUser)
		$r=j::ODQL($Query,$CurrentUser,$now);
		return $r;
	}
	public function CurrentUserAlarms_Group()
	{
		$now=time();
		$CurrentUser=MyUser::CurrentUser();
		$Query="SELECT A From AlarmAuto A JOIN A.ConfigAlarm C JOIN C.Group AS G 
			WHERE G=? AND A.CreateTimestamp+C.Moratorium<=?";
		if($CurrentUser && $CurrentUser->Group())
		$r=j::ODQL($Query,$CurrentUser->Group(),$now);
		return $r;
	}
	
	
}