<?php
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity 
 * @entity(repositoryClass="MyUserRepository")
 * Application Specific User
 * @author dariush_jafari
 *
 */
class MyUser extends Xuser
{
	function ID(){
		return $this->ID;
	}
	/**
	* @Column(type="string")
	* @var string
	*/
	protected $Firstname;
	public function Firstname()
	{
		$r=$this->Firstname;
		return (mb_strlen($r,'utf-8')>15?mb_substr($r, 0,12,'utf-8').'...':mb_substr($r, 0,15,'utf-8'));
	}
	/**
	* @Column(type="string")
	* @var string
	*/
	protected $Lastname;
	public function Lastname()
	{
		
		$r=$this->Lastname;
		return (mb_strlen($r,'utf-8')>15?mb_substr($r, 0,12,'utf-8').'...':mb_substr($r, 0,15,'utf-8'));
	}
	
	/**
	* @Column(type="boolean")
	* @var boolean
	* 0= male
	* 1=female
	*/
	protected $gender;
	public function Gender()
	{
		if($this->gender){
			return 'خانم';
		}else{
			return 'آقای';
		}
	}
	public function SetGender($iswoman)
	{
		$this->gender=$iswoman;
	}
	
	const STATE_VACATION=0;
	const STATE_WORK=1;
	const STATE_RETIRED=2;
	/**
	* @Column(type="integer")
	* @var integer
	* 0: Vacation: morakhasi
	* 1: Work
	* 2: Retired: بازنشست شده
	*/
	protected $State;
	public function State()
	{
		$Real=array(0=>"Vacation",1=>"Work",2=>"Retired");
		return $Real[$this->State];
	}
	
