<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ارسال رای به آدرس صاحب کالا
 * address در لغت به معنی ارسال می باشد
 * @Entity
 * @entity(repositoryClass="ReviewProcessAddressRepository")
 * */
class ReviewProcessAddress extends ReviewProgress
{
	///--------------------///
	///protected SubManner =
	///-------------------///
	/**
	 * send demand
	 * ارسال مطالبه نامه
	 * @var string
	 */
	const SubManner_first = 'first';
	/**
	 * ارسال نظر کارشناس
	 * @var string
	 */
	const SubManner_second = 'second';
	/**
	 * ارسال رای دفاتر ستادی
	 * @var string
	 */
	const SubManner_setad = 'setad';
	/**
	 * not usefull yet
	 * ارسال رای کمیسیون
	 * این مورد در گمرک رجایی وجود ندارد چون کمیسیون خودش ارسال را انجام میدهد
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
	function __construct(ReviewFile $File=null,$SubManner,$MailNum=0,MyUser $User=null)
	{
		parent::__construct($File,$User);
		$this->setMailNum($MailNum);
		$this->SetSubManner($SubManner);
	}

	function  Summary()
	{
		return $this->Title(). 'شماره نامه: '.v::b($this->MailNum());
	}
	function Title()
	{
		$fsmp = FsmGraph::GetProgressByName($this->Manner());
		return $fsmp->Label;
	}
	function Manner()
	{
		return 'Address_'.$this->SubManner();
	}

}


use \Doctrine\ORM\EntityRepository;
class ReviewProcessAddressRepository extends EntityRepository
{
	/**
	 *
	 * @param ReviewFile $File
	 * @return string on error object on sucsess
	 */
	public function AddToFile(ReviewFile $File, $SubManner=null,$MailNum,$Comment=null)
	{
		$R=new ReviewProcessAddress($File,$SubManner,$MailNum);
		if(!$R->SubMannerValidation())
			return v::Edb();
		
		$R->setComment(($Comment==null?'':$Comment));
		$er=$R->Check();
		if(is_string($er))
			return $er;
		
		$R->Apply();
		ORM::Persist($R);
		
		return $R;

	}
}