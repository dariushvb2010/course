<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="ProcessAssign")
 * @entity(repositoryClass="ReviewProcessAssignRepository")
 * */
class ReviewProcessAssign extends ReviewProgress
{
	
	/**
	*
	* @ManyToOne(targetEntity="MyUser");
	* @JoinColumn(name="ExpertID",referencedColumnName="ID")
	* @var unknown_type
	*/
	protected  $Reviewer;
	function Reviewer()
	{
		return $this->Reviewer;
	}
	
	function __construct(ReviewFile $File=null,MyUser $User=null,MyUser $Reviewer=null)
	{
		parent::__construct($File,$User);
		if($Reviewer)
			$this->Reviewer=$Reviewer;
	}
	
	function  Summary()
	{
		if($this->Reviewer)
			return "پرونده به کارشناس بازبینی "."<b>".$this->Reviewer()->getFullName()."</b>"."  تخصیص داده شد.";
		else 
			return "خطا در گزارش گیری";
	}
	function Title()
	{
		return "تحویل به کارشناس";
	}
	function Event()
	{
		return "ProcessAssign";
	}
}


use \Doctrine\ORM\EntityRepository;
class ReviewProcessAssignRepository extends EntityRepository
{
	/**
	 * 
	 * Enter description here ...
	 * @param ReviewFile $File
	 * @return string on error object on sucsess
	 */
	public function AddToFile(ReviewFile $File=null)
	{
		$CurrentUser=MyUser::CurrentUser();
		if($File)
		{
			if(FsmGraph::NextState($File->State(),"ProcessAssign"))
			{
				if($File->LastReviewer())
				{
					if($File->LastReviewer()->State()!="Retired")
					$Reviewer=$File->LastReviewer();
				}
				else
				{
					$Reviewer=ORM::Query(new MyUser)->getRandomReviewer();
					if(!$Reviewer)
					{
						$res['Error']="هیچ کارشناس بازبینی فعال در سیستم وجود ندارد.";
						return $res;
					}
					$this->Reviewer=$Reviewer;
				}

				$p=new ReviewProcessAssign($File, $CurrentUser,$Reviewer);
				$p->SetState($File,FsmGraph::NextState($File->State(),"ProcessAssign"));
				ORM::Persist($File);
				ORM::Persist($p);
				$res['Class']=$p;
			}
			else
			{
				$res['Error']=" پرونده با شماره کلاسه ".$File->GetClass()."در مرحله ای نیست که بتوان به کارشناس  تخصیص داد.";
			}
		}
		else
		{
			$res['Error']="اظهارنامه وجود ندارد.";
		}
		return $res;
	}
}