<?php

use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity Table(name="ReviewProgress")
 * @entity(repositoryClass="ReviewProgressRepository")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="Type", type="string")
 * @DiscriminatorMap({"Base" = "ReviewProgress",
 * 	"Assign"="ReviewProgressAssign",
 * 	"Correction"="ReviewProgressCorrection",
 * 	"Classeconfirm"="ReviewProgressClasseconfirm",
 * 	"Start"="ReviewProgressStart",
 * 	"Scan"="ReviewProgressScan",
 * 	"Ebtal"="ReviewProgressEbtal",
 * 	"Review"="ReviewProgressReview",
 * 	"RegisterInArchive"="ReviewProgressRegisterarchive",
 *  "Give"="ReviewProgressGive",
 *  "Get"="ReviewProgressGet",
 *  "Send"="ReviewProgressSend",
 *  "Receive"="ReviewProgressReceive",
 * 	"Remove"="ReviewProgressRemove",
 * 
 * 	"AssignProtest"="ReviewProcessAssign",
 * 	"Confirm"="ReviewProcessConfirm",
 * 	"Feedback"="ReviewProcessFeedback",
 * 	"Forward"="ReviewProcessForward",
 *  "Judgement"="ReviewProcessJudgement",
 *  "Payment"="ReviewProcessPayment",
 *  "Prophecy"="ReviewProcessProphecy",
 *  "Protest"="ReviewProcessProtest",
 *  "Refund"="ReviewProcessRefund",
 *  "RegisterClasse"="ReviewProcessRegister",
 *  "Senddemand"="ReviewProcessSenddemand",
 *  "Clearance"="ReviewProcessClearance"
 * })
 * */
abstract class ReviewProgress
{
	/**
	 * @GeneratedValue @Id @Column(type="integer")
	 * @var integer
	 */
	protected $ID;
	function ID(){ return $this->ID; }
	/**
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $CreateTimestamp;
	function CreateTimestamp(){ return $this->CreateTimestamp; }
	function SetCreateTimestamp($Time){ $this->CreateTimestamp=$Time; }
	function CreateTime()
	{
		$jc=new CalendarPlugin();
		return $jc->JalaliFullTime($this->CreateTimestamp);
	}
	/**
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $EditTimestamp;
	function SetEditTimestamp($Timestamp){ $this->EditTimestamp=$Timestamp; }
	/**
	 * @ManyToOne(targetEntity="ReviewFile", inversedBy="Progress")
 	 * @JoinColumn(name="FileID", referencedColumnName="ID")
	 */
	protected $File;
	function File(){ return $this->File; }
	function SetFile(ReviewFile $File){ $this->File=$File; }
	/**
	 * Assigns the file here and assigns this progress to the file
	 * @param ReviewFile $File
	 */
	function AssignFile(ReviewFile $File)
	{
		$this->File=$File;
		$File->Progress()->add($this);		
	}
	/**
	 * @ManyToOne(targetEntity="MyUser", inversedBy="Progress")
 	 * @JoinColumn(name="UserID", referencedColumnName="ID")
	 */
	protected $User;
	function User(){ return $this->User; }
	function SetUser(MyUser $User){	$this->User=$User; }
	function AssignUser(MyUser $User)
	{
		$this->User=$User;
		$User->Progress()->add($this);
	}
	/**
	 * @Column(type="string", nullable=true)
	 * @var string
	 */
	protected $Comment;
	function Comment(){ return $this->Comment; }
	function setComment($value){ $this->Comment=$value;	}
	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $MailNum;
	function MailNum(){ return $this->MailNum; }
	function setMailNum($value){ $this->MailNum=$value;	}
	/**
	 * 
	 * @Column(type="integer") 
	 * @var integer 
	 */
	protected  $PrevState;
	function PrevState(){ return $this->PrevState; }
	function SetPrevState($State){ $this->PrevState=$State; }
	function SetState(ReviewFile $File,$state)
	{
		$this->SetPrevState($File->State());
		$File->SetState($state);
	}
	/**
	 * @Column(type="boolean")
	 * @var boolean
	*/
	protected  $Dead;
	function Dead(){ return $this->Dead; }
	function SetDead($value=true){ $this->Dead=$value; }
	
	/**
	 * @return ReviewProgress
	 */
	function NLP(){
		return ORM::Query("ReviewFile")->NextLiveProgress($this);
	}
		
	function kill()
	{
		$NLP=$this->NLP();
		if($NLP){
			$NLP->SetPrevState($this->PrevState());
		}else{
			$File=$this->File();
			$File->SetState($this->PrevState());
		}
		$this->SetDead(true);
	}
	function Concat($Prefix,$Flag,$IfTrue,$IfFalse)
	{
		$R=$Prefix;
		$R.=$Flag ? $IfTrue : $IfTrue;
		return $R;
	}
	function Cotag()
	{
		return $this->File->Cotag();
	}
	/**
	 * Creates a new progress
	 * @param ReviewFile $File
	 * @param MyUser $User
	 */
	function __construct(ReviewFile $File=null,MyUser $User=null)
	{
		$this->CreateTimestamp=time();
		$this->EditTimestamp=0;
		if($File)
			$this->SetFile($File);
		if(!$User)
			$User=MyUser::CurrentUser();
		if($User)
			$this->SetUser($User);
		$this->Comment="";
		$this->MailNum="";
		$this->PrevState=0;
		$this->Dead=0;
	}

	/**
	 * @author Morteza ;)
	 * @var unknown_type
	 */
	public $error=null;
	