	/**
	 * 
	 * returns the state as a number
	 * @return number
	 */
	public function Enabled()
	{
		return $this->State;
	}
	/**
	 * 
	 * set the state of the reviewer
	 * @param string or integer $State
	 */
	public function SetState($State)
	{
		$Numeric=array("Vacation"=>0,"Work"=>1,"Retired"=>2);
		if($this->State()!="Retired")
		{
			if(is_string($State))
				$this->State=$Numeric[$State];
			else 
				$this->State=$State;
		}
	}
	public function Enable()
	{
		$this->State=1;
	}
	public function Disable()
	{
		$this->State=2;
	}
	/**
	 * @ManyToOne(targetEntity="MyGroup", inversedBy="User")
	 * @JoinColumn(name="Group1ID",referencedColumnName="ID")
	 * @var MyGroup
	 */
	protected $Group;
	function Group()
	{
		return $this->Group;
	}
	function SetGroup($Group)
	{
		$this->Group=$Group;
		$Group->User()->add($this);
	}
	function GroupRTitle()
	{
		if($this->Group)
			return $this->Group->RTitle();
	}
	/**
	* @Column(type="boolean")
	* @var boolean
	*/
	protected $isReviewer;
	public function isReviewer()
	{
		return $this->isReviewer;
	}
	/**
	 * @OneToMany(targetEntity="ReviewProgress", mappedBy="User")
	 * @var ArrayCollection
	 */
	protected $Progress;
	public function Progress()
	{
		return $this->Progress;
	}
	/**
	 * 
	 * @ManyToMany(targetEntity="AlarmFree", mappedBy="User")
	 * All of the Alarms related to this user
	 * @var ArrayCollection
	 */
	protected $AlarmFree;
	public function AlarmFree()
	{
		return $this->AlarmFree;
	}
	public function AddAlarmFree($AlarmFree)
	{
		$this->AlarmFree[]=$AlarmFree;
	}
	/**
	*
	* @ManyToMany(targetEntity="ConfigAlarm", mappedBy="User")
	* All of the Alarms related to this user
	* @var ArrayCollection
	*/
	protected $ConfigAlarm;
	public function ConfigAlarm()
	{
		return $this->ConfigAlarm;
	}
	public function AddConfigAlarm(ConfigAlarm $ConfigAlarm)
	{
		$this->ConfigAlarm[]=$ConfigAlarm;
	}
	/**
	 * 
	 * @OneToMany(targetEntity="Alarm", mappedBy="MotherUser")
	 * @var ArrayCollectionOfAlarm
	 */
	protected $ChildAlarm;
	public function ChildAlarm()
	{
		return $this->ChildAlarm;
	}
	/**
	*
	* @OneToMany(targetEntity="MySetting", mappedBy="User")
	* @var MySetting
	* this is an array of settings of the user, every user has some settings 
	*/
	protected $Setting;
	function Setting()
	{
		return $this->Setting;
	}
	function MainSetting()
	{
		if($this->Setting)
			return $this->Setting[0];
		else 
			return null;
	}
	public function LegalGroups()
	{
		$Groups=ORM::Query("MyGroup")->GetAll();
		if($Groups)
		foreach($Groups as $G)
		{
			$t=$G->Title();
			$per="AlarmTo".$t;
			if(j::Check($per,$this->ID))
			{
				$res[]=$G;
			}
		}
		return $res;
	}
	/**
	*
	* @param array_of_MyGroup $Groups
	* @return all of users in Group param
	*/
	public static function UsersOfGroups($Groups)
	{
		if($Groups)
		foreach($Groups as $G)
		{
			$t=$G->Title();
			$per="AlarmTo".$t;
			if(j::Check($per))
			{
				$Users=$G->User();
				if($Users)
				foreach ($Users as $User)
				$res[]=$User;
			}
		}
		return $res;
	}
	public function __construct($Username=null,$Password=null,$Gender=0,$Firstname="",$Lastname="",$isReviewer=false,$Email="",$Group=null)
	{
		$this->Progress=new ArrayCollection();
		if ($Username)
		{
			parent::__construct($Username,$Password,$Email);
			$this->Firstname=$Firstname;
			$this->gender=$Gender;
			$this->Lastname=$Lastname;
			$this->SetGroup($Group);
			$this->isReviewer=$isReviewer;
			$this->State=1;
			$this->Setting= new ArrayCollection();
		}
	}	
	public static $PersianRoles=array(
		"root"=>"مدیر اصلی",
		"Review"=>"بازبینی",
		"Review_CotagBook"=>"دفتر کوتاژ",
		"Review_Raked"=>"بایگانی راکد",
		"Review_Correspondence"=>"مکاتبات",
		"Review_Archive"=>"بایگانی بازبینی",
		"Review_Reviewer"=>"کارشناسان",
		"Review_Admin"=>"مدیر"
	);
	
	function getFullName(){
		return $this->Gender()." ".$this->Firstname." ".$this->Lastname();
	}
	public static function CurrentUser()
	{
		$s = ORM::Find("MyUser",j::UserID());
		return $s;
	}
	
	function RecentProgresses($count){
		return ORM::Query($this)->RecentProgresses($this,$count);
	}
}

use \Doctrine\ORM\EntityRepository;
class MyUserRepository extends EntityRepository
{
	public function GetAll()
	{
		$r=j::ODQL("SELECT U FROM MyUser U");
		return $r;
	}
	public function getAllUsersBelow100()
	{
		return $this->_em->createQuery('SELECT u FROM User u WHERE u.id < 100')
		->getResult();
	}
	public function getReviewerCount()
	{
		$r=j::DQL("SELECT COUNT(U) AS Result FROM MyUser AS U WHERE U.isReviewer=1 AND U.State=1");
		return $r[0]['Result'];
	}
	public function getRandomReviewer()
	{
		$Reviewers=j::ODQL("SELECT U FROM MyUser U WHERE U.isReviewer=1 AND U.State=1");
		$WSum=0;
		if($Reviewers)
		foreach($Reviewers as $R)
		{
			$W=$this->AssignedReviewableFileCount($R);
			$W=100-$W;
			if($W<5)
				$W=5;
			// R:Reviewer, W:Weight, WSB: weightSum befor me
			$RS[]=array("R"=>$R,"W"=>$W,"WSB"=>$WSum);
			$WSum+=$W;
		}
		$Rand=mt_rand(0,$WSum);
		for($i=count($RS)-1; $i>=0; $i--)
		{
			if($Rand>=$RS[$i]['WSB'])
			{
				$Selected=$RS[$i]["R"];
				break;
			}
		}
		return $Selected;
		
		$Offset=mt_rand(0,$this->getReviewerCount()-1);
		$r=j::ODQL("SELECT U FROM MyUser U WHERE U.isReviewer=1 AND U.State=1 LIMIT {$Offset},1");
		if(empty($r))
			return null;
		else
			return $r[0];
		
	}
	public function AssignedReviewableFileCount($Reviewer)
	{
		return count($this->AssignedReviewableFile($Reviewer));
	}

