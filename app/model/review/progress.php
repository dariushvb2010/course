<?php

use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity Table(name="ReviewProgress")
 * @entity(repositoryClass="ReviewProgressRepository")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="Type", type="string")
 * @DiscriminatorMap({"Base" = "ReviewProgress",
 * 	"Assign"="ReviewProgressAssign",
 * 	"Finish"="ReviewProgressFinish",
 * 	"Correction"="ReviewProgressCorrection",
 * 	"Classeconfirm"="ReviewProgressClasseconfirm",
 * 	"Start"="ReviewProgressStart",
 * 	"Review"="ReviewProgressReview",
 * 	"Manual"="ReviewProgressManual",
 * 	"ManualCorrespondence"="ReviewProgressManualcorrespondence",
 * 	"SendFile"="ReviewProgressSendfile",
 * 	"ReceiveFile"="ReviewProgressReceivefile",
 * 	"CorrespondenceFinish"="ReviewProgressFinishcorrespondence",
 * 	"DeliverToReview"="ReviewProgressDeliver",
 * 	"RegisterInArchive"="ReviewProgressRegisterarchive",
 * 	"RegisterInRaked"="ReviewProgressRegisterraked",
 * 	"ReturnFromRaked"="ReviewProgressReturn",
 * 	"Post"="ReviewProgressPost",
 *  "Give"="ReviewProgressGive",
 *  "Get"="ReviewProgressGet",
 *  "Send"="ReviewProgressSend",
 *  "Receive"="ReviewProgressReceive",
 * 	"Cancelassign"="ReviewProgressCancelassign",
 * 	"Remove"="ReviewProgressRemove",
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
 *  "Senddemand"="ReviewProcessSenddemand"
 * })
 * */
abstract class ReviewProgress
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
		return $jc->JalaliFullTime($this->CreateTimestamp);
	}
	/**
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $EditTimestamp;
	function SetEditTimestamp($Timestamp)
	{
		$this->EditTimestamp=$Timestamp;
	}
	
	/**
	 * @ManyToOne(targetEntity="ReviewFile", inversedBy="Progress")
 	 * @JoinColumn(name="FileID", referencedColumnName="ID")
	 */
	protected $File;
	function File()
	{
		return $this->File;
	}
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $File
	 * @return ReviewFile
	 */
	function SetFile(ReviewFile $File)
	{
		$this->File=$File;
	}
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
	/**
	 * 
	 * Enter description here ...
	 * @return Myuser
	 */
	function User()
	{
		return $this->User;
	}
	function SetUser(MyUser $User)
	{
		$this->User=$User;
	}
	function AssignUser(MyUser $User)
	{
		$this->User=$User;
		$User->Progress()->add($this);
	}


	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $Comment;
	function Comment()
	{
		return $this->Comment;
	}
	function setComment($value){
		$this->Comment=$value;
	}
	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $MailNum;
	function MailNum()
	{
		return $this->MailNum;
	}
	function setMailNum($value){
		$this->MailNum=$value;
	}
	/**
	 * 
	 * @Column(type="integer") 
	 * @var integer 
	 */
	protected  $PrevState;
	function PrevState()
	{
		return $this->PrevState;
		
	}
	function SetPrevState($State)
	{
		$this->PrevState=$State;
	}
	function SetState(ReviewFile $File,$state){
		$this->SetPrevState($File->State());
		$File->SetState($state);
	}
	
	/**
	 *
	 * @Column(type="boolean")
	 * @var boolean
	*/
	protected  $Dead;
	function Dead()
	{
		return $this->Dead;
	}
	function kill()
	{
		$this->Dead=true;
	}
	static function TruncateTables()
	{
		mysql_connect('localhost','root',
		'119ba00fd73711a09fa82177f48f4e4ac32b1e1d73925fc4f654851b617b2a96fd5a5b3095d59b59e5cdfd71312ba3f61195414758478feced69544447360003')
		or die(mysql_error());
		/*
		mysql_query("TRUNCATE TABLE `app_reviewprogressreview` ;
					TRUNCATE TABLE `app_reviewprogressmanual` ;
					TRUNCATE TABLE `app_reviewprogressfinish` ;
					TRUNCATE TABLE `app_reviewprogressassign` ;
					TRUNCATE TABLE `app_reviewprogress` ;
					TRUNCATE TABLE `app_reviewfile` ;");
		
		*/
		j::DQL("DELETE FROM ReviewProgressFinish");
		j::DQL("DELETE FROM ReviewProgressManual");
		j::DQL("DELETE FROM ReviewProgressReview");
		j::DQL("DELETE FROM ReviewProgressAssign");
		j::DQL("DELETE FROM ReviewProgressStart");
		j::DQL("DELETE FROM ReviewProgress");
		j::DQL("DELETE FROM ReviewFile");
		
	}
	function Concat($Prefix,$Flag,$IfTrue,$IfFalse)
	{
		$R=$Prefix;
		$R.=$Flag ? $IfTrue : $IfTrue;
		return $R;
	}
	/**
	 * 
	 * Creates a new progress
	 * @param ReviewFile $File
	 * @param MyUser $User
	 */
	function __construct(ReviewFile $File=null,MyUser $User=null,$IfPersist=true)
	{
		$this->CreateTimestamp=time();
		$this->EditTimestamp=0;
		if($File)
			$IfPersist ? $this->AssignFile($File) : $this->SetFile($File);
		if ($User) 
			$IfPersist ? $this->AssignUser($User) : $this->SetUser($User);
		$this->Comment="";
		$this->MailNum="";
		$this->PrevState=0;
		$this->Dead=0;
	}

	/**
	 * 
	 * Has to return a textual summary of the progress
	 */
	abstract function Summary();
	/**
	 * 
	 * Returns Persian name of this progress
	 * @return string
	 * 
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
		// 		if($ETitle==null OR $ETitle=="" OR !ETitle)
		// 			throw new Exception("EventTitle is empty!");
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
	private function ApplyFileState()
	{
		$EName=$this->Event();
		if(!isset($EName))
			throw new Exception("Event Not Set for Progress ".get_class($this));
		$CurrentState=$this->File->State();
		$NewState=FileFsm::NextState($CurrentState, $EName);
		if(!isset($NewState))
		{
			
			$str="از شماره حالت ".$CurrentState;
			$str.="پلی با نام رویداد ".$EName;
			$str.="ثبت نشده است.";
			$str.=" حالت جدید:".$NewState;
			return $str;
		}
		$this->SetPrevState($CurrentState);
		$this->File()->SetState($NewState);
		return $NewState;
	}
	private function CheckFileState()
	{
		$EName=$this->Event();
		if(!isset($EName))
		throw new Exception("Event Not Set for Progress ".get_class($this));
		$CurrentState=$this->File->State();
		$NewState=FileFsm::NextState($CurrentState, $EName);
		if(!isset($NewState))
		{
				
			$str="از شماره حالت ".$CurrentState;
			$str.="پلی با نام رویداد ".$EName;
			$str.="ثبت نشده است.";
			$str.=" حالت جدید:".$NewState;
			return $str;
		}
		return $NewState;
	}
	/**
	 * Has to check the situations and apply the changes and make the entities
	 */
	function Apply()
	{
		$this->ApplyAlarm();
		return $this->ApplyFileState();
	}
	/**
	 * Only Has to check the situations and return the Errors and not perform any changes
	 */
	function Check()
	{
		return $this->CheckFileState();
	}
}


use \Doctrine\ORM\EntityRepository;
