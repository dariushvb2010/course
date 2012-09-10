<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @entity(repositoryClass="ReviewProcessPaymentRepository")
 * */
class ReviewProcessPayment extends ReviewProgress
{

	///--------------------///
	///protected MailNum = 
	///-------------------///
	/**
	 * @Column(type="string")
	 * @var string
	 *
	 */
	protected $PaymentValue;
	function PaymentValue(){ return $this->PaymentValue; }
	
	function __construct(ReviewFile $File=null,$PaymentValue,$MailNum,MyUser $User=null)
	{
		parent::__construct($File,$User);
		$this->setMailNum($MailNum);
		$this->PaymentValue=$PaymentValue;
	}

	function  Summary()
	{
		return 'مبلغ پرداخت: '.$this->PaymentValue().' ریال.';
	}
	function Title()
	{
		return 'پرداخت و تمکین';
	}
	function Manner()
	{
		return 'Payment';
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
	public function AddToFile(ReviewFile $File=null,$PaymentValue,$MailNum,$Comment=null)
	{

		$R=new ReviewProcessPayment($File,$PaymentValue,$MailNum);
		$R->setComment($Comment);
		
		$er = $R->Check();
		if(is_string($er))
			return $er;
		
		$R->Apply();
		ORM::Persist($R);

		return $R;
	}
}