<?php
/**
 * @Entity
 * @entity(repositoryClass="ReviewProgressStartRepository")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="Type", type="string")
 * */
class ReviewProgressStart extends ReviewProgress
{
	/**
	 * @Column(type="boolean")
	 * @var boolean
	 */
	protected $IsPrint;
	function IsPrint()
	{
		return $this->IsPrint;
	}
	/**
	 * @ManyToOne(targetEntity="MyGroup")
	 * @JoinColumn(name="StartGroupID", referencedColumnName="ID", nullable="false")
	 * 
	 * to distiguish the file next state, we will need this var
	 * @see $this->Event()
	 * @example only three Groups: cotagbook, archive, raked
	 * @var MyGroup
	 
	 */
	protected $StartGroup;
	function StartGroup(){ return $this->StartGroup; }
	function SetStartGroup(MyGroup $G){
		$this->StartGroup=$G;
	}
	function __construct(ReviewFile $File=null,$IsPrint=false, $IfPersist=true, MyGroup $StartGroup=null)
	{	
		if($IsPrint!=null)
			$this->IsPrint=$IsPrint;
		else
			$this->IsPrint=false;
		if($StartGroup==null)
			$this->StartGroup = MyGroup::CotagBook();
		else 
		{
			$this->StartGroup = $StartGroup;
		}
		parent::__construct($File, null, $IfPersist);

	}
	function  Summary()
	{
		$sgn = $this->StartGroup()->Title();
		return "اظهارنامه توسط ".v::b(ConfigData::$GROUPS[$sgn])." در سیستم ثبت شد. ";
	}

	function Title()
	{
		return "ورود اظهارنامه";
	}
	function Event()
	{
		$sgn = $this->StartGroup()->Title(); // startGroup Title
		if(!isset($sgn))
			throw new Exception("start event has not been set!");
		
		return "Start_".strtolower($sgn);
	}
}
use \Doctrine\ORM\EntityRepository;
class ReviewProgressStartRepository extends EntityRepository
{
	/**
	 *
	 * @param integer $Cotag
	 * @return string for error boolean for true;
	 */
	public function AddToFile($Cotag,$IsPrint=false,$StartGroup =null)
	{
		//ORM::Dump($StartGroup);die();
		if(b::CotagValidation($Cotag)==false)
			return "کوتاژ ناصحیح است.";
		//		else if(! j::CallService("https://10.32.0.19/server/service/review/info", "ReviewInfo",array("Cotag"=>$Cotag)))
			// 					{
			// 						$Error="کوتاژ در سرور اسیکودا ثبت نشده , خطا در ارتباط با سرور اسیکودا .";
// 									return $Error;
			// 					}

		$File=ORM::Query(new ReviewFile)->GetRecentFile($Cotag);
		if($File==null)
		{
			$File=new ReviewFile($Cotag);
			ORM::Persist($File);
		}
		$start=new ReviewProgressStart($File,$IsPrint, false, $StartGroup);
		$ch=$start->Check();
		if(is_string($ch))
		{
			return $ch;
		}

		$start= new ReviewProgressStart($File, $IsPrint, true, $StartGroup);
		$start->Apply();
		ORM::Persist($start);

		return $start;


	}
	/**
	 * 
	 * @param unknown $Cotag
	 * @return array (['result']=>bool, ['message']=>string( Error or Result) )
	 */
	public function CancelCotag($Cotag)
	{
		$ret = array(); // the return value
		$Cotag = b::BakeCotag($Cotag);
		if($Cotag == false)
		{
			$ret['result'] = false;
			$ret['message']="کوتاژ ناصحیح است.کوتاژ باید هفت رقمی باشد.";
			return $ret;
		}
		
		$resLast = "وصول اظهارنامه با شماره کوتاژ ".v::bgc($Cotag)."لغو شد.";
		$resMessage = "";
		$File=ORM::Query("ReviewFile")->GetRecentFile($Cotag);
		if ($File==null)
		{
			$ret['result'] = false;
			$ret['message'] = "این کوتاژ وصول نشده است.";
			return $ret;
		}
		
		$LastProg=$File->LLP();
		$progressCount = count($File->Progress());
		if(!($LastProg instanceof ReviewProgressStart or $progressCount==0))
		{
			$ret['result'] = false;
			$ret['message'] = "شما اجازه لغو این کوتاژ را ندارید." ;
			return $ret;
		}
		else if($LastProg instanceof ReviewProgressStart or  $progressCount == 0)
		{
			$thisUser=MyUser::CurrentUser();
			if($progressCount!=0)
			{
				ORM::Delete($LastProg);
				$resMessage = "فرآیند حذف شد. ".$resMessage;
			}
			else 
				$resMessage = "هیچ فرآیندی برای این کوتاژ ثبت نشده است! --".$resMessage;
			//------delete Alarms-----
			$A=$File->Alarm();
			if(count($A))
			{
				$resMessage = "به تعداد ".count($A)." هشدار حذف شد. ".$resMessage;
				foreach ($A as $a)
					ORM::Delete($a);
			}//---------------------
			//-----delete Stock-------
			$S = $File->Stock();
			if(isset($S))
			{
				$resMessage = " اظهارنامه از نامه حذف شد. ".$resMessage;
				ORM::Delete($S);
			}//---------------------
			ORM::Delete($File);
			$ret['result'] = true;
			$ret['message'] = $resMessage." - ".$resLast;
			return $ret;
		}
		else
		{
			$ret['result'] = false;
			$ret['message'] = "احتمالا خطایی در سیستم رخ داده. با مسئولین نرم افزار تماس بگیرید";
			return $ret;
		}
	}

