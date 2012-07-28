<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @entity(repositoryClass="ReviewProcessForwardRepository")
 * */
class ReviewProcessForward extends ReviewProgress
{

	/**
	 * @Column(type="string")
	 * @var string
	 *
	 */
	protected $ForwardOffice;// setad,commission,appeals
	function ForwardOffice()
	{
		return $this->ForwardOffice;
	}
	/**
	 * @Column(type="string",name="Forward_Setad")
	 * @var string
	 *
	 */
	protected $Setad;
	function Setad()
	{
		return $this->Setad;
	}
	function __construct(ReviewFile $File=null,$ForwardOffice=null,$Setad=null,$Indicator=null,MyUser $User=null)
	{
		parent::__construct($File,$User);
		$this->MailNum=$Indicator;
		$this->ForwardOffice=$ForwardOffice;
		$this->Setad=$Setad;
		
	}

	function  Summary()
	{
		$R="اظهارنامه به ";
		$R.=FsmGraph::$Persian($this->ForwardOffice);
		$R.="ارسال شد.";
		return $R;
	}
	function Title()
	{
		switch($this->ForwardOffice){
			case 'setad': return "ارسال به دفاتر ستادی";
			case 'commission': return "ارسال به کمیسیون";
			case 'appeals': return "ارسال به کمیسیون تجدید نظر";			
		}
	}
	function Event()
	{
		if(!issset($this->ForwardOffice))
			throw new Exception("hoooo");
		$R="Forward_";
		$R.=$this->ForwardOffice;
		return $R;
	}

}


use \Doctrine\ORM\EntityRepository;
class ReviewProcessForwardRepository extends EntityRepository
{
	/**
	 *
	 * Enter description here ...
	 * @param ReviewFile $File
	 * @return string on error object on sucsess
	 */
	public function AddToFile(ReviewFile $File=null,$ForwardOffice=null,$Setad,$Indicator,$Comment="")
	{
		$CurrentUser=MyUser::CurrentUser();


		if ($File==null)
		{
			$res['Error']="اظهارنامه‌ای با شماره کوتاژ داده شده در سیستم ثبت نشده است.";
		}
		else{
			if(FsmGraph::NextState($File->State(),"Forward"))
			{
				$R=new ReviewProcessForward($File,$Ofiice,$Setad,$Indicator,$CurrentUser);
				$R->SetState($File,FsmGraph::NextState($File->State(),"Forward"));
				ORM::Write($R);
				ORM::Persist($File);
				$res['Class']=$R;
			}
			else
			{
				$res['Error']=" پرونده با شماره کلاسه ".$File->GetClass()."در مرحله ای نیست که بتوان درخواست  بررسی ایجاد کرد.";
			}
		}
		
		return $res;
	}
}