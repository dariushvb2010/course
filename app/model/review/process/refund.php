<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @entity(repositoryClass="ReviewProcessRefundRepository")
 * */
class ReviewProcessRefund extends ReviewProgress
{

	/**
	 * @Column(type="string" , name="Refund_id")
	 * @var string
	 *
	 */
	protected $RefundValue;// first,second,setad
	function RefundValue()
	{
		return $this->RefundValue;
	}
	
	function __construct(ReviewFile $File=null,$RefundValue,$Indicator,MyUser $User=null)
	{
		parent::__construct($File,$User);
		$this->MailNum=$Indicator;
		$this->RefundValue=$RefundValue;
	}

	function  Summary()
	{
		return ".";
	}
	function Title()
	{
		return 'استرداد';
	}
	function Event()
	{
		$R="Refund_";
		if(!isset($this->RefundValue))
			throw new Exception("hooooooo");
		$R.=$this->RefundValue;
		return $R;
	}
}


use \Doctrine\ORM\EntityRepository;
class ReviewProcessRefundRepository extends EntityRepository
{
	/**
	 *
	 * Enter description here ...
	 * @param ReviewFile $File
	 * @return string on error object on sucsess
	 */
	public function AddToFile(ReviewFile $File=null,$RefundValue,$Indicator,$Comment=null)
	{
		$CurrentUser=MyUser::CurrentUser();


		if ($File==null)
		{
			$res['Error']="اظهارنامه‌ای با شماره کوتاژ داده شده در سیستم ثبت نشده است.";
		}
		else{
			if(FileFsm::NextState($File->State(),"Refund"))
			{
				$R=new ReviewProcessRefund($File,$RefundValue,$Indicator,$CurrentUser);
				$R->setComment(($Comment==null?"":$Comment));
				$R->SetState($File,FileFsm::NextState($File->State(),"Refund"));
				ORM::Write($R);
				ORM::Persist($File);
				$res['Class']=$R;
			}
			else
			{
				$res['Error']=" پرونده با شماره کلاسه ".$File->GetClass()."در مرحله ای نیست که بتوان مبلغی را استرداد کرد.";
			}
		}
		
		return $res;
	}
}