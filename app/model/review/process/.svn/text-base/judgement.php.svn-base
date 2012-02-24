<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @entity(repositoryClass="ReviewProcessJudgementRepository")
 * */
class ReviewProcessJudgement extends ReviewProgress
{

	/**
	 * @Column(type="string",name="Judgement_Result")
	 * @var string
	 *
	 */
	protected $JudgeResult;// ok,nok,JudgementSetad,commission
	function JudgeResult()
	{
		return $this->JudgeResult;
	}
	/**
	 * @Column(type="string",name="Judgment_JudgementSetad")
	 * @var string
	 *
	 */
	protected $JudgementSetad;
	function JudgementSetad()
	{
		return $this->JudgementSetad;
	}
	function __construct(ReviewFile $File=null,$JudgeResult=null,$JudgementSetad=null,MyUser $User=null)
	{
		parent::__construct($File,$User);
		$this->JudgeResult=$JudgeResult;
		$this->JudgementSetad=$JudgementSetad;
	}

	function  Summary()
	{
		$R="کارشناسی پرونده انجام شد.";
	}
	function Title()
	{
		return "کارشناسی";
	}
	function Event()
	{
		if(!isset($this->JudgeResult))
			throw new Exception("hooooooooooo");
		
		return "Judgement_".$this->JudgeResult;
	}
}


use \Doctrine\ORM\EntityRepository;
class ReviewProcessJudgementRepository extends EntityRepository
{
	/**
	 *
	 * Enter description here ...
	 * @param ReviewFile $File
	 * @return string on error object on sucsess
	 */
	public function AddToFile(ReviewFile $File=null,$JudgeResult,$JudgementSetad,$Comment="")
	{
		$CurrentUser=MyUser::CurrentUser();


		if ($File==null)
		{
			$res['Error']="اظهارنامه‌ای با شماره کوتاژ داده شده در سیستم ثبت نشده است.";
		}
		else{
			if(FileFsm::NextState($File->State(),"Judgement_".$JudgeResult))
			{
				$R=new ReviewProcessJudgement($File,$JudgeResult,$JudgementSetad,$CurrentUser);
				$R->setComment(($Comment==null?"":$Comment));
				$R->SetState($File,FileFsm::NextState($File->State(),"Judgement_".$JudgeResult));
				ORM::Persist($R);
				ORM::Persist($File);
				$res['Class']=$R;
			}
			else
			{
				$res['Error']=" پرونده با شماره کلاسه ".$File->GetClass()."در مرحله ای نیست که بتوان نتیجه  کارشناسی را ثبت کرد.";
			}
		}
		return $res;
	}
}