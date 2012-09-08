<?php
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity
 * @entity(repositoryClass="ReviewProgressAssignRepository")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="Type", type="string")
 * */
class ReviewProgressAssign extends ReviewProgress
{
	/**
	 * @ManyToOne(targetEntity="MyUser");
	 * @JoinColumn(name="ReviewerID",referencedColumnName="ID")
	 * @var MyUser
	 */
	protected  $Reviewer;
	function Reviewer()
	{
		return $this->Reviewer;
	}
	
	function __construct(ReviewFile $File=null,MyUser $Reviewer=null)
	{
		parent::__construct($File);
		
		$this->Reviewer=$Reviewer;
	}
	
	function  Summary()
	{
		if($this->Reviewer)
			return "اظهارنامه به کارشناس بازبینی ".v::b($this->Reviewer()->getFullName())." تخصیص داده شد.";
		else 
			return "خطا در گزارش گیری";
	}
	function Title()
	{
		return "تخصیص";
	}
	function Manner()
	{
		return "Assign";
	}
}


use \Doctrine\ORM\EntityRepository;
class ReviewProgressAssignRepository extends EntityRepository
{
	/**
	 * 
	 * Enter description here ...
	 * @param ReviewFile $File
	 * @return string on error object on sucsess
	 */
	public function AddToFile(ReviewFile $File=null,MyUser $Reviewer=null,$Comment=null)
	{
		if($Reviewer==null)
		{
			$Reviewer=ORM::Query(new MyUser)->getRandomReviewer();
			if(!$Reviewer)
			{
				$Error="هیچ کارشناس بازبینی فعال در سیستم وجود ندارد.";
				return $Error;
			}
		}
		if ($File==null)
		{
			$Error=v::Ecnf();
			return $Error;
		}
		else
		{
			$R=new ReviewProgressAssign($File, $Reviewer);
			if($Comment==null)
				$Comment="";
			$R->setComment($Comment);
			$ch=$R->Check();
			if(is_string($ch))
			{
				return "اظهارنامه با شماره کوتاژ  ".$File->Cotag()." قابل تخصیص به کارشناس نیست.";
			}
			else
			{
				//TODO: $R=new ReviewProgressAssign($File,$Reviewer,true);
				$R->Apply();
				ORM::Persist($R);
				return $R;
			}
		
		}
		
	}
}