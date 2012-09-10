<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
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
	 * کارشناسی و درخواست ارسال به دفاتر ستادی
	 * @var string
	 */
	const SubManner_setad = 'setad';
	
	static $SubMannerArray=array('accept','deny','setad');
	
	/**
	 * @ManyToOne(targetEntity="ReviewTopic")
	 * @JoinColumn(name="ReviewSetadID",referencedColumnName="ID")
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
		return $r;
	}
	/**
	 * if you set the $setad memebr, the submanner must be 'setad'
	 */
	function CheckConsistensy(){
		if(($this->Setad() and $this->SubManner()!=self::SubManner_setad) or 
				(!$this->Setad() and $this->SubManner()==self::SubManner_setad))
			return 'برای ارسال به دفاتر ستادی یکی از موارد را انتخاب نمایید.';
		else
			return true;
	}
	function Check(){
		//=======parent=======
		$c = parent::Check();
		if(is_string($c))
			return $c;
		//======consistency=====
		$c = $this->CheckConsistensy();
		if(is_string($c))
			return $c;
		//============check for processAssign reviewer = $thisUser or $thisUser is manager
		$processAssign = $this->File->LLP('Assign', true);
		$curUser = MyUser::CurrentUser();
		if($processAssign){
			$rev = $processAssign->ProcessReviewer(); 
			if($rev)
				if($processAssign->ProcessReviewer()->ID() != $curUser->ID() and $curUser->GroupTitle()!=MyGroup::Title_Admin)
					return v::Enatu();
		}
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
		if($this->User->GroupTitle()==MyGroup::Title_Admin)
			return 'رای مدیر';
		else
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
	 * @param ReviewFile $File
	 * @return string on error object on sucsess
	 */
	public function AddToFile(ReviewFile $File, $SubManner, $MailNum='', ReviewTopic $Setad=null, $Comment)
	{
		$R=new ReviewProcessReview($File,$SubManner, $MailNum, $Setad);
		$R->setComment($Comment);
		$er = $R->Check();
		if(is_string($er))
			return $er;
		
		$R->Apply();
		ORM::Persist($R);
		return $R;
	}
}