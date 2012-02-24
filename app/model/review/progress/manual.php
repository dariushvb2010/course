<?php
/** 
 * @Entity
 * @entity(repositoryClass="ReviewProgressManualRepository")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="Type", type="string")
 * */
use Doctrine\DBAL\Types\IntegerType;
class ReviewProgressManual extends ReviewProgress
{
	function __construct(ReviewFile $File=null,MyUser $User=null,$FirstCreateTimestamp=null,$FinishTimestamp=null)
	{
		parent::__construct($File,$User);
		if ($FinishTimestamp) $this->FinishTimestamp=$FinishTimestamp;
		else $this->FinishTimestamp=0;
		if ($this->FirstCreateTimestamp) $this->FirstCreateTimestamp=($FirstCreatTimestamp);
		else $this->FirstCreateTimestamp=0;
		
	}
	
	/**
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $FinishTimestamp;
	function SetFinishTimestamp($Timestamp)
	{
		$this->FinishTimestamp=$Timestamp;
	}
	function FinishTimestamp()
	{
		return $this->FinishTimestamp;
	}
	function FinishTime()
	{
		$c=new CalendarPlugin();
		return $c->JalaliFromTimestamp($this->FinishTimestamp());
	}
	/**
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $FirstCreateTimestamp;
	function SetFirstCreateTimestamp($Timestamp)
	{
		$this->FirstCreateTimestamp=$Timestamp;
	}
	function FirstCreateTimestamp()
	{
		return $this->FirstCreateTimestamp;
	}
	function FirstCreateTime()
	{
		$c=new CalendarPlugin();
		return $c->JalaliFromTimestamp($this->FirstCreateTimestamp());
	}
	function  Summary()
	{
		return " اظهارنامه قدیمی به شماره کوتاژ".$this->File->Cotag()." در تاریخ ".$this->FirstCreateTime()." وصول گردیده و در  ".$this->FinishTime()."مختومه شده ثبت گردید.";	
	}
	
	function Title()
	{
		return"ثبت پرونده مختومه";
	}
}
use \Doctrine\ORM\EntityRepository;
class ReviewProgressManualRepository extends EntityRepository
{
	public function GetFileWithManual($Cotag,$CreatTime)
	{
		$r=j::ODQL("SELECT P FROM ReviewProgressManual AS P JOIN P.File AS F WHERE F.Cotag=? 
		AND P.FirstCreateTimestamp BETWEEN ? AND ? ",$Cotag,($CreatTime-(182*24*60*60)),($CreatTime+(182*24*60*60)));
		if($r)
			return $r[0];
		else 
			return null;
	}
	/**
	 * 
	 * Enter description here ...
	 * @param integer $Cotag
	 * @param array $CDate Georgian date
	 * @param array $FDate Georgian
	 * @return true for sucsess string for error
	 */
	public function AddManual($Cotag=null,$CDate=null,$FDate=null)
	{
		$CreateTimestamp=strtotime($CDate[0]."/".$CDate[1]."/".$CDate[2]);
		$FinishTimestamp=strtotime($FDate[0]."/".$FDate[1]."/".$FDate[2]);
		if ($Cotag<1)
		{
			$Error="کوتاژ ناصحیح است.";
			return $Error;
		}
		else 
		{
			$File=ORM::Query(new ReviewProgressManual())->GetFileWithManual($Cotag,$CreateTimestamp);
			if ($File==null)
			{
				$thisUser=MyUser::CurrentUser();
				$File=new ReviewFile($Cotag);
				ORM::Persist($File);
				$manual=new ReviewProgressManual($File,$thisUser,$CreateTimestamp,$FinishTimestamp);
				ORM::Persist($manual);
				return true;
			}
			else
			{
				$Error="اظهارنامه ای با همین کوتاژ در سال وصول داده شده  قبلا ثبت گردیده است!";
				return $Error;
			}
		}
	}

}