<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @entity(repositoryClass="ReviewProcessProtestRepository")
 * */
class ReviewProcessProtest extends ReviewProgress
{

	/**
	 * @Column(type="string")
	 * @var string
	 *
	 */
	protected $ProtestRequest;//karshenas,setad,commission,appeals
	function ProtestRequest()
	{
		return $this->ProtestRequest;
	}
	
	function __construct(ReviewFile $File=null,$ProtestRequest=null,$Indicator=null,MyUser $User=null)
	{
		parent::__construct($File,$User);
		$this->MailNum=$Indicator;
		$this->ProtestRequest=$ProtestRequest;
	}

	function  Summary()
	{
		$R= "اعتراض صاحب کالا با درخواست ارسال به";
		$R.=' ';
		$R.=FileFsm::$Persian[$this->ProtestRequest];
		$R.=' ';
		$R.="ثبت شد.";
		return $R;
	}
	function Title()
	{
		return "اعتراض";
	}
	function Event()
	{
		return "Protest";
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
	public function AddToFile(ReviewFile $File=null,$ProtestRequest=null,$Indicator=null,$Comment=null)
	{
		$CurrentUser=MyUser::CurrentUser();

		if ($File==null)
		{
			$res['Error']="اظهارنامه‌ای با شماره کوتاژ داده شده در سیستم ثبت نشده است.";
		}
		else{
			$R=new ReviewProcessProtest($File,$ProtestRequest,$Indicator,$CurrentUser);
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