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
	function __construct(ReviewFile $File=null,$SubManner,$Indicator='',MyUser $User=null)
	{
		parent::__construct($File,$User);
		$this->setMailNum($Indicator);
		$this->SetSubManner($SubManner);
	}

	function  Summary()
	{
		$fsmp = FsmGraph::GetProgressByName($this->Manner());
		return 'اعتراض صاحب کالا ثبت شد: '. $fsmp->Label;
	}
	function Title()
	{
		return 'اعتراض صاحب کالا';
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
	public function AddToFile(ReviewFile $File,$SubManner, $Indicator, $Comment=null)
	{
		$R=new ReviewProcessProtest($File,$SubManner,$Indicator);
		$R->setComment($Comment);
		if(!$R->SubMannerValidation())
			return v::Edb();
		$er=$R->Check();
		if(is_string($er))
			return $er;
		
		$R->Apply();
		ORM::Persist($R);
		return $R;

	}
}