<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @entity(repositoryClass="ReviewProcessSenddemandRepository")
 * */
class ReviewProcessSenddemand extends ReviewProgress
{

	/**
	 * @Column(type="string",name="Senddemand_DemandStep")
	 * @var string
	 *
	 */
	protected $DemandStep;//demand,karshenas,setad
	function DemandStep()
	{
		return $this->DemandStep;
	}
	
	function __construct(ReviewFile $File=null,$DemandStep=0,$Indicator=0,MyUser $User=null)
	{
		parent::__construct($File,$User);
		$this->MailNum=$Indicator;
		$this->DemandStep=$DemandStep;
	}

	function  Summary()
	{
		switch($this->DemandStep)
		{
			case 'demand': return "ارسال مطالبه نامه به صاحب کالا";
			case 'karshenas': return "ارسال نتیجه کارشناسی";
			case 'setad': return "ارسال نتیجه دفاتر ستادی";			
		}
	}
	function Title()
	{
		switch($this->DemandStep){
			case 'demand': return "ارسال مطالبه نامه";
			case 'karshenas': return "ارسال نتیجه کارشناسی";
			case 'setad': return "ارسال نتیجه دفاتر ستادی";			
		}
	}
	function Event()
	{
		$R="Senddemand_";
		if(!isset($this->DemandStep))
			throw new Exception("hoooooo");
		$R.=$this->DemandStep;
		return $R;
	}

}


use \Doctrine\ORM\EntityRepository;
class ReviewProcessSenddemandRepository extends EntityRepository
{
	/**
	 *
	 * @param ReviewFile $File
	 * @return string on error object on sucsess
	 */
	public function AddToFile(ReviewFile $File=null,$DemandStep=null,$Indicator,$Comment=null)
	{
		
		$CurrentUser=MyUser::CurrentUser();

		if ($File==null)
		{
			$Res['Error']="اظهارنامه‌ای با شماره کوتاژ داده شده در سیستم ثبت نشده است.";
		}
		else{
			$R=new ReviewProcessSenddemand($File,$DemandStep,$Indicator,$CurrentUser);
			$R->setComment(($Comment==null?"":$Comment));
			$er=$R->Check();
			if(!is_string($er)){
				$R->Apply();
				ORM::Write($R);
				$Res['Class']=$R;
			}else{
				$Res['Error']=$er;
			}
		}
		
		return $Res;

	}
}