<?php

use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity Table(name="AlarmFree")
 * @entity(repositoryClass="AlarmFreeRepository")
 * */
class AlarmFree extends Alarm
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
	function setContext($value){
		$this->Context=$value;
	}
	/**
	* Number of seconds in Moratorium time
	* مهلت فانوني
	* @Column(type="integer")
	* @var integer
	*/
	protected $Moratorium;
	function Moratorium()
	{
		return $this->Moratorium;
	}
	public function MoratoriumToDays()
	{
		return $this->Moratorium/TIMESTAMP_DAY;
	}
	
	/**
	 * @ManyToMany(targetEntity="MyUser", inversedBy="AlarmFree")
 	 * @JoinTable(name="app_AlarmFree_User",
 	 * 		joinColumns={@JoinColumn(name="AlarmFreeID", referencedColumnName="ID")},
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
			$User->AddAlarmFree($this);
			$this->User[]=$User;
		}
	}
	/**
	* @ManyToMany(targetEntity="MyGroup", inversedBy="AlarmFree")
	* @JoinTable(name="app_AlarmFree_Group",
	* 		joinColumns={@JoinColumn(name="AlarmFreeID", referencedColumnName="ID")},
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
			$Group->AddAlarmFree($this);
			$this->Group[]=$Group;
		}
	}
	
	
	
	/**
	* @ManyToMany(targetEntity="ConfigEvent", inversedBy="SlainAlarmFree")
	* @JoinTable(name="app_AlarmFree_KillerEvent",
	* 		joinColumns={@JoinColumn(name="AlarmFreeID", referencedColumnName="ID")},
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
		$Killer->AddSlainAlarmFree($this);
		$this->Killer[]=$Killer;
	}
	function __construct($File, $Title, $Context=null, $Moratorium=null)
	{
		parent::__construct($File);
		$this->Title=$Title;
		$this->Context=$Context;
		if(is_numeric($Moratorium))
			$this->Moratorium=$Moratorium*24*3600;
		else
			$this->Moratorium=0;
		$this->User=new ArrayCollection();
		$this->Group=new ArrayCollection();
		$this->Killer=new ArrayCollection();
		
		if($Moratorium)
			$this->Moratorium=$Moratorium;
		else 
			$this->Moratorium=0;
	}
}


use \Doctrine\ORM\EntityRepository;
class AlarmFreeRepository extends EntityRepository
{

	/**
	 * 
	 * Enter description here ...
	 * @param ReviewFile_or_integer $File
	 * @param string $Title
	 * @param string $Comment
	 * @param integer $Moratorium
	 * @param unknown_type $ConfigAlarm
	 * @param array_of_(integer_or_ConfigEvent) $Killers
	 * @param array_of_(integer_or_MyUser) $Users
	 */
	public function Add($File, $Title, $Context=null, $Moratorium=null, $Users=null, $Groups=null, $Killers=null)
	{
		$Error=array();
		//if the cotag of file
		
		if(is_numeric($File))
		{
			$Cotag=$File;
			$File=b::GetFile($Cotag);
			if($File==null)
			{
				$Error[]=v::Ecnf($Cotag);
				return $Error;
			}
			
		}
		
		$User=MyUser::CurrentUser();
		if($User==null)
			$Error[]="هيچ کاربري فعال نيست";
		$AF=new AlarmFree($File, $Title, $Context,$Moratorium);
		$MyGroups=$this->AddGroups($Groups, $AF,$Error);
		$this->AddUsers($Users,$MyGroups,$AF,$Error);
		$this->AddKillers($Killers, $AF);
		if($this->Validation($AF))
			ORM::Persist($AF);
		else 
			$Error[]="حداقل يک گروه کاربري يا يک کاربر بايد انتخاب شود.";
		//ORM::Flush();
		if(count($Error))
			return $Error;
		else 
			return $AF;
	}
	
	public function Validation($AF)
	{
		$R=false;
		$R|=(count($AF->Group()));
		$R|=(count($AF->User()));
		return $R;
	}
	public function AddGroups($Groups, $Alarm, $Error)
	{
		if(count($Groups))
		{
			if(is_numeric($Groups[0]) )
			{
				foreach ($Groups as $GroupID)
				{
					$Group=ORM::Find("MyGroup", $GroupID);
					if($Group)
					$NewGroups[]=$Group;
				}
				$Groups=$NewGroups;
			}
			if($Groups && $Groups[0] instanceof MyGroup)
			foreach ($Groups as $Group)
			{
				if(j::Check("AlarmTo".$Group->Title()))
					$Alarm->AddGroup($Group);
				else
					$Error[]="شما اجازه ايجاد هشدار براي گروه ".$Group->PersianTitle()."نداريد."; 
				
			}
		}
		return $Groups;
	}
	public function AddUsers($Users,$Groups, $Alarm, $Error)
	{
		if(count($Users))
		{
			if(is_numeric($Users[0]))
			{
				foreach ($Users as $UserID)
				{
					$User=ORM::Find("MyUser", $UserID);
					if($User)
					$NewUsers[]=$User;
				}
				$Users=$NewUsers;
			}
			if($Users && $Users[0] instanceof MyUser)
			foreach ($Users as $User)
			{
				$G=$User->Group();
				if(j::Check("AlarmTo".$G->Title()))
				{
					$add=true;
					if($Groups)
					foreach($Groups as $Group)
					{
						if($Group===$User->Group())
						{
							$add=false;
							break;
						}
					}
					if($add)
						$Alarm->AddUser($User);
				}
				else 
					$Error[]="شما اجازه ايجاد هشدار براي گروه ".$G->PersianTitle()."نداريد.";
			}
		}
	}
	public function AddKillers($KillerEvents, $Alarm)
	{
		$Killers=$KillerEvents;
		if(count($Killers))
		{
			if(is_numeric($Killers[0]))
			{
				foreach($Killers as $Killer)
				{
					$K=ORM::Find("ConfigEvent",$Killer);
					if($K)
					$NewKillers[]=$K;
				}
				$Killers=$NewKillers;
			}
			
			if($Killers && $Killers[0] instanceof ConfigEvent)
			foreach($Killers as $Killer)
			{
				$Alarm->AddKiller($Killer);
			}
		}
	}
	public function CurrentUserAlarms_Personal()
	{
		$now=time();
		$CurrentUser=MyUser::CurrentUser();
		$Query="SELECT A From AlarmFree A JOIN A.User AS U WHERE
			 	 U=? AND A.CreateTimestamp+A.Moratorium<=?";
		if($CurrentUser)
			$r=j::ODQL($Query,$CurrentUser,$now);
		return $r;
	}
	public function CurrentUserAlarms_Group()
	{
		$now=time();
		$CurrentUser=MyUser::CurrentUser();
		if($CurrentUser && $CurrentUser->Group())
		$r=j::ODQL("SELECT A From AlarmFree A JOIN A.Group AS G WHERE G=? AND A.CreateTimestamp+A.Moratorium<=?",$CurrentUser->Group(),$now);
		return $r;
	}
	
	
}