	abstract function Summary();
	/**
	 * Returns Persian name of this progress
	 * @return string
	 */
	abstract function Title();
	/**
	 * returns the final name of the progress
	 * @return string
	 */
	//abstract function Event();
	/**
	 * Has to return a texual name of the progress depending on result of the progress
	 * @return string
	 */
	private function MakeAlarm()
	{
		$ETitle=$this->Event();
// 		if($ETitle==null OR $ETitle=="" OR !ETitle)
// 			throw new Exception("EventTitle is empty!");
		$Event=ORM::Find1("ConfigEvent", "EventName", $ETitle);
		
		if(!$Event)
			return;
		$ConfigAlarms=$Event->ChildConfigAlarm();
		if($ConfigAlarms)
		foreach ($ConfigAlarms as $CA)
		{
			if($CA instanceof ConfigAlarm)
			$AA=ORM::Query(new AlarmAuto())->Add($this->File(),$Event,$CA);
			if(!($AA instanceof AlarmAuto))
				throw new Exception("Cannot create AlarmAuto at ReviewProgress class!");
		}
	}
	private function KillAlarm()
	{
		$ETitle=$this->Event();
		$Event=ORM::Find1("ConfigEvent", "EventName", $ETitle);
		
		if(!$Event)
		return;
		$ConfigAlarms=$Event->SlainConfigAlarm();
		if($ConfigAlarms)
		foreach ($ConfigAlarms as $CA)
		{
			if($CA instanceof ConfigAlarm)
			$AA=ORM::Query(new AlarmAuto())->Delete($this->File(),$CA);
// 			if(!($AA instanceof AlarmAuto))
// 			throw new Exception("Cannot create AlarmAuto at ReviewProgress class!");
		}
	}
	private function ApplyAlarm()
	{
		$this->KillAlarm();
		$this->MakeAlarm();
	}
	/**
	 * 
	 * @param boolean $Persist whether change the file or other classes(true) or not(false) 
	 * @throws Exception
	 */
	private function DoFileState($Persist)
	{
		$EName=$this->Event();
		if(!isset($EName))
			throw new Exception("Event Not Set for Progress ".get_class($this));
		$CurrentState=$this->File->State();
		$NewState=FsmGraph::NextState($CurrentState, $EName);
		if(!isset($NewState))
		{
			return "به دلیل وضعیت فعلی اظهارنامه انجام این فرآیند امکان پذیر نیست.";
		}
		$this->SetPrevState($CurrentState);
		if($Persist)
			$this->File()->SetState($NewState);
		return $NewState;
	}
	public function AddAssociations(){
		if($this->File->Progress()->contains($this))
			throw new Exception("AddAssociation Exception!!!!");
		$this->File->Progress()->add($this);
		$this->User->Progress()->add($this);
	}
	/**
	 * Has to check the situations and apply the changes and make the entities
	 */
	function Apply()
	{
		if ($this->error!=null)
			return $this->error;

		$res=$this->DoFileState(true);
		if(!is_string($res))
		{
			$this->ApplyAlarm();
			$this->AddAssociations();
		}
		return $res;
	}
	/**
	 * Completely pure function
	 * Only Has to check the situations and return the Errors and not perform any changes
	 */
	function Check()
	{
		if ($this->error!=null)
			return $this->error;
		return $this->DoFileState(false);
	}
}

use \Doctrine\ORM\EntityRepository;
class ReviewProgressRepository extends EntityRepository
{
	/**
	 * @author dariush
	 * @tutorial  bazdoc/review/progress/start/monthlystart.html
	 * @see http://dev.mysql.com/doc/refman/5.5/en/date-and-time-functions.html#function_date-add
	 *
	 * --------------time line ------->-->---->----->------>----->------>------->------>------>---->----->----->----->----->-----
	 * ********|***M(monthCount-1)***|  ...  |***M(2)***|***M(1)***|***M(0)***|startMonth=4|***M***|***M***|***M***|***now***|****
	 * --------------------------------------------------------------------------------------------------------------------------
	 * @param integer $monthCount: number of monthes to show
	 * @param integer $startMonth: how many monthes ago
	 * @return array the number of files started in a unit(month, day) seperated by unit(month, day)
	 */
	public function ProgressCountPerMonth($ProgressType,$monthCount, $startMonth=0)
	{
		//-----get the numberof days of last month---------------------------
		$c = new CalendarPlugin();
		$t = $c->TodayJalaliArray();
		$dayOfMonth= $t[2]; //our purpose--------------------------------
		$addDays = 30 - $dayOfMonth;
		
		
		$oc=j::SQL("SELECT COUNT(P.ID) as count,
						floor(
							DATEDIFF(
								curdate() + INTERVAL ? DAY - INTERVAL ? MONTH,
								FROM_UNIXTIME(P.CreateTimestamp)
							)/30.3
						) as month
					FROM app_ReviewProgress AS P
					WHERE P.Type=?
						AND DATEDIFF(
								curdate() + INTERVAL ? DAY - INTERVAL ? MONTH,
								FROM_UNIXTIME(P.CreateTimestamp)
							) >= 0
					GROUP BY month ",$addDays,$startMonth,$ProgressType,$addDays,$startMonth);
	
	
		//-----------------making $res------------------
		while(count($oc)){
			$t=array_pop($oc);
			$month=$t['month'];
			if($month*1<$monthCount)
			$res[$month*1+$startMonth]=$t['count'];
		}
		
		for($i=$startMonth;$i<$startMonth+$monthCount;$i++){
			if(!array_key_exists($i,$res)){
				$res[$i]=0;//array('count'=>0,'month'=>$i);
			}
		}
		krsort($res);
		return $res;
	}
}
