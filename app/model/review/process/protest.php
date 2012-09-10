<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @entity(repositoryClass="ReviewProcessProtestRepository")
 * */
class ReviewProcessProtest extends ReviewProgress
{
	///--------------------///
	///protected SubManner = 
	///-------------------///
	
	///--------------------///
	///protected MailNum = اندیکاتور تخصیص شده در دبیرخانه
	///-------------------///
	/**
	 * اعتراض به مطالبه نامه
	 * @var string
	 */
	const SubManner_first = 'first';
	/**
	 * اعتراض به نظر کارشناس
	 * @var string
	 */
	const SubManner_second = 'second';
	/**
	 * اعتراض به رای دفاتر ستادی
	 * @var string
	 */
	const SubManner_setad = 'setad';
	/**
	 * اعتراض به رای کمیسیون
	 * @var string
	 */
	const SubManner_commission = 'commission';
	/**
	 * اعتراض بعد از ماده هفت شدن
	 * @var string
	 */
	const SubManner_after_p7 = 'after_p7';
	
	/**
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $ProtestTimestamp;
	function ProtestTimestamp(){ return $this->ProtestTimestamp; }
	function ProtestTime(){
		$jc=new CalendarPlugin();
		return $jc->JalaliFullTime($this->ProtestTimestamp);
	}
	function SetProtestTimestamp($Timestamp){ $this->ProtestTimestamp=$Timestamp; }
	function CheckProtestTimestamp(){
		if($this->ProtestTimestamp > time())
			return false;
		else
			return true;
	}
	/**
	 * *ProphecyTimestamp*|---Moratorium---|---Provisory---|
	 * چک می کند که آیا مهلت قانونی اعتراض گذشته است یا نه
	 * اگر زمان حال در بین بازه مهلت و احتیاط باشد می تواند ثبت اعتراض کند وگرنه پرونده در لیست ماده ۷ است
	 * با گذشتن از شرط بالا اگر تاریخ اعتراض در بین بازه مهلت باشد می تواند ثبت شود وگرنه پرونده به لیست ماده ۷ خواهد رفت
	 * @uses Moratorium(), Provisory()
	 */
	function CheckIfPossible(){
		$sm = $this->SubManner();
		if($sm==self::SubManner_after_p7)
			return true;
		$mora = $this->Moratorium();// gets the moratorium from database in configmain
		$provisory = $this->Provisory();
		$lastProphecy = ORM::Query('ReviewProcessProphecy')->GetLastProphecy($this->File, $sm);
		//ORM::Dump($lastProphecy);
		if($lastProphecy){
			$ProphecyTimestamp = $lastProphecy->ProphecyTimestamp();
			if(time() < $ProphecyTimestamp+$mora+$provisory){ // can register protest
				if($this->ProtestTimestamp < $ProphecyTimestamp+$mora)
					return true;
				else 
					return false;	
			}
			else
				return false;
		}else 
			return true;
	}
	/**
	 * @return string
	 */
	private function MoraLable(){
		return 'Protest_'.$this->SubManner().'_moratorium';
	}
	private function ProvisoryLabel(){
		return 'Protest_'.$this->SubManner().'_provisory';
	}
	/**
	 * مهلت اعتراض
	 * @throws Exception
	 * @return number
	 */
	private function Moratorium(){
		$mora = ConfigMain::GetValue($this->MoraLable()); // gets the moratorium of the protest ex: 32, 7
		if(!$mora)
			throw new Exception($this->MoraLable().' in configmain is not set!');
		
		return $mora*TIMESTAMP_DAY;
	}
	/**
	 * مهلت احتیاطی
	 * @throws Exception
	 */
	private function Provisory(){
		$pro = ConfigMain::GetValue($this->ProvisoryLabel()); // gets the moratorium of the protest ex: 32, 7
		if(!$pro)
			throw new Exception($this->ProvisoryLabel().' in configmain is not set!');
		return $pro*TIMESTAMP_DAY;
	}
	
	
	function SubMannerValidation(){
		$val = $this->SubManner();
		$r = false;
		$r |= ($val == self::SubManner_first );
		$r |= ($val == self::SubManner_second );
		$r |= ($val == self::SubManner_setad );
		$r |= ($val == self::SubManner_commission );
		$r |= ($val == self::SubManner_after_p7 );
		return $r;
	}
	function __construct(ReviewFile $File=null,$SubManner,$Indicator='', $ProtestTimestamp, MyUser $User=null)
	{
		parent::__construct($File,$User);
		$this->setMailNum($Indicator);
		$this->SetSubManner($SubManner);
		$this->SetProtestTimestamp($ProtestTimestamp);
	}

	function  Summary()
	{
		
		return 'اندیکاتور دبیرخانه: '.$this->MailNum().'. تاریخ ثبت اعتراض در دبیرخانه: '.$this->ProtestTime();
	}
	function Title()
	{
		return 'اعتراضیه';
	}
	function Manner()
	{
		return 'Protest_'.$this->SubManner();
	}
	
}


use \Doctrine\ORM\EntityRepository;
class ReviewProcessProtestRepository extends EntityRepository
{
	/**
	 *
	 * Enter description here ...
	 * @param ReviewFile $File
	 * @return string on error object on sucsess
	 */
	public function AddToFile(ReviewFile $File,$SubManner, $Indicator, $ProtestTimestamp, $Comment=null)
	{
		$R=new ReviewProcessProtest($File,$SubManner,$Indicator, $ProtestTimestamp);
		$R->setComment($Comment);
		if(!$R->SubMannerValidation())
			return v::Edb();
		if(!$R->CheckProtestTimestamp())
			return v::Ednv();
		if(!$R->CheckIfPossible())
			return 'مهلت اعتراض قانونی گذشته است. پرونده در لیست '.p::P7.' یا در لیست انتظار قرار دارد.';
		$er=$R->Check();
		if(is_string($er))
			return $er;
		
		$R->Apply();
		ORM::Persist($R);
		return $R;

	}
}