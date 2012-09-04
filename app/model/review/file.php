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
	function ID(){ return $this->ID;	}
	/**
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $Cotag;
	function Cotag(){ return $this->Cotag; }
	function setCotag($value){	$this->Cotag=$value; }
	/**
	 * شماره مجوز بارگیری
	 * Bargiri Serail
	 * @Column(type="string", nullable="true")
	 * @var string
	 */
	protected $BarSerial;
	function BarSerial(){ return $this->BarSerial; }
	function SetBarSerial($BarSerial){ $this->BarSerial = $BarSerial;	}
	/**
	 * @Column(type="integer", nullable="true")
	 * @var integer
	 */
	protected $RegYear;
	function RegYear(){ return $this->RegYear; }
	function SetRegYear($RegYear){ $this->RegYear = $RegYear;	}
    /**
     * @Column(type="integer")
     * @var integer
     */
    protected $CreateTimestamp;
    
    function CreateTime(){	
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
    * @OneToMany(targetEntity="ReviewProgress", mappedBy="File")
    * @var ReviewProgress
    */
    protected $Progress;
    function Progress(){  return $this->Progress; }     
    /**
    * @Column(type="integer")
    * @var integer
    */
    protected $Gatecode;
    function Gatecode(){ return $this->Gatecode;  }
    /**
    * @Column(type="integer")
    * @var integer
    */
    protected $State;
    function State(){ 	return $this->State;  }
    function SetState($value){	$this->State=$value; }
    /**
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
    
    public function GetClass(){
    	$p = $this->LLP("Register",true);
    	return $p ? $p->Classe() : null;
    }
    /**
     * returns the recent file
     * @param  integer or string or ReviewFile
     * @return ReviewFile
     */
    static function GetRecentFile($input,$gateCode=null)
    {
    	if($input instanceof ReviewFile)
    		return $input;
    	elseif(b::CotagValidation($input))
    	{
    		$goodGate=($gateCode==null?GateCode:$gateCode);
    		return ORM::Query("ReviewFile")->GetRecentFile($input,$goodGate);
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
    		$var=b::GetFile($File);
    		if($var instanceof ReviewFile)
    		{	
    			$res[]=$var;
    		}
    		else 
    		{
    			$res[]=v::Ecnf($File);
    		}
    		
    	}
    	return $res;
    }
    function __construct($Cotag=null)
    {
    	$this->CreateTimestamp=time();
    	$this->FinishTimestamp=0;
    	$this->Progress= new ArrayCollection();
    	$this->Alarm=new ArrayCollection();
    	$this->Class=b::file_initial_class;
    	$this->State=b::file_initial_state;
		if($Cotag){
			$g=$this->ParseCotag($Cotag);
			if(count($g)==2){
	    		$this->Cotag=$g[1];    		
	    		$this->Gatecode=$g[0];
			}
		}
    	
    }
    
    private function ParseCotag($Cotag){
    	$inpAr=explode('-',$Cotag);
    	if (count($inpAr)==2){
    		return array($inpAr[0],$inpAr[1]);
    	}else{
    		return array(GateCode,$inpAr[0]);
    	}
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
    
    function UpdateSerial(){
    	if ($this->Gatecode()!=GateCode)
    		return false;

    	if($this->BarSerial()>1)
    		return true;
    	

    	$cq=new ConnectionBakedata();
    	$cq->GetMojavezBargiriYear($this->Cotag(),$this->RegYear());
    	if($cq->Validate()){
    		$s=$cq->GetResult();
    		$this->SetBarSerial($s);
    		ORM::Write($this);
    		return true;
    	}else{
    		return false;
    	}
    }
    
    function UpdateYear(){
    	if ($this->Gatecode()!=GateCode)
    		return false;

    	if($this->RegYear()>0)
    		return true;
    	$cq=new ConnectionBakedata();
    	$r=$cq->GetYear($this->Cotag());
    	if($r){
    		$this->SetRegYear($r);
    		ORM::Write($this);
    		return true;
    	}else{
    		return false;
    	}
    }
    
    /**
     * if Asycuda Contains it
     * @param unknown_type $input
     * @return boolean
     */
    public static function AsyValidation($input){
    	$ar=self::ParseCotag($input);
    	if(count($ar)>1 AND $ar[0]!=GateCode)
    		return true;
    	
    	$cotag=(count($ar)>1?$ar[1]:$ar[0]);	
    	
    	$cq=new ConnectionBakedata();
    	$r=$cq->GetYear($cotag);
    	if($r){
    		return true;
    	}else{
    		return false;
    	}
    }
    
    function UpdateAsycuda(){
    	if ($this->Gatecode()!=GateCode)
    		return false;
    
    	if(ConnectionAsy::GetAsyByFile($this))
    		return true;

    	//return false;
    	 
    
    	$cq=new ConnectionBakedata();
    	$cq->GetParvanehFromAsycudaYear($this->Cotag(),$this->RegYear());
    	if($cq->Validate()){
    		$s=$cq->GetResult();
    		$r=new ConnectionAsy($this, $s);
    		
    		ORM::Persist($r);
    		//ORM::Flush();
    		return true;
    	}else{
    		return false;
    	}
    	
    }
    function CheckUp(){
    	$p=$this->UpdateYear();
    	if(!$p)
    		return false;

    	$p=$this->UpdateSerial();
    	return $this->UpdateAsycuda();
    }
    
    function CheckUpForce(){
    	ConnectionAsy::DeleteAsyByFile($this);
    	$this->SetRegYear(0);
    	$this->SetBarSerial(0);
    	$this->CheckUp();
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
	 * @param ReviewFile $File
	 * @param unknown_type $Type
	 * @param Boolean $IsProcess
	 */
	public function LastLiveProgress($File,$Type='all',$IsProcess=false)
	{
		if($IsProcess) $T="Process";
		else  $T="Progress";
		if (strtolower($Type)!=="all")
			$r=j::ODQL("SELECT P FROM ReviewProgress AS P join P.File AS F 
						WHERE P.Dead=0 AND F=?
						AND P INSTANCE OF Review{$T}{$Type} 
						ORDER BY P.CreateTimestamp DESC,P.ID DESC LIMIT 1 ",$File);
		else 
			$r=j::ODQL("SELECT P FROM ReviewProgress AS P join P.File AS F 
						WHERE P.Dead=0 AND F=?
						ORDER BY P.CreateTimestamp DESC,P.ID DESC LIMIT 1 ",$File);
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
		$File=$Progress->File();
		
		if($IsProcess) $T="Process";
		else  $T="Progress";
		
		
		$Query="SELECT P FROM ReviewProgress AS P join P.File AS F 
				WHERE P.Dead=0 AND F=? AND P.CreateTimestamp>?".
				(strtolower($Type)!=="all"?"AND P INSTANCE OF Review{$T}{$Type}":"").
				"ORDER BY P.CreateTimestamp DESC,P.ID DESC LIMIT 1";
		
		$r=j::ODQL($Query,$File,$Progress->CreateTimestamp());	 
		if($r)
			return $r[0];
		else
			return null;
	}
	
	/**
	 * لیست کوتاژهای بر اساس نام وضعیت
	 * @param string or Integer $StateName
	 * @param integer OR 'all' $GateCode
	 * @param array $Pagination
	 * $Pagination is Like
	 * array('Sort'=>'Cotag','Order'=>'ASC','Offset'=>0,'Limit'=>100)
	 * @author Morteza Kavakebi
	 */
	//public static function FilesByStateName(,$GateCode='all',$Pagination=null)
	public static function FilesByCondition($Conditions,$Pagination=null)
	{
		//----------------WHERE CLAUSE-----------
		$whereAr=array();
		foreach($Conditions as $k=>$v){
			if($k=='State'){
				$states=FsmGraph::Name2State($v);
				$states=implode(',', $states);
				$whereAr[]="F.State IN ({$states})";
			}elseif($k=='Gatecode'){
				if($v!='all')
					$whereAr[]="F.Gatecode={$GateCode}";
			}
		}
		if(count($whereAr)){
			$where=" WHERE ".implode(' AND ',$whereAr);
		}else{
			$where='';
		}
		//----------------------------------------------------------
		if($Pagination=='CountAll'){
			$QueryStr="SELECT count(F) as result FROM ReviewFile AS F ".$where;
			$r=j::ODQL($QueryStr);
			return $r[0]['result'];
		}else{
			$QueryStr="SELECT F FROM ReviewFile AS F ".$where;
				
			//----------Pagination
			if($Pagination==null){
				$QueryStr.=" ORDER BY F.Cotag ";
			}else{
				if(isset($Pagination['Sort'])){
					$QueryStr.=" ORDER BY F.{$Pagination['Sort']} ";
					if(isset($Pagination['Order']))
						$QueryStr.=" {$Pagination['Order']} ";
				}
				if(isset($Pagination['Offset'])){
					$QueryStr.=" Limit {$Pagination['Offset']} ";
					if(isset($Pagination['Limit']))
						$QueryStr.=", {$Pagination['Limit']} ";
				}
			}
			$r=j::ODQL($QueryStr);
			echo $QueryStr;
			var_dump($r);
			return $r;
		}
	}
	
	/**
	 لیست فایل ها و اخرین ‍فرایند هر کدام برای استفاده در لیست کوتاژ
	 *
	 */
	public function CotagList($Pagination=null,$gateCode='All',$operation='=')
	{		
		if($Pagination=='CountAll'){
			$QueryStr="SELECT count(F) as result FROM ReviewFile AS F ";
			
			if($GateCode!='all')
				$QueryStr.=" WHERE F.Gatecode".$operation.$gateCode;
			$r=j::ODQL($QueryStr);
			return $r[0]['result'];
		}else{
			$QueryStr="SELECT F FROM ReviewFile AS F ";
			if($GateCode!='all')
				$QueryStr.=" WHERE F.Gatecode".$operation.$gateCode;
		
			//----------Pagination
			if($Pagination==null){
				$QueryStr.=" ORDER BY F.Cotag ";
			}else{
				if(isset($Pagination['Sort'])){
					$QueryStr.=" ORDER BY F.{$Pagination['Sort']} ";
					if(isset($Pagination['Order']))
						$QueryStr.=" {$Pagination['Order']} ";
				}
				if(isset($Pagination['Offset'])){
					$QueryStr.=" Limit {$Pagination['Offset']} ";
					if(isset($Pagination['Limit']))
						$QueryStr.=", {$Pagination['Limit']} ";
				}
			}
			$r=j::ODQL($QueryStr);
			return $r;
		}
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
	
	public function GetFilesWithLastProgress($ProgressName,$Offset=0,$Limit=100,$Sort="Cotag", $Order="ASC")
	{
		$r=j::ODQL("SELECT F FROM ReviewFile AS F JOIN F.Progress AS P
								WHERE
									P.ID=(SELECT MAX(P3.ID) FROM ReviewProgress AS P3 WHERE P3.File=F)
									 AND P INSTANCE OF ReviewProgress{$ProgressName}   
								ORDER BY F.{$Sort} {$Order} LIMIT {$Offset},{$Limit}");
		return $r;
	}
	
	/**
	 * 
	 * برای این که در فایل جدید مطمئن شویم ک این  کوتاژ قبلا در سال جاری وصول نشده 
	 * @param integer $Cotag
	 * @return ReviewFile
	 */
	public function GetRecentFile($Cotag,$gateCode)
	{
		$r=j::ODQL("SELECT F FROM ReviewFile AS F WHERE F.Cotag=? AND F.Gatecode=?",$Cotag,$gateCode);
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
	 * @param string $StateName
	 * @return array of ReviewFile
	 */
	

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
		$StateNumber=FsmGraph::Name2State($StateName);
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
	public function RecievedInRange($start=0,$end=0,$GateCode='')
	{
		if($GateCode=='')
			$GateCode=GateCode;
		
		$r=j::DQL("SELECT F.Cotag FROM ReviewFile AS F
				WHERE  F.CreateTimestamp > ?  AND F.Cotag BETWEEN {$start} AND {$end} AND F.Gatecode=?",time()-(365*24*60*60),$GateCode);
		return $r;
	}
	
	public function ProgressList($File)
	{
		//TODO think more
		//TODO: where file and type must be added
		$r=j::ODQL("SELECT P FROM ReviewProgress AS P WHERE P.File=?",$File);
		return $r;
	}
	
	
	public function CotagCount($GateCode='')
	{
		if($GateCode=='')
			$GateCode=GateCode;
		
		$r=j::DQL("SELECT COUNT(F) AS Result FROM ReviewFile AS F WHERE F.Gatecode=?",$GateCode);
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
		//state name='CotagStart'
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