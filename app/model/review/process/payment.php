<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @entity(repositoryClass="ReviewProcessPaymentRepository")
 * */
class ReviewProcessPayment extends ReviewProgress
{

	/**
	 * @Column(type="string")
	 * @var string
	 *
	 */
	protected $PaymentValue;// first,second,setad
	function PaymentValue()
	{
		return $this->PaymentValue;
	}
	
	function __construct(ReviewFile $File=null,$PaymentValue,$Indicator,MyUser $User=null)
	{
		parent::__construct($File,$User);
		$this->MailNum=$Indicator;
		$this->PaymentValue=$PaymentValue;
	}

	function  Summary()
	{
		return ".";
	}
	function Title()
	{
		return 'پرداخت';
	}
	function Manner()
	{
		if(!isset($this->PaymentValue))
			throw new Exception("hooooooooooo");
		$R="Payment_";
		$R.=$this->PaymentValue;
		return $R;
	}
}


use \Doctrine\ORM\EntityRepository;
class ReviewProcessPaymentRepository extends EntityRepository
{
	/**
	 *
	 * Enter description here ...
	 * @param ReviewFile $File
	 * @return string on error object on sucsess
	 */
	public function AddToFile(ReviewFile $File=null,$PaymentValue,$Indicator,$Comment=null)
	{
		$CurrentUser=MyUser::CurrentUser();


		if ($File==null)
		{
			$res['Error']=v::Ecnf();
		}
		else{
			if(FsmGraph::NextState($File->State(),"Payment"))
			{
				$R=new ReviewProcessPayment($File,$PaymentValue,$Indicator,$CurrentUser);
				$R->setComment(($Comment==null?"":$Comment));
				$R->SetState($File,FsmGraph::NextState($File->State(),"Payment"));
				ORM::Write($R);
				ORM::Persist($File);
				$res['Class']=$R;
			}
			else
			{
				$res['Error']=" پرونده با شماره کلاسه ".$File->GetClass()."در مرحله ای نیست که بتوان پرداختی ثبت کرد.";
			}

		}
		
		return $res;
	}
}