<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ثبت ابلاغ
 * @Entity
 * @entity(repositoryClass="ReviewProcessProphecyRepository")
 * */
class ReviewProcessProphecy extends ReviewProgress
{
	///--------------------///
	///protected SubManner = 
	///-------------------///
	
	///--------------------///
	///protected MailNum = شماره نامه مرجع ابلاغ کننده
	///-------------------///
	
	/**
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $ProphecyTimestamp;
	function ProphecyTimestamp(){ return $this->ProphecyTimestamp; }
	function ProphecyTime(){
		$jc=new CalendarPlugin();
		return $jc->JalaliFullTime($this->ProphecyTimestamp);
	}
	function SetProphecyTimestamp($Timestamp){ $this->ProphecyTimestamp=$Timestamp; }
	function CheckProphecyTimestamp(){
		if($this->ProphecyTimestamp > time())
			return false;
		else
			return true;
	}
	/**
	 * ثبت ابلاغ اولیه
	 * @var string
	 */
	const SubManner_first = 'first';
	/**
	 * ثبت ابلاغ ثانویه
	 * @var string
	 */
	const SubManner_second = 'second';
	/**
	 * ثبت ابلاغ رای دفاتر ستادی
	 * @var string
	 */
	const SubManner_setad = 'setad';
	/**
	 * ثبت ابلاغ رای کمیسیون
	 * @var string
	 */
	const SubManner_commission = 'commission';
	
	
	function SubMannerValidation(){
		$val = $this->SubManner();
		$r = false;
		$r |= ($val == self::SubManner_first );
		$r |= ($val == self::SubManner_second );
		$r |= ($val == self::SubManner_setad );
		$r |= ($val == self::SubManner_commission );
		return $r;
	}
	
	function __construct(ReviewFile $File=null,$SubManner=null,$MailNum=null, $ProphecyTimestamp, MyUser $User=null)
	{
		parent::__construct($File,$User);
		$this->setMailNum($MailNum);
		$this->SetSubManner($SubManner);
		$this->SetProphecyTimestamp($ProphecyTimestamp);
	}

	function  Summary()
	{
		$jc = new JalaliCalendar();
		$pd = $jc->JalaliFromTimestamp($this->ProphecyTimestamp);
		return 'شماره نامه مرجع ابلاغ کننده: '.v::b($this->MailNum()).'. تاریخ ابلاغ نامه: '.v::b($pd);
	}
	function Title()
	{
		return 'ثبت نسخه ابلاغ شده';
	}
	function Manner()
	{
		return 'Prophecy_'.$this->SubManner();
	}
}


use \Doctrine\ORM\EntityRepository;
class ReviewProcessProphecyRepository extends EntityRepository
{
	/**
	 *
	 * Enter description here ...
	 * @param ReviewFile $File
	 * @return string on error object on sucsess
	 */
	public function AddToFile(ReviewFile $File, $SubManner ,$MailNum, $ProphecyTimestamp, $Comment=null)
	{
		
		$R=new ReviewProcessProphecy($File,$SubManner,$MailNum, $ProphecyTimestamp);
		$R->setComment(($Comment==null?"":$Comment));
		if(!$R->SubMannerValidation())
			return v::Edb();
		if(!$R->CheckProphecyTimestamp())
			return v::Ednv();
		$er=$R->Check();
		if(is_string($er))
			return $er;
			
		$R->Apply();
		ORM::Persist($R);
		return $R;
	}
	function GetLastProphecy(ReviewFile $File, $SubManner='first'){
		$r = j::ODQL('SELECT P FROM ReviewProcessProphecy P join P.File F WHERE F=? AND P.SubManner=?',$File,$SubManner);
		if(count($r))
			return $r[0];
		else 
			return null;
	}
}