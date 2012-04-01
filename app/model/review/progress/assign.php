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
	 * 
	 * @ManyToOne(targetEntity="MyUser");
	 * @JoinColumn(name="ReviewerID",referencedColumnName="ID")
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
		$this->Reviewer=$Reviewer;
	}
	
	function  Summary()
	{
		if($this->Reviewer)
			return "اظهارنامه به کارشناس بازبینی "."<b>".$this->Reviewer()->getFullName()."</b>"." تخصیص داده شد.";
		else 
			return "خطا در گزارش گیری";
	}
	function Title()
	{
		return "تخصیص";
	}
	function Event()
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
		$CurrentUser=MyUser::CurrentUser();

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
			$Error="اظهارنامه‌ای با شماره کوتاژ داده شده در سیستم ثبت نشده است.";
			return $Error;
		}
		else
		{
			$R=new ReviewProgressAssign($File,$CurrentUser,$Reviewer);
			$R->setComment($Comment);
			$ch=$R->Apply();
			if(is_string($ch))
			{
				if(j::check("Reassign"))
				{
					$LLP=$File->LLP();
					if( $LLP instanceof ReviewProgressAssign)
					{
						$LLP->kill();
						ORM::Persist($R);
						return $R;
					}
				}
				return "اظهارنامه با شماره کوتاژ  ".$File->Cotag()." قابل تخصیص به کارشناس نیست.";
			}
			else
			{
				ORM::Persist($R);
				return $R;
			}
		
		}
		
	}
}