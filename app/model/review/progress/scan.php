<?php
/** 
 * @Entity
 * @entity(repositoryClass="ReviewProgressScanRepository")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="Type", type="string")
 * */
class ReviewProgressScan extends ReviewProgress
{

	function __construct(ReviewFile $File=null, $IfPersist=true)
	{
		
		parent::__construct($File, null, $IfPersist);

	}
	function  Summary()
	{
		return "اظهارنامه اسکن شد.";	
	}
	
	function Title()
	{
		return "اسکن";
	}
	function Event()
	{
		return "Scan";
	}
}
use \Doctrine\ORM\EntityRepository;
class ReviewProgressScanRepository extends EntityRepository
{
	/**
	 * 
	 * @param integer $Cotag
	 * @return string for error object for true;
	 */
	public function AddToFile($Cotag)
	{
		if(b::CotagValidation($Cotag)==false)
		return v::Ecnv();
//		else if(! j::CallService("https://10.32.0.19/server/service/review/info", "ReviewInfo",array("Cotag"=>$Cotag)))
//		{
//			$Error="کوتاژ در سرور اسیکودا ثبت نشده , خطا در ارتباط با سرور اسیکودا .";
//			return $Error;
//		}
		
		$File=ORM::Query(new ReviewFile)->GetRecentFile($Cotag);
		if($File==null)
		{
			$File=new ReviewFile($Cotag);
			ORM::Write($File);
			$start=new ReviewProgressScan($File, false);
			$ch=$start->Check();
			if(is_string($ch))
				return $ch;
			
			$start= new ReviewProgressScan($File, true);
			$start->Apply();
			ORM::Write($start);
			   
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
			return v::Ecnv();
		}
		$Cotag=$Cotag*1;
		
		$File=ORM::Query(new ReviewFile)->GetRecentFile($Cotag);
		if ($File==null)
		{
			$Error=v::Ecnf();
			return $Error;
		}
			
		$LastProg=$File->LLP();
		if(!($LastProg instanceof ReviewProgressScan))
		{
			$Error="شما اجازه لغو این کوتاژ را ندارید.";
			return $Error;
		}
		else if($LastProg instanceof ReviewProgressScan)
		{
			$thisUser=MyUser::CurrentUser();
			ORM::Delete($LastProg);
			$A=$File->Alarm();
			if(count($A))
			{
				foreach ($A as $a)
					ORM::Delete($a);
			}
			ORM::Query("ReviewImages")->deleteImage($LastProg);
			ORM::Delete($File);
			return true;
		}
		else 
		{
			$Error="احتمالا خطایی در سیستم رخ داده. با مسئولین نرم افزار تماس بگیرید";
			return $Error;
		}
	}
	
	public function UntiEbtalCotag($Cotag)
	{
		if(b::CotagValidation($Cotag)==false)
			return v::Ecnv();
		
		$File=ORM::Query(new ReviewFile)->GetRecentFile($Cotag);
		if($File==null)
			return "اظهارنامه در سیستم ثبت نگردیده است!";
		
		$start=new ReviewProgressScan($File,false,false);
		$ch=$start->Check();
		if(is_string($ch))
			return $ch;
   		
		$start=new ReviewProgressScan($File,false,true);
		$ch=$start->Apply();
		ORM::Persist($start);
		return true;
	}
	public function Prints($f,$l)
	{
		$r=j::ODQL("SELECT F.Cotag FROM ReviewProgressScan  AS P JOIN P.File AS F WHERE P.IsPrint=FALSE AND P.CreateTimestamp BETWEEN ? AND ? ",$f,$l);
		return $r;
	}
	
	public function DailyScan($days=30)
	{
		$r=j::SQL("SELECT COUNT(P.ID) as count,DATE(FROM_UNIXTIME(P.CreateTimestamp))as date,DATEDIFF(DATE(NOW()),FROM_UNIXTIME(P.CreateTimestamp))as day FROM app_ReviewProgress AS P WHERE P.Type='Scan' GROUP BY DATE(FROM_UNIXTIME(P.CreateTimestamp))");
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
	
	
	public function HourlyScan()
	{
		$r=j::SQL("SELECT COUNT(P.ID) as count,HOUR(FROM_UNIXTIME(P.CreateTimestamp))as hour FROM App_ReviewProgress AS P WHERE P.type='Scan' GROUP BY HOUR(FROM_UNIXTIME(P.CreateTimestamp))");
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
