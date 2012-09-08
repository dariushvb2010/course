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
	function __construct(ReviewFile $File=null,$SubManner=null,$Indicator=null,MyUser $User=null)
	{
		parent::__construct($File,$User);
		$this->setMailNum($Indicator);
		$this->SetSubManner($SubManner);
	}

	function  Summary()
	{
		return '';
	}
	function Title()
	{
		$fsmp = FsmGraph::GetProgressByName($this->Manner());
		return $fsmp->Label;
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
	public function AddToFile(ReviewFile $File, $SubManner ,$Indicator,$Comment=null)
	{

		$R=new ReviewProcessProphecy($File,$SubManner,$Indicator);
		$R->setComment(($Comment==null?"":$Comment));
		var_dump($R->SubManner());
		if(!$R->SubMannerValidation())
			return v::Edb();
		var_dump($R->Manner());
		$er=$R->Check();
		if(is_string($er))
			return $er;
			
		$R->Apply();
		ORM::Persist($R);
		return $R;
	}
}