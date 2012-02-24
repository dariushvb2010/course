<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @entity(repositoryClass="ReviewProcessProphecyRepository")
 * */
class ReviewProcessProphecy extends ReviewProgress
{

	/**
	 * @Column(type="string")
	 * @var string
	 *
	 */
	protected $ProphecyStep;// first,second,setad
	function ProphecyStep()
	{
		return $this->ProphecyStep;
	}
	
	function __construct(ReviewFile $File=null,$ProphecyStep=null,$Indicator=null,MyUser $User=null)
	{
		parent::__construct($File,$User);
		$this->MailNum=$Indicator;
		$this->ProphecyStep=$ProphecyStep;
	}

	function  Summary()
	{
		switch ($this->ProphecyStep){
			case 'first':
				return 'ابلاغ مطالبه نامه به صاحب کالا ثبت گردید.';
			case 'second':
			case 'setad':
		}
	}
	function Title()
	{
		return 'ثبت ابلاغ';
	}
	function Event()
	{
		$R="Prophecy_";
		if(!isset($this->ProphecyStep))
			throw new Exception("hoooooooooo");
		$R.=$this->ProphecyStep;
		return $R;
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
	public function AddToFile(ReviewFile $File=null,$ProphecyStep=null,$Indicator,$Comment=null)
	{
		$CurrentUser=MyUser::CurrentUser();

		if ($File==null)
		{
			$res['Error']="اظهارنامه‌ای با شماره کوتاژ داده شده در سیستم ثبت نشده است.";
		}
		else{
			if(FileFsm::NextState($File->State(),"Prophecy_".$ProphecyStep))
			{
				$R=new ReviewProcessProphecy($File,$ProphecyStep,$Indicator,$CurrentUser);
				$R->setComment(($Comment==null?"":$Comment));
				$R->SetState($File,FileFsm::NextState($File->State(),"Prophecy_".$ProphecyStep));
				ORM::Write($R);
				ORM::Persist($File);
				$res['Class']=$R;
			}
			else
			{
				$res['Error']=" پرونده با شماره کلاسه ".$File->GetClass()."در مرحله ای نیست که بتوان اعلام ابلاغی را ثبت کرد.";
			}
			return $res;

		}

	}
}