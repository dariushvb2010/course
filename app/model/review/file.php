<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * 
 * @Entity Table(name="ReviewFile")
 * @Entity(repositoryClass="ReviewFileRepository")
 * InheritanceType("JOINED")
 * DiscriminatorColumn(name="discriminator", type="string")
 * DiscriminatorMap({"dbvalue"="class"})
 **/
class ReviewFile
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
	protected $Cotag;
	function Cotag()
	{
		return $this->Cotag;
	}
	function setCotag($value)
	{
		$this->Cotag=$value;
	}
	
    /**
     * @Column(type="integer")
     * @var integer
     */
    protected $CreateTimestamp;
    
    function CreateTime()
    {
    	$jc=new CalendarPlugin();
    	return $jc->JalaliFromTimestamp($this->CreateTimestamp)." ".date("H:i:s",$this->CreateTimestamp);
    }
    
    /**
     * @Column(type="integer")
     * @var integer
     */
    protected $FinishTimestamp;
    function FinishTimestamp()
    {
    	return $this->FinishTimestamp;
    }
    function FinishTime()
    {
    	$jc=new CalendarPlugin();
    	return $jc->JalaliFromTimestamp($this->FinishTimestamp)." ".date("H:i:s",$this->FinishTimestamp);
    }
    function SetFinishTime($time)
    {
	    $this->FinishTimestamp=$time;
    }
    
    
    /**
    *
    * @OneToMany(targetEntity="ReviewProgress", mappedBy="File")
    * @var ReviewProgress
    */
    protected $Progress;
    function Progress()
    {
    	return $this->Progress;
    }     
    
    
    /**
     * @Column(type="integer")
     * @var integer
     */
    protected $Class;
    function GetClass()
    {
    	return $this->Class;
    }
    function SetClass($c)
    {
    	$this->Class=$c;
    }
    /**
    * @Column(type="integer")
    * @var integer
    */
    protected $Gatecode;
    function Gatecode()
    {
    	return $this->Gatecode;
    }
    /**
    * @Column(type="integer")
    * @var integer
    */
    protected $State;
    function State()
    {
    	return $this->State;
    }
    function SetState($value)
    {
    	$this->State=$value;
    }
    
    /**
    *
    * @OneToOne(targetEntity="ReviewDossier", inversedBy="File")
    * @JoinColumn(name="DossierID", referencedColumnName="ID", nullable=true);
    * @var ReviewDossier
    */
    protected $Dossier;
    function Dossier()
    {
    	return $this->Dossier;
    }
    /**
    * @OneToMany(targetEntity="Alarm", mappedBy="File")
    * @var Alarm
    */
    protected $Alarm;
    function Alarm(){ return $this->Alarm;}
    /**
     * 
     * @OneToOne(targetEntity="FileStock", mappedBy="File")
     * @var FileStock
     */
    protected $Stock;
    function Stock(){ return $this->Stock;}
    function SetStock($Stock){ $this->Stock=$Stock; }
    /**
     * returns the recent file
     * @param  integer or string or ReviewFile
     */
    static function GetRecentFile($File)
    {
    	if($File instanceof ReviewFile)
    	return $File;
    	elseif(b::CotagValidation($File))
    	{
    		$File=ORM::Query("ReviewFile")->GetRecentFile($File);
    		if($File instanceof ReviewFile)
    		return $File;
    	}
    }
    /**
     * Has to get the cotages or even files and only return the ReviewFile type
     * @param array of integer|string|ReviewFile $Files
     */
    static function Regulate($Files)
    {
    	$res=array();
    	if($Files)
    	foreach ($Files as $File)
    	{
    		$var=self::GetRecentFile($File);
    		if($var instanceof ReviewFile)
    			$res[]=$var;
    	}
    	return $res;
    }
    /**
    * Has to get the cotages or even files and return the ReviewFile type if file exists 
    * and return the string if file does not exists
    * @param array of integer|string|ReviewFile $Files
    */
    static function RegulateWithError($Files)
    {
    	$res=array();
    	if($Files)
    	foreach ($Files as $File)
    	{
    		$var=self::GetRecentFile($File);
    		if($var instanceof ReviewFile)
    			$res[]=$var;
    		else 
    		{
    			$error='کوتاژ ';
    			if($File instanceof ReviewFile)
    				$error.=$File->Cotag();
    			else 
    				$error.=strval($File);
    			$error.="  یافت نشد.";
    			$res[]=strval($error);
    		}
    		
    	}
    	return $res;
    }
    function __construct($Cotag=null)
    {
    	$this->CreateTimestamp=time();
    	$this->FinishTimestamp=0;
		$this->Cotag=$Cotag;    		
    	$this->Progress= new ArrayCollection();
    	$this->Alarm=new ArrayCollection();
    	$this->Class=ConfigReview::$file_initial_class;
    	$this->Gatecode=ConfigReview::$gate_code;
    	$this->State=ConfigReview::$file_initial_state;
    	
    }
    
    public function Finish()
    {
    	$thisUser=ORM::Find("MyUser", j::UserID());
		$p=new ReviewProgressFinish($this,$thisUser);
		ORM::Persist($p);
    	$this->FinishTimestamp=time();
    }
	function LastProgress($Type="all",$IsProcess=false)
	{
		return ORM::Query($this)->GetLastProgress($this,$Type,$IsProcess);		
	}
    function LLP($Type='all',$IsProcess=false)
    {
    	return ORM::Query($this)->LastLiveProgress($this,$Type,$IsProcess);
    }
	function LastReviewer()
	{
		$ProgAssign= $this->LastProgress('Assign');
		if ($ProgAssign==null) return null;
		return $ProgAssign->Reviewer();
	}
	
	function LastReview()
	{
		$Prog= $this->LastProgress('Review');
		if ($Prog==null) return null;
		return $Prog;
	}
	
	function AllProgress(){
		return ORM::Query($this)->ProgressList($this);
	}
    
    function killLLP($Comment){
    	$LLP=$this->LLP();
    	$res=ORM::Query("ReviewProgressRemove")->AddToFile($LLP,$Comment);
    	if(is_string($res))
    		return $res;
    	
    	$LLP->kill();
    }
}