	public function UntiEbtalCotag($Cotag)
	{
		if(b::CotagValidation($Cotag)==false)
			return "کوتاژ ناصحیح است.";

		$File=ORM::Query(new ReviewFile)->GetRecentFile($Cotag);
		if($File==null)
			return "اظهارنامه در سیستم ثبت نگردیده است!";

		$start=new ReviewProgressStart($File,false,false);
		$ch=$start->Check();
		if(is_string($ch))
			return $ch;
			
		$start=new ReviewProgressStart($File,false,true);
		$ch=$start->Apply();
		ORM::Persist($start);
		return true;
	}
	public function Prints($f,$l)
	{
		$r=j::ODQL("SELECT F.Cotag FROM ReviewProgressStart  AS P JOIN P.File AS F WHERE P.IsPrint=FALSE AND P.CreateTimestamp BETWEEN ? AND ? ",$f,$l);
		return $r;
	}

	public function DailyStart($days=30)
	{
		$r=j::SQL("SELECT COUNT(P.ID) as count,DATE(FROM_UNIXTIME(P.CreateTimestamp))as date,DATEDIFF(DATE(NOW()),FROM_UNIXTIME(P.CreateTimestamp))as day FROM app_ReviewProgress AS P WHERE P.Type='Start' GROUP BY DATE(FROM_UNIXTIME(P.CreateTimestamp))");
		if(is_array($r))
			while(count($r)){
			$t=array_pop($r);
			$day=$t['day'];
			$res[$day*1]=$t;
		}
		for($i=0;$i<=$days;$i++){
			if(!array_key_exists($i,$res)){
				$res[$i]=array('count'=>0,'date'=>date("Ymd",time()-60*60*24*$i),'day'=>$i);
			}
		}
		for($i=$days;$i>=0;$i--){
			$res2[]=$res[$i];
		}
		return $res2;
	}


	public function HourlyStart()
	{
		$r=j::SQL("SELECT COUNT(P.ID) as count,HOUR(FROM_UNIXTIME(P.CreateTimestamp))as hour FROM App_ReviewProgress AS P WHERE P.type='Start' GROUP BY HOUR(FROM_UNIXTIME(P.CreateTimestamp))");
		while(count($r)){
			$t=array_pop($r);
			$hour=$t['hour'];
			$res[$hour]=$t;
		}
		for($i=0;$i<23;$i++){
			if(!array_key_exists($i,$res)){
				$res[$i]=array('count'=>0,'hour'=>$i);
			}
		}
		for($i=0;$i<23;$i++){
			$res2[]=$res[$i];
		}
		return $res2;
	}
	}
