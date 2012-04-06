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
	function __construct(ReviewFile $File=null,$IsPrint=false, $IfPersist=true)
	{
		if($IsPrint!=null)
			$this->IsPrint=$IsPrint;
		else 
			$this->IsPrint=false;
		
		parent::__construct($File, null, $IfPersist);

	}
	function  Summary()
	{
		return "اظهارنامه توسط دفتر کوتاژ وصول گردید.";	
	}
	
	function Title()
	{
		return "وصول دفترکوتاژ";
	}
	function Event()
	{
		return "Start";
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
	public function AddToFile($Cotag,$IsPrint=false)
	{
		if(b::CotagValidation($Cotag)==false)
		return "کوتاژ ناصحیح است.";
//		else if(! j::CallService("https://10.32.0.19/server/service/review/info", "ReviewInfo",array("Cotag"=>$Cotag)))
//		{
//			$Error="کوتاژ در سرور اسیکودا ثبت نشده , خطا در ارتباط با سرور اسیکودا .";
//			return $Error;
//		}
		
		$File=ORM::Query(new ReviewFile)->GetRecentFile($Cotag);
		if($File==null)
		{
			$File=new ReviewFile($Cotag);
			ORM::Persist($File);
			$start=new ReviewProgressStart($File,$IsPrint, false);
			$ch=$start->Check();
			if(is_string($ch))
				return $ch;
			
			$start= new ReviewProgressStart($File, $IsPrint, true);
			$start->Apply();
			ORM::Persist($start);
			   
			return $start;
		}
		else
		{
			$Error="اظهارنامه قبلا وصول گردیده است!";
			return $Error;
		}
		
	}
	public function CancelCotag($Cotag)
	{
		if(b::CotagValidation($Cotag)==false)
		{
			$Error=" کوتاژ ناصحیح است.کوتاژ باید هفت رقمی باشد.";
			return $Error;
		}
		$Cotag=$Cotag*1;
		if ($Cotag<1)
		{
			$Error="کوتاژ ناصحیح است.";
			return $Error;
		}
		else
		{
			$File=ORM::Query(new ReviewFile)->GetRecentFile($Cotag);
			if ($File==null)
			{
				$Error="این کوتاژ وصول نشده است.";
				return $Error;
			}
			else 
			{
				$LastProg=$File->LLP();
				if(!($LastProg instanceof ReviewProgressStart))
				{
					$Error="شما اجازه لغو این کوتاژ را ندارید.";
					return $Error;
				}
				else if($LastProg instanceof ReviewProgressStart)
				{
					$thisUser=MyUser::CurrentUser();
					ORM::Delete($LastProg);
					$A=$File->Alarm();
					if(count($A))
					{
						foreach ($A as $a)
							ORM::Delete($a);
					}
					ORM::Delete($File);
					return true;
				}
				else 
				{
					$Error="احتمالا خطایی در سیستم رخ داده. با مسئولین نرم افزار تماس بگیرید";
					return $Error;
				}
			}
		}
	}
	public function Prints($f,$l)
	{
		$r=j::ODQL("SELECT F.Cotag FROM ReviewProgressStart  AS P JOIN P.File AS F WHERE P.IsPrint=FALSE AND P.CreateTimestamp BETWEEN ? AND ? ",$f,$l);
		return $r;
	}
	public function DailyStart($days=30)
	{
		$r=j::SQL("SELECT COUNT(P.ID) as count,DATE(FROM_UNIXTIME(P.CreateTimestamp))as date,DATEDIFF(DATE(NOW()),FROM_UNIXTIME(P.CreateTimestamp))as day FROM app_ReviewProgress AS P WHERE P.Type='Start' GROUP BY DATE(FROM_UNIXTIME(P.CreateTimestamp))");
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