use \Doctrine\ORM\EntityRepository;
class ReviewFileRepository extends EntityRepository
{
	public function GetLastProgress($File,$Type="all",$IsProcess=false)
	{
		if($IsProcess)
			$T="Process";
		else 
			$T="Progress";
		if (strtolower($Type)!=="all")
			$r=j::ODQL("SELECT P FROM ReviewProgress AS P JOIN P.File AS F WHERE F=? AND P INSTANCE OF 
				Review{$T}{$Type} 
		 		ORDER BY P.CreateTimestamp DESC,P.ID DESC LIMIT 1",$File);
		else
			$r=j::ODQL("SELECT P FROM ReviewProgress AS P JOIN P.File AS F WHERE F=? 
		 		ORDER BY P.CreateTimestamp DESC,P.ID DESC LIMIT 1",$File);
		if ($r)
			return $r[0];
		else 
			return null;
	}
	/**
	*
	* @param integer or reviewfile $Cotag
	*/
	public function LastLiveProgress($Cotag,$Type='all',$IsProcess=false)
	{
		if($Cotag instanceof ReviewFile)
		{
			$Cotag=$Cotag->Cotag();
		}
		if($IsProcess) $T="Process";
		else  $T="Progress";
		if (strtolower($Type)!=="all")
			$r=j::ODQL("SELECT P FROM ReviewProgress AS P join P.File AS F WHERE P.Dead=0 AND F.Cotag= ?
			AND P INSTANCE OF Review{$T}{$Type} 
			ORDER BY P.CreateTimestamp DESC,P.ID DESC LIMIT 1 ",$Cotag);
		else 
			$r=j::ODQL("SELECT P FROM ReviewProgress AS P join P.File AS F WHERE P.Dead=0 AND F.Cotag= ?
			ORDER BY P.CreateTimestamp DESC,P.ID DESC LIMIT 1 ",$Cotag);
		return $r[0];
	}

	/**
	 * 
	 * @param ReviewProgress $Progress
	 * @param string $Type
	 * @param boolean $IsProcess
	 * @return ReviewProgress
	 */
	public function NextLiveProgress($Progress,$Type='all',$IsProcess=false)
	{
		if($Cotag instanceof ReviewFile)
		{
			$Cotag=$Progress->File()->Cotag();
		}
		
		if($IsProcess) $T="Process";
		else  $T="Progress";
		
		
		$Query="SELECT P FROM ReviewProgress AS P join P.File AS F 
				WHERE P.Dead=0 AND F.Cotag= ? AND P.CreateTimestamp>?".
				(strtolower($Type)!=="all"?"AND P INSTANCE OF Review{$T}{$Type}":"").
				"ORDER BY P.CreateTimestamp DESC,P.ID DESC LIMIT 1";
		
		$r=j::ODQL($Query,$Cotag,$Progress->CreateTimestamp());	 
		if($r)
			return $r[0];
		else
			return null;
	}
	/**
	 * 
	 * in report use only
	 * @param unknown_type $Offset
	 * @param unknown_type $Limit
	 * @param unknown_type $Sort
	 * @param unknown_type $Order
	 * @return array of ReviewFile
	 * @author Morteza Kavakebi
	 */
	public function CotagBookNotSentFiles($Offset=0,$Limit=100,$Sort="Cotag", $Order="ASC")
	{
		$states=FileFsm::Name2State('Cotag');
		$states=implode(',', $states);
		$r=j::DQL("SELECT F FROM ReviewFile AS F WHERE F.State IN ({$states}) ORDER BY F.{$Sort} {$Order} LIMIT {$Offset},{$Limit}");
		return $r;
	}
	public function GetOnlyProgressStartObject($Offset=0,$Limit=100,$Sort="Cotag", $Order="ASC")
	{
		$r=j::ODQL("SELECT F FROM ReviewFile AS F JOIN F.Progress AS P
							WHERE P.CreateTimestamp=
								(SELECT MAX(P2.CreateTimestamp) FROM ReviewProgress AS P2 WHERE P2.File=F)
								 AND P INSTANCE OF ReviewProgressStart    
							ORDER BY F.{$Sort} {$Order} LIMIT {$Offset},{$Limit}");
		return $r;
	}
	public function GetOnlyProgressStartObject2($Offset=0,$Limit=100,$Sort="Cotag", $Order="ASC")
	{
		$r=j::ODQL("SELECT F FROM ReviewFile AS F JOIN F.Progress AS P
								WHERE P.MailNum!=0 AND P.CreateTimestamp=
									(SELECT MAX(P2.CreateTimestamp) FROM ReviewProgress AS P2 WHERE P2.File=F)
									 AND P INSTANCE OF ReviewProgressStart    
								ORDER BY F.{$Sort} {$Order} LIMIT {$Offset},{$Limit}");
		return $r;
	}
	public function GetFilesWithLastProgress($Progress,$Offset=0,$Limit=100,$Sort="Cotag", $Order="ASC")
	{
		$r=j::ODQL("SELECT F FROM ReviewFile AS F JOIN F.Progress AS P
								WHERE
									P.ID=(SELECT MAX(P3.ID) FROM ReviewProgress AS P3 WHERE P3.File=F)
									 AND P INSTANCE OF ReviewProgress{$Progress}   
								ORDER BY F.{$Sort} {$Order} LIMIT {$Offset},{$Limit}");
		return $r;
	}
	
	/**
	 * 
	 * برای این که در فایل جدید مطمئن شویم ک این  کوتاژ قبلا در سال جاری وصول نشده 
	 * @param integer $Cotag
	 * @return ReviewFile
	 */
	public function GetRecentFile($Cotag)
	{
		$r=j::ODQL("SELECT F FROM ReviewFile AS F WHERE F.Cotag=?",$Cotag);
		if(count($r))
			return $r[0];
		else
			return null;
	}	
	
	/**
	 * 
	 * تمام اظهارنامه هایی را بر میگرداند که آخرین فرآیند آنها
	 * کارشناسی با result=false
	 */
	public function WaitingForConfirmFilesInRange()
	{
		$r1=j::ODQL("SELECT F,P FROM ReviewProgressReview AS P JOIN P.File AS F WHERE P.Result=0 AND
				P.CreateTimestamp=(SELECT MAX(P2.CreateTimestamp) FROM ReviewProgress AS P2 WHERE P2.File=F)"
				.($sort?" ORDER BY F.{$sort} {$ord} ":"").($off?" LIMIT {$off},{$lim}":"")
		);
		$r2=j::ODQL("SELECT F,P FROM ReviewProgressClasseconfirm AS P JOIN P.File AS F 
						WHERE P.CreateTimestamp=
								(SELECT MAX(P2.CreateTimestamp) FROM ReviewProgress AS P2 WHERE P2.File=F)"
		.($sort?" ORDER BY F.{$sort} {$ord} ":"").($off?" LIMIT {$off},{$lim}":"")
		);
		
		
		foreach($r1 as $D)
			$f[]=$D->File();
		foreach($r2 as $D)
			$f[]=$D->File();
		return $f;
	}
	/**
	 *
	 * برای استفاده در گزلرش گیری
	 * @param unknown_type $From
	 * @param unknown_type $Limit
	 */
	public static function UnassignedFiles($From=0,$Limit=0)
	{
		$states=FileFsm::Name2State('Assignable');
		$states=implode(',', $states);
		$r=j::DQL("SELECT F FROM ReviewFile AS F WHERE F.State IN ({$states})
					ORDER BY F.Cotag LIMIT {$From},{$Limit}");
		return $r;
	}

	/**
	 *
	 * لیست کوتاژهای بر اساس نام وضعیت
	 * @param string $StateName
	 * @return array of ReviewFile
	 * @author Morteza Kavakebi
	 */
	public static function FilesByStateName($StateName)
	{
		$states=FileFsm::Name2State($StateName);
		$states=implode(',', $states);
		$r=j::ODQL("SELECT F FROM ReviewFile AS F WHERE F.State IN ({$states})
					ORDER BY F.Cotag");
		return $r;
	}

	/**
	 * 
	 * لیست فایل هایی که مدتی است در استیت مشخص شده مانده اند
	 * کاربرد در ماده 1415 کردن پس از گذشت مدت قانونی در ابلاغ مطالبه نامه
	 * @param unknown_type $State
	 * @param integer $PeriodSeconds
	 * @return array of ReviewFile
	 * @author morteza kavakebi
	 */
	public function ExpiredStateFiles($StateName,$PeriodSeconds)
	{
		$StateNumber=FileFsm::Name2State($StateName);
		$c_time=time();
		$r=j::DQL("SELECT F FROM ReviewFile AS F JOIN F.Progress AS P
						WHERE F.State={$StateNumber} AND {$c_time}-P.CreateTimestamp>{$PeriodSeconds}
						ORDER BY F.Cotag");
		return $r;
	}

	/**
	 *
	 * used for list of UnReceived Files 
	 * فایل های که ثبت مختومه شده اند نباید با این کوئری به لیست کوتاژ های وصول نشده بروند 
	 * @param unknown_type $start
	 * @param unknown_type $end
	 */
	public function RecievedInRange($start=0,$end=0)
	{
		$r=j::DQL("SELECT F.Cotag FROM ReviewFile AS F  WHERE  F.CreateTimestamp > ?  AND F.Cotag BETWEEN {$start} AND {$end}",time()-(365*24*60*60));
		return $r;
	}
	/**
		لیست فایل ها و اخرین ‍فرایند هر کدام برای استفاده در لیست کوتاژ
	 * 
	 */
	public function CotagList($Offset,$Limit,$Sort,$SortOrder)
	{
		$r=j::ODQL("SELECT P,F FROM ReviewFile AS F LEFT JOIN F.Progress AS P WHERE P IS NULL OR P.CreateTimestamp=(SELECT MAX(P2.CreateTimestamp) FROM ReviewProgress AS P2 WHERE P2.File=F)
			ORDER BY F.{$Sort} {$SortOrder} LIMIT {$Offset},{$Limit}");
		return $r;
	}
	
	public function ProgressList($File)
	{
		//TODO think more
		//TODO: where file and type must be added
		$r=j::ODQL("SELECT P FROM ReviewProgress AS P WHERE P.File=?",$File);
		return $r;
	}
	
	
	public function CotagCount()
	{
		$r=j::DQL("SELECT COUNT(F) AS Result FROM ReviewFile AS F");
		return $r[0]['Result'];
		
	}

	/**
	 *
	 * لغو ابطالی ها و تحویلی های دفتر کوتاژ
	 * برای تحویل دفتر کوتاژ به بایگانی بازبینی
	 * @param unknown_type $TimeStart
	 * @param unknown_type $TimeEnd
	 * @return array of ReviewFile
	 * @author Morteza Kavakebi
	 */
	public function FilesInTimeRange($TimeStart,$TimeEnd,MyUser $User)
	{
		
		$r=j::ODQL("SELECT F FROM ReviewFile AS F JOIN F.Progress P 
						WHERE P INSTANCE OF ReviewProgressStart AND F.State=2 AND P.CreateTimestamp BETWEEN ? AND ? ORDER BY F.Cotag",$TimeStart,$TimeEnd);
		return $r;
	}
	
	/**
	 * used in correspondence
	 */
	public function GetRecentFileByClasse($Classe)
	{
		$r=j::ODQL("SELECT F FROM ReviewFile AS F WHERE F.Class=? ",$Classe);
		return $r[0];
	}
	
}