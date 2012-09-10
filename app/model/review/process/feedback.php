<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @entity(repositoryClass="ReviewProcessFeedbackRepository")
 * */
class ReviewProcessFeedback extends ReviewProgress
{
	///--------------------///
	///protected $SubManner = 'setad', 'commission', 'appeals'
	///-------------------///
	
	
	///--------------------///
	///protected $MailNum = شماره نامه
	///-------------------///
	
	/**
	 * دریافت از دفاتر ستادی
	 * @var string
	 */
	const SubManner_setad = 'setad';
	/**
	 * دریافت از کمیسیون
	 * @var string
	 */
	const SubManner_commission = 'commission';
	/**
	 * دریافت از کمیسیون تجدید نظر
	 * @var string
	 */
	const SubManner_appeals = 'appeals';
	/**
	 * @Column(type="string", length="20")
	 * @var string
	 *
	 */
	protected $FeedbackTo; 
	function FeedbackTo(){ return $this->FeedbackTo; }
	/**
	 * @OneToOne(targetEntity="ReviewProcessForward")
	 * @JoinColumn(name="ForwardID", referencedColumnName="ID")
	 * @var ReviewProcessForward
	 */
	protected $ProcessForward;
	function ProcessForward(){ return $this->ProcessForward; }
	function SetProcessForward($val){ $this->ProcessForward = $val; }
	/**
	 * به نفع صاحب کالا
	 * @var integer
	 */
	const To_owner = 'owner';
	/**
	 *  به نفع گمرک
	 * @var integer
	 */
	const To_gomrok = 'gomrok';
	
	function __construct(ReviewFile $File=null,$SubManner, $FeedbackTo,$MailNum, ReviewProcessForward $Forward,MyUser $User=null)
	{
		parent::__construct($File,$User);
		$this->setMailNum($value);
		$this->FeedbackTo=$FeedbackTo;
		$this->SetSubManner($SubManner);
		$this->SetProcessForward($Forward);
	}

	function  Summary()
	{
		$submanner = p::$arr[$this->SubManner()];
		$to = p::$arr[$this->FeedbackTo()];
		return 'رای '.v::b($submanner).' دریافت شد. رای به نفع '.v::b($to). ' اعلام شد.';
	}
	function Title()
	{
		return 'دریافت پرونده';
	}
	function Manner()
	{
		return 'Feedback_'.$this->SubManner().'_'.$this->FeedbackTo;
	}
}


use \Doctrine\ORM\EntityRepository;
class ReviewProcessFeedbackRepository extends EntityRepository
{
	/**
	 *
	 * Enter description here ...
	 * @param ReviewFile $File
	 * @return string on error object on sucsess
	 */
	public function AddToFile(ReviewFile $File=null,$SubManner, $FeedbackTo, $MailNum, $Comment=null)
	{
		$Forward = $File->LLP('Forward', true);
		if(!$Forward or $Forward->SubManner()!=$SubManner)
			return 'ارسال نشده است.';
		$R=new ReviewProcessFeedback($File,$SubManner, $FeedbackTo,$MailNum, $Forward);
		$R->setComment($Comment);
		$er = $R->Check();
		if(is_string($er))
			return $er;

		$R->Apply();
		ORM::Persist($R);
		return $R;
	}
}