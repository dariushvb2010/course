<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @entity(repositoryClass="ReviewProcessConfirmRepository")
 * */
class ReviewProcessConfirm extends ReviewProgress
{

	/**
	 * @Column(type="boolean",name="Confirm_ConfirmResult")
	 * @var boolean
	 *
	 */
	protected $ConfirmResult;//ok,nok
	function ConfirmResult()
	{
		return $this->ConfirmResult;
	}
	
	function __construct(ReviewFile $File=null,$ConfirmResult,$Indicator,MyUser $User=null)
	{
		parent::__construct($File,$User);
		$this->MailNum=$Indicator;
		$this->ConfirmResult=$ConfirmResult;
	}

	function  Summary()
	{
		$R="مدیر بازبینی آقای ";
		$R.=$this->User->getFullName();
		$R.="کارشناسی کارشناس را تایید کرد";
		return $R;
	}
	function Title()
	{
		return "تایید مدیر";
	}
	function Event()
	{
		if(isset($this->ConfirmResult))
		{
			return "ProcessConfirm_".($this->ConfirmResult ? "ok" : "nok");
		}
		else
			throw new Exception("Event has not been set!");
	}

}


use \Doctrine\ORM\EntityRepository;
class ReviewProcessConfirmRepository extends EntityRepository
{
	/**
	 *
	 * Enter description here ...
	 * @param ReviewFile $File
	 * @return string on error object on sucsess
	 */
	public function AddToFile(ReviewFile $File=null,$ConfirmResult,$Indicator=null,$Comment=null)
	{
		$CurrentUser=MyUser::CurrentUser();


		if ($File==null)
		{
			$res['Error']="اظهارنامه‌ای با شماره کوتاژ داده شده در سیستم ثبت نشده است.";
		}
		else{
			if(FileFsm::NextState($File->State(),"Processconfirm"))
			{
				$R=new ReviewProcessConfirm($File,$ConfirmResult,$Indicator,$CurrentUser);
				$R->setComment(($Comment==null?"":$Comment));
				$R->SetState($File,FileFsm::NextState($File->State(),"Processconfirm"));
				ORM::Persist($File);
				ORM::Persist($R);
				$res['Class']=$R;
			}
			else
			{
				$res['Error']=" پرونده با شماره کلاسه ".$File->GetClass()."در مرحله ای نیست که نیاز به تایید مدیر باشد.";
			}
		}
		return $res;
	}
}