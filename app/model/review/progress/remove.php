<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @entity(repositoryClass="ReviewProgressRemoveRepository")
 * */
class ReviewProgressRemove extends ReviewProgress
{
	/**
	 * @OneToOne(targetEntity="ReviewProgress")
	 * @JoinColumn(name="SlainID", referencedColumnName="ID")
	 */
	protected $Slain;
	function Slain()
	{
		return $this->Slain;
	}



	function __construct(ReviewFile $File=null, MyUser $User=null,$Comment='')
	{
		parent::__construct($File,$User);
		$this->kill();
		if($File)
		{
			$LLP=ORM::Query(new ReviewFile)->LastLiveProgress($File);
			if(!$LLP)
			throw new Exception("Remove of nothing is impossible");
			$this->Slain=$LLP;
			$LLP->kill();
			$File->SetState($LLP->PrevState());
		}
		$this->Comment=$Comment;
	}

	function  Summary()
	{
		return "آخرین فرایند این اظهارنامه یعنی "."<strong> '".$this->Slain->Summary()."' </strong>"."حذف شد ";
	}
	function Title()
	{
		return "حذف یک فرآیند";
	}

}


use \Doctrine\ORM\EntityRepository;
class ReviewProgressRemoveRepository extends EntityRepository
{
	/**
	 *
	 * Enter description here ...
	 * @param ReviewFile $File
	 * @return string on error true on sucsess
	 */
	public function AddToFile(ReviewFile $File,$Comment)
	{

		if (!$File)
		{
			$Error="کوتاژ ناصحیح است.";
			return $Error;
		}
		$LLP=$File->LLP();
		if(!$LLP)
		{
			$Error="هیچ فرایند قابل حذفی برای این پرونده یا اظهارنامه وجود ندارد.";
			return $Error;
		}
		if($LLP->PrevState()==0)
		{
			return "امکان حذف کردن این فرآیند وجود ندارد. ";
		}
		$User=MyUser::CurrentUser();
		$R=new ReviewProgressRemove($File,$User,$Comment);
		ORM::Persist($R);
		return true;
	}
}
