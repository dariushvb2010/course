<?php
use Symfony\Component\Console\Input\StringInput;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity
 * @entity(repositoryClass="ReviewProgressManualcorrespondenceRepository")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="Type", type="string")
 * */
class ReviewProgressManualcorrespondence extends ReviewProgress
{
	
	
	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected  $Destination;
	function Destination()
	{
		return $this->Destination;
	}
	
	
	/**
	 * @Column(type="integer")
	 * @var integer
	 */
	protected $Newmailnum;
// 	/**
// 	* @ManyToOne(targetEntity="ReviewCorrespondenceTopic",inversedBy="Correspondence")
// 	* @JoinColumn(name="TopicID", referencedColumnName="ID")
// 	*/
// 	protected $Subject;
// 	function Subject()
// 	{
// 		return $this->Subject;
// 	}
	/**
	* @Column(type="integer")
	* @var integer
	*/
	
	protected $SubjectID;
	function SubjectID()
	{
		return $this->SubjectID;
	}
	function __construct(ReviewFile $File=null,MyUser $User=null,$Newmailnum=null, $SubjectID=null,$Destination =null,$Comment=null)
	{
		parent::__construct($File,$User);
		$this->Newmailnum=0;
		$this->Comment="";
		$this->Destination="";
		$this->subjectID=1;
		if($Destination)$this->Destination=$Destination;
		if($SubjectID)
		{
			var_dump($subjectID);
			$this->SubjectID=$SubjectID;
			//$T=ORM::Find("ReviewCorrespondenceTopic", $SubjectID);
			$this->Subject=$T;
		}
		if($Comment)$this->Comment=$Comment;
		if($Newmailnum)$this->Newmailnum=$Newmailnum;
	}
	
	function  Summary()
	{
		return  " نامه ای به شماره ".$this->Newmailnum." تحت عنوان <strong>".$subject."</strong> برای ".$this->Destination." فرستاده شد <br/>"
		."توضیحات:".substr($this->Comment, 0,40).(strlen($this->Comment)>40?'...':'');
	}
	function Title()
	{
		return "مکاتبات";
	}
	
}


use \Doctrine\ORM\EntityRepository;
class ReviewProgressManualcorrespondenceRepository extends EntityRepository
{
	/**
	 * 
	 * Enter description here ...
	 * @param ReviewFile $File
	 * @return string on error object on sucsess
	 */
	public function AddToFile($Cotag=null,$Newmailnum=null,$SubjectID=null,$Destination=null,$Comment=null)
	{
		echo "this is subjectID:".$SubjectID;
		$Topic=ORM::Find("ReviewCorrespondenceTopic", $SubjectID);
		if ($Cotag<1)
		{
			$Error="کوتاژ ناصحیح است.";
			return $Error;
		}
		else 
		{
			$File=ORM::Query(new ReviewFile)->GetRecentFile($Cotag);
			if ($File==null)
			{
				$Error="اظهارنامه ای با شماره کوتاژ ".$Cotag." اصلا وارد سیستم نگردیده است.";
				return $Error;
			}
			else if( $File->LastProgress() instanceof ReviewProgressManual)
			{
				$Error="این اظهارنامه قبلا به عنوان پرونده مختومه قبلی ثبت گردیده.";
				return $Error;
				
			}
			else
			{
				if ($File->LastReviewer()==null)
				{
					$Error="این اظهارنامه هنوز برای بازبینی تخصیص نیافته است.";
					return $Error;
					
				}	
				elseif ($File->LastReview()==null)
				{
					$Error="بازبینی اظهارنامه هنوز تکمیل نگردیده است.";
					return $Error;
				}
				else if( $File->LastProgress() instanceof ReviewProgressFinish)
				{
					$Error="این اظهارنامه قبلا مختومه شده.";
					return $Error;
					
				}
				else #FIXME: ensure logic blacklisted all errors
				{
					$User=MyUser::CurrentUser();
					var_dump($SubjectID);
					$R=new ReviewProgressManualcorrespondence($File,$User,$Newmailnum,$SubjectID,$Destination,$Comment);
					ORM::Persist($R);
					return true;
				}
				
			}
		}
		
	}
}