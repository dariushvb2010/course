<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ارسال اظهارنامه به دفاتر ستادی یا کمیسیون یا کمیسیون تجدید نظر
 * @Entity
 * @entity(repositoryClass="ReviewProcessForwardRepository")
 * */
class ReviewProcessForward extends ReviewProgress
{
	///--------------------///
	///protected $SubManner = 'setad', 'commission', 'appeals'
	///-------------------///
	/**
	 * ارسال به دفاتر ستادی
	 * @var string
	 */
	const SubManner_setad = 'setad';
	/**
	 * ارسال به کمیسیون
	 * @var string
	 */
	const SubManner_commission = 'commission';
	/**
	 * ارسال به کمیسیون تجدید نظر
	 * @var string
	 */
	const SubManner_appeals = 'appeals';
	
	/**
	 * @ManyToOne(targetEntity="ReviewTopic",inversedBy="ProcessForward")
	 * @JoinColumn(name="ForwardSetadID",referencedColumnName="ID", nullable=false)
	 * @var ReviewTopic
	 */
	protected $Setad;
	function Setad(){ return $this->Setad; }
	function SetSetad($val){ $this->Setad = $val; ORM::Dump($this->Setad());}
	
	function SubMannerValidation(){
		$val = $this->SubManner();
		$r = false;
		$r |= ($val == self::SubManner_setad );
		$r |= ($val == self::SubManner_commission );
		$r |= ($val == self::SubManner_appeals );
		return $r;
	}
	function __construct(ReviewFile $File=null,$SubManner,$MailNum='', ReviewTopic $Setad,MyUser $User=null)
	{
		parent::__construct($File,$User);
		$this->SetMailNum($MailNum);
		$this->SetSubManner($SubManner);
		if($Setad)
			$this->SetSetad($Setad);
		
	}

	function  Summary()
	{
		if($this->Setad())
			$t = $this->Setad()->Topic();
		return 'پرونده به '.$t.' فرستاده شد.';
		
	}
	function Title()
	{
		return 'ارسال پرونده';
	}
	function Manner()
	{
		return 'Forward_'.$this->SubManner;
	}

}


use \Doctrine\ORM\EntityRepository;
class ReviewProcessForwardRepository extends EntityRepository
{
	/**
	 *
	 * Enter description here ...
	 * @param ReviewFile $File
	 * @return string on error object on sucsess
	 */
	public function AddToFile(ReviewFile $File, $SubManner,$MailNum, $SetadID ,$Comment="")
	{
	
		$Setad = ORM::Find('ReviewTopic', $SetadID);
		if(!($Setad instanceof ReviewTopic))
			return 'طرف مکاتبه مورد نظر یافت نشد.';
		$R=new ReviewProcessForward($File,$SubManner, $MailNum, $Setad);
		$R->setComment($Comment);
		$er = $R->Check();
		if(is_string($er))
			return $er;
		
		$R->Apply();
		ORM::Persist($R);
			
		
	}
}