<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @entity(repositoryClass="ReviewProcessFeedbackRepository")
 * */
class ReviewProcessFeedback extends ReviewProgress
{

	/**
	 * @Column(type="string")
	 * @var string
	 *
	 */
	protected $FeedbackOffice;// setad,commission,appeals
	function FeedbackOffice()
	{
		return $this->FeedbackOffice;
	}
	
	/**
	 * togomrok=1(it is good and for us, thus it is 1)
	 * toowner=0
	 * @Column(type="boolean",name="feedback_FeedbackResult")
	 * @var boolean
	 *
	 */
	protected $FeedbackResult; // togomrok=1,toowner=0
	function FeedbackResult()
	{
		return $this->FeedbackResult;
	}
	
	function __construct(ReviewFile $File=null,$FeedbackResult,$FeedbackOffice,$Indicator,MyUser $User=null)
	{
		parent::__construct($File,$User);
		$this->MailNum=$Indicator;
		$this->FeedbackOffice=$FeedbackOffice;
		$this->FeedbackResult=$FeedbackResult;
	}

	function  Summary()
	{
		$R=" رای";
		switch ($this->FeedbackOffice)
		{
			case "commission": $R.="کمیسیون";
			case "setad": $R.="دفاتر ستادی";
			case "appeals": $R.="کمیسیون تجدید نظر";
		}
		$R.="دریافت شد. نتیجه رای به نفع ";
		$R.=$this->FeedbackResult ? "گمرک":
				"صاحب کالا";
		$R.="اعلام شد.";
		return $R;
	}
	function Title()
	{
		$E=ORM::Find1("ConfigEvent","EventName", $this->Event());
		if($E)
			return $E->PersianTitle();
	}
	function Event()
	{
		if(!isset($this->FeedbackResult) OR !isset($this->FeedbackOffice))
			throw new Exception("hooooooooooo");
		$R="Feedback_";
		$R.=$this->FeedbackOffice."_";
		$R.=$this->FeedbackResult ? "togomrok" : "toowner";
		return $R;
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
	public function AddToFile(ReviewFile $File=null,$FeedbackResult,$FeedbackOffice=null,$Indicator,$Comment=null)
	{
		$CurrentUser=MyUser::CurrentUser();


		if ($File==null)
		{
			$res['Error']="اظهارنامه‌ای با شماره کوتاژ داده شده در سیستم ثبت نشده است.";
		}
		else{
			if(FileFsm::NextState($File->State(),"Feedback"))
			{
				$R=new ReviewProcessFeedback($File,$FeedbackResult,$FeedbackOffice,$Indicator,$CurrentUser);
				$R->setComment(($Comment==null?"":$Comment));
				$R->SetState($File,FileFsm::NextState($File->State(),"Feedback"));
				ORM::Write($R);
				ORM::Persist($File);
				$res['Class']=$R;
			}
			else
			{
				$res['Error']=" پرونده با شماره کلاسه ".$File->GetClass()."در مرحله ای نیست که بتوان نتیجه  بررسی را ثبت کرد.";
			}
		}
		return $res;
	}
}