	public function Reviewers($State1="*",$State2=".")
	{
		if($State1==='*'){
			$r=j::ODQL("SELECT U FROM MyUser U WHERE U.isReviewer=1");
		}else{
			$r=j::ODQL("SELECT U FROM MyUser U WHERE U.isReviewer=1 AND (U.State=? OR U.State=?)",$State1, $State2);
		}
		if(empty($r))
			return null;
		else
			return $r;
	}
	public function GetNotReviewers()
	{
		$r=j::ODQL("SELECT U FROM MyUser U WHERE U.isReviewer=0");
		
		if(empty($r))
			return null;
		else
			return $r;
	}
	public function ReviewersCount($State="*")
	{
		if($State==='*'){
			$c=j::ODQL("SELECT COUNT(U) FROM MyUser U WHERE U.isReviewer=1");
		}else{
			$c=j::ODQL("SELECT COUNT(U) FROM MyUser U WHERE U.isReviewer=1 AND U.State=?",$State);
		}
		return $c[0][1];
	}
	/*public function AssignedReviewableFile($Reviewer)
	{
		$r=j::ODQL("SELECT F,P FROM ReviewProgressAssign AS P JOIN P.File AS F WHERE P.Reviewer=? AND
			P.CreateTimestamp=(SELECT MAX(P2.CreateTimestamp) FROM ReviewProgress AS P2 WHERE P2.File=F)
			",$Reviewer);
		foreach($r as $item){
			$files[]=$item->File();
		}
		return $files;
	}*/
	public function AssignedReviewableFile($Reviewer)
	{
		$states=FileFsm::Name2State('reviewing');
		$r=j::ODQL("SELECT F,P FROM ReviewProgressAssign AS P JOIN P.File AS F 
					WHERE P.Reviewer=? AND P.Dead=0 AND F.State={$states[0]}",$Reviewer);
		
		foreach($r as $item){
			$files[]=$item->File();
		}
		return $files;
	}
	public function AssignedReviewableDossier($Reviewer)
	{
		$r=j::ODQL("SELECT F,P FROM ReviewProcessAssign AS P JOIN P.File AS F WHERE P.Reviewer=? AND
			P.CreateTimestamp=(SELECT MAX(P2.CreateTimestamp) FROM ReviewProgress AS P2 WHERE P2.File=F)
			",$Reviewer);
		foreach($r as $item){
			$files[]=$item->File();
		}
		return $files;
	}
	
	/**
	 * 
	 * all Progresses user created
	 * @param MyUser $User
	 * @return array of ReviewProgress
	 * @author Morteza Kavakebi
	 */
	public function RecentProgresses(MyUser $User,$count)
	{
		//TODO think more
		//TODO: where file and type must be added
		$r=j::ODQL("SELECT P FROM ReviewProgress AS P WHERE P.User=? ORDER BY P.ID DESC LIMIT {$count}",$User);
		return $r;
	}
	
	/**
	 * 
	 * Count of Progresses user created
	 * @param MyUser $User
	 * @return integer
	 * @author Morteza Kavakebi
	 */
	public function CountProgresses(MyUser $User)
	{
		//TODO think more
		//TODO: where file and type must be added
		$r=j::ODQL("SELECT Count(P) as co FROM ReviewProgress AS P WHERE P.User=?",$User);
		if(isset($r[0]['co'])){
			return $r[0]['co'];
		}else{
			return null;
		}
	}
	
}