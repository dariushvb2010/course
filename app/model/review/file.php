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
    function SetStock(FileStock $Stock){ $this->Stock=$Stock; }
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
    		else
    		return false;
    	}
    	else 
    	return false;
    }
    function __construct($Cotag=null)
    {
    	$this->CreateTimestamp=time();
    	$this->FinishTimestamp=0;
		$this->Cotag=$Cotag;    		
    	$this->Progress= new ArrayCollection();
    	$this->Alarm=new ArrayCollection();
    	$this->Class=0;
    	$this->Gatecode=50100;
    	$this->State=1;
    	
    }
    
    public function Finish()
    {
    	$thisUser=ORM::Find("MyUser", j::UserID());
		$p=new ReviewProgressFinish($this,$thisUser);
		ORM::Persist($p);
    	$this->FinishTimestamp=time();
    }
    static function Regulate($Files)
    {
    	$res=array();
    	if($Files)
    	foreach ($Files as $File)
    	{
    		$res[]=self::GetRecentFile($File);
    	}
    	return $res;
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
		if ($ProgAssign->Reviewer()==null)return null;
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
			ORDER BY P.CreateTimestamp DESC LIMIT 1 ",$Cotag);
		else 
			$r=j::ODQL("SELECT P FROM ReviewProgress AS P join P.File AS F WHERE P.Dead=0 AND F.Cotag= ?
			ORDER BY P.CreateTimestamp DESC LIMIT 1 ",$Cotag);
		return $r[0];
	}
	public function GetOnlyProgressStart($Offset=0,$Limit=100,$Sort="Cotag", $Order="ASC")
	{
		$r=j::DQL("SELECT F FROM ReviewFile AS F JOIN F.Progress AS P
						WHERE P.CreateTimestamp=
							(SELECT MAX(P2.CreateTimestamp) FROM ReviewProgress AS P2 WHERE P2.File=F)
							 AND P INSTANCE OF ReviewProgressStart    
						ORDER BY F.{$Sort} {$Order} LIMIT {$Offset},{$Limit}");
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
	public function GetFilesWithLastProgress($Progress,$Offset=0,$Limit=100,$Sort="Cotag", $Order="ASC")
	{
		$r=j::ODQL("SELECT F FROM ReviewFile AS F JOIN F.Progress AS P
								WHERE P.CreateTimestamp=
									(SELECT MAX(P2.CreateTimestamp) FROM ReviewProgress AS P2 WHERE P2.File=F)
									 AND P INSTANCE OF ReviewProgress{$Progress}   
								ORDER BY F.{$Sort} {$Order} LIMIT {$Offset},{$Limit}");
		return $r;
	}
	public function GetMaxID()
	{
		$r=j::DQL("SELECT MAX(F.ID) AS Result FROM ReviewFile AS F");
		return $r[0]['Result'];	
	}

	public function GetUnfinishedList($Offset=0,$Limit=100,$Sort="Cotag",$Order='ASC')
	{
		$r=j::DQL("SELECT F FROM ReviewFile AS F WHERE F.FinishTimestamp=0 
			ORDER BY F.{$Sort} {$Order} LIMIT {$Offset},{$Limit}");
		return $r;
			
	}
	public function DeleteAll()
	{
		j::DQL("DELETE ReviewInfo");
		j::DQL("DELETE ReviewCorrespondence");
		j::DQL("DELETE ReviewFile");
	}	
	public function GetCount()
	{
		$r=j::DQL("SELECT COUNT(F.ID) AS Result FROM ReviewFile F");
		return $r[0]['Result'];
	}
	public function GetFinishedCount()
	{
		
		$r=j::DQL("SELECT COUNT(F) AS Result FROM ReviewFile AS F WHERE F.FinishTimestamp!=0");
		return $r[0]['Result'];
	}
	public function GetUnfinishedCount()
	{
		$r=j::DQL("SELECT COUNT(F) AS Result FROM ReviewFile AS F WHERE F.FinishTimestamp=0");
		return $r[0]['Result'];
		
	}
	
	public function GetRecentFiles($Cotag)
	{
		$r=j::ODQL("SELECT F FROM ReviewFile AS F WHERE F.Cotag=? ORDER BY F.CreateTimestamp DESC",$Cotag);
		return $r;
	}
	/**
	 * 
	 * برای این که در فایل جدید مطمئن شویم ک این  کوتاژ قبلا در سال جاری وصول نشده 
	 * @param unknown_type $Cotag
	 */
	public function GetRecentFile($Cotag)
	{
		$r=ORM::Query("ReviewFile")->GetRecentFiles($Cotag);
		if(count($r))
			return $r[0];
	}
	/**
	 *
	 * برای استفاده در تخصیص بازه ای و تخصیص از لیست 
	 * که فایل های قدیمی مختومه را بر نمی گرداند 
	 * @param unknown_type $From
	 * @param unknown_type $Limit
	 */
	public function UnassignedFilesInRange($RangeStart,$RangeEnd)
	{
		$r=j::ODQL("SELECT F FROM ReviewFile AS F 
					WHERE F.ID NOT IN (SELECT G.ID FROM ReviewFile AS G JOIN G.Progress AS P WHERE P INSTANCE OF ReviewProgressAssign  AND G.Cotag BETWEEN ? AND ? )
					AND F.Cotag BETWEEN ? AND ?
					AND F.ID IN (SELECT G1.ID FROM ReviewFile AS G1 JOIN G1.Progress AS P1 WHERE P1 INSTANCE OF ReviewProgressRegisterarchive) 
					ORDER BY F.Cotag",$RangeStart,$RangeEnd,$RangeStart,$RangeEnd);
		return $r;
	}
	
	public function AssignedFilesInRange($RangeStart,$RangeEnd)
	{
		$r=j::ODQL("SELECT F FROM ReviewFile AS F 
					WHERE F.ID IN (SELECT G.ID FROM ReviewFile AS G JOIN G.Progress AS P WHERE P INSTANCE OF ReviewProgressAssign AND G.Cotag BETWEEN ? AND ? )
					AND F.Cotag BETWEEN ? AND ?
					ORDER BY F.Cotag",$RangeStart,$RangeEnd,$RangeStart,$RangeEnd);
		return $r;
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
	 * برای استفاده در تخصیص بازه ای و تخصیص از لیست 
	 * که فایل های قدیمی مختومه را بر نمی گرداند 
	 * @param unknown_type $From
	 * @param unknown_type $Limit
	 */
	public function UnassignedFiles($From=0,$Limit=0)
	{
		$r=j::DQL("SELECT F,P FROM ReviewFile AS F JOIN F.Progress AS P
						WHERE F.ID  IN (SELECT G.ID FROM ReviewFile AS G JOIN G.Progress AS P1 WHERE P1 INSTANCE OF ReviewProgressRegisterarchive)
						AND P.CreateTimestamp=(SELECT MAX(P2.CreateTimestamp) FROM ReviewProgress AS P2 WHERE P2.File=F)AND P INSTANCE OF ReviewProgressRegisterarchive
						ORDER BY F.Cotag LIMIT {$From},{$Limit}");
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
		$r=j::DQL("SELECT F.Cotag FROM ReviewFile AS F  WHERE  F.CreateTimestamp > ? AND
		(F.ID NOT IN (SELECT K.ID FROM ReviewProgressManual AS P JOIN P.File AS K ))  AND F.Cotag BETWEEN {$start} AND {$end}",time()-(365*24*60*60));
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
	public function FileWithLastProg($Cotag)
	{
		$r=j::ODQL("SELECT F FROM ReviewFile AS F WHERE F.Cotag=?",$Cotag);
		
		if($r)
		{
//			$res[]=$r[0];
			return $r;
		}	
		else return null;
	}
	
	public function ProgressList($File)
	{
		//TODO think more
		//TODO: where file and type must be added
		$r=j::ODQL("SELECT P FROM ReviewProgress AS P WHERE P.File=?",$File);
		return $r;
	}
		
	
	
	public function FinishableFiles($off,$lim,$sort,$ord)
	{
		$r1=j::DQL("SELECT F,P FROM ReviewProgressReview AS P JOIN P.File AS F WHERE P.Result=1 AND
				P.CreateTimestamp=(SELECT MAX(P2.CreateTimestamp) FROM ReviewProgress AS P2 WHERE P2.File=F)"
				.($sort?" ORDER BY F.{$sort} {$ord} ":"ORDER BY F.Cotag ASC ").($off?" LIMIT {$off},{$lim}":"")
		);
	
		$r2=j::DQL("SELECT F,P FROM ReviewProgressReceivefile AS P JOIN P.File AS F WHERE
						P.CreateTimestamp=(SELECT MAX(P2.CreateTimestamp) FROM ReviewProgress AS P2 WHERE P2.File=F)"
						.($sort?" ORDER BY F.{$sort} {$ord} ":"ORDER BY F.Cotag ASC ").($off?" LIMIT {$off},{$lim}":"")
						);
		foreach($r1 as $D)
			$f1[]=$D['File'];
		foreach($r2 as $D)
			$f2[]=$D['File'];
		
		$i=0;$j=0;
		$c1=count($f1);
		$c2=count($f2);
		$c=$c1+$c2;
		
		//Order By Cotag
		for($k=0;$k<$c;$k++){
			if($i>=$c1){
				$f3[]=$f2[$j];
				$j++;
			}elseif($j>=$c2){
				$f3[]=$f1[$i];
				$i++;
			}elseif($f1[$i]['Cotag']*1<$f2[$j]['Cotag']*1){
				$f3[]=$f1[$i];
				$i++;
			}else{
				$f3[]=$f2[$j];
				$j++;
			}
		}
		return $f3;
	}
	
	public function CotagCount()
	{
		$r=j::DQL("SELECT COUNT(F) AS Result FROM ReviewFile AS F");
		return $r[0]['Result'];
		
	}
	/**
	 *
	 * برای تحویل دفتر کوتاژ به بایگانی بازبینی
	 * @param unknown_type $TimeStart
	 * @param unknown_type $TimeEnd
	 */
	public function FilesInTimeRange($TimeStart,$TimeEnd,MyUser $User)
	{
		$r=j::ODQL("SELECT F,P FROM ReviewFile AS F JOIN F.Progress AS P 
					WHERE F.CreateTimestamp BETWEEN ? AND ? AND P.CreateTimestamp=(SELECT MAX(P2.CreateTimestamp)
					 FROM ReviewProgress AS P2 WHERE P2.File=F) AND P INSTANCE OF ReviewProgressStart    ORDER BY F.Cotag",$TimeStart,$TimeEnd);
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