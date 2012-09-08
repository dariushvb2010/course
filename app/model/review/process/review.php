<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="ProcessReview")
 * @entity(repositoryClass="ReviewProcessReviewRepository")
 * */
class ReviewProcessReview extends ReviewProgress
{
	///--------------------///
	///protected SubManner =
	///-------------------///
	/**
	 * پذیرش اعتراض
	 * @var string
	 */
	const SubManner_accept='accept';
	/**
	 *	رد اعتراض
	 * @var string
	 */ 
	const SubManner_deny = 'deny';
	/**
	 *	تایید مکاتبات
	 * @var string
	 */
	const SubManner_ok = 'ok';
	/**
	 * کارشناسی و درخواست ارسال به دفاتر ستادی
	 * @var string
	 */
	const SubManner_setad = 'setad';
	
	static $SubMannerArray=array('accept','deny','ok','setad');
	
	/**
	 * @ManyToOne(targetEntity="ReviewTopic")
	 * @JoinColumn(name="TopicID",referencedColumnName="ID")
	 * @var ReviewTopic
	 */
	protected $Setad;
	function Setad(){ return $this->Setad; }
	function SetSetad($val){ $this->Setad = $val; }
	function SubMannerValidation(){
		$val = $this->SubManner();
		$r = false;
		$r |= ($val == self::SubManner_accept );
		$r |= ($val == self::SubManner_deny );
		$r |= ($val == self::SubManner_setad );
		$r |= ($val == self::SubManner_ok );
		return $r;
	}
	/**
	 * if you set the $setad memebr, the submanner must be 'setad'
	 */
	function CheckConsistensy(){
		if(($this->Setad() and $this->SubManner()!=self::SubManner_setad) or 
				(!$this->Setad() and $this->SubManner()==self::SubManner_setad))
			return false;
		else
			return true;
	}
	///--------------------///
	///protected MailNum = شماره صورت جلسه فنی
	///-------------------///
	function __construct(ReviewFile $File=null,$SubManner, $MailNum='', ReviewTopic $Setad=null, MyUser $User=null)
	{
		parent::__construct($File,$User);
		$this->SetSubManner($SubManner);
		$this->setMailNum($MailNum);
		if($Setad)
			$this->SetSetad($Setad);
	}
	
	function  Summary()
	{
		$fsmp = FsmGraph::GetProgressByName($this->Manner());
		return 'پرونده کارشناسی شد: '. $fsmp->Label;
	}
	function Title()
	{
		return 'رای کارشناس';
	}
	function Manner()
	{
		return 'ProcessReview_'.$this->SubManner();
	}
}


use \Doctrine\ORM\EntityRepository;
class ReviewProcessReviewRepository extends EntityRepository
{
	/**
	 * 
	 * Enter description here ...
	 * @param ReviewFile $File
	 * @return string on error object on sucsess
	 */
	public function AddToFile(ReviewFile $File, $SubManner, $MailNum='', $Comment='', ReviewTopic $Setad=null)
	{
		$R=new ReviewProcessReview($File,$SubManner, $MailNum, $Setad);
		$R->setComment($Comment);
		if(!$R->CheckConsistensy())
			return 'برای ارسال به دفاتر ستادی یکی از موارد را انتخاب نمایید.';
		$er = $R->Check();
		if(is_string($er))
			return $er;
		
		$R->Apply();
		ORM::Persist($R);
		return $R;
	}
}