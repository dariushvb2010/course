<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @entity(repositoryClass="ReviewProgressPostRepository")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="Type", type="string")
 * */
class ReviewProgressPost extends ReviewProgress
{
	/**
	 * @ManyToOne(targetEntity="ReviewMail", inversedBy="Post")
	 * @JoinColumn(name="MailID", referencedColumnName="ID")
	 */
	protected $Mail;
	function Mail()
	{
		return $this->Mail;
	}
	/**
	 * @Column(type="boolean")
	 * @var boolean
	 */
	protected $IsSend;
	function IsSend()
	{
		return $this->IsSend;
	}

	function __construct(ReviewFile $File=null, MyUser $User=null, ReviewMail $Mail=null,$IsSend=1)
	{
		parent::__construct($File,$User);
		$this->IsSend=$IsSend;
		$this->Mail=$Mail;
	}

	function  Summary()
	{
		if($this->IsSend)
		{
			if($this->Mail)
			{
				$Mail=$this->Mail;
				if($Mail->State()==0)
				{
					return "این اظهارنامه آماده فرستادن  از ".$Mail->Sender()->Topic()."به ".$Mail->Receiver()->Topic()."است";
				}
				else if($Mail->State()==1)
				{
					return " این اظهارنامه   از ".$Mail->Sender()->Topic()." به ".$Mail->Receiver()->Topic()." با شماره نامه ".$this->Mail->Num()." فرستاده شد  .";
				}
			}

		}
		else
		{
			if($this->Mail && $this->Mail->Sender()&& $this->Mail->Receiver())
			return "این اظهارنامه از ".$this->Mail->Sender()->Topic()."دریافت شد "."توسط".$this->Mail->Receiver()->Topic();

		}

	}
	function Title()
	{
		return "فرستادن پرونده";
	}

}


use \Doctrine\ORM\EntityRepository;
class ReviewProgressPostRepository extends EntityRepository
{
	/**
	 *
	 * Enter description here ...
	 * @param ReviewFile $File
	 * @return string on error true on sucsess
	 */
	public function AddToFile(ReviewFile $File,ReviewMail $Mail=null, $IsSend=null,$isSameMail=0)
	{

		if (!$File)
		{
			$Error="کوتاژ ناصحیح است.";
			return $Error;
		}
		if($IsSend==0)
		{
			$Last=$File->LastProgress();
			if(!$Last)
			{
				$Error="اظهارنامه با کوتاژ ".$File->Cotag()."هیچ فرایندی ندارد.";
				return $Error;
			}
			if(!($Last instanceof ReviewProgressPost))
			{
				$Error="آخرین فرایند اظهارنامه با کوتاژ ".$File->Cotag()."ارسال نبوده است.";
				return $Error;
			}
			if($isSameMail)
			{
				if($Last->Mail()!= $Mail)
				{
					$Error="نامه ارسال باید برابر نامه وصول باشد.";
					return $Error;
				}
			}
			if($Last->isSend()!=1)
			{
				$Error="آخرین فرایند اظهارنامه با کوتاژ ".$File->Cotag()."ارسال نبوده است.بلکه دریافت بوده  است.";
				return $Error;
			}
			$countSend=ORM::Query(new ReviewMail)->GetCountType($Mail,1);
			$countReceive=ORM::Query(new ReviewMail)->GetCountType($Mail,0);
			if($countReceive+1==$countSend)
			{
				
				$Mail->SetState(2);
				
			}	
			
		}
		else
		{
			$Last=$File->LastProgress();
			if(!$Last)
			{
				$Error="اظهارنامه با کوتاژ ".$File->Cotag()."هیچ فرایندی ندارد.";
				return $Error;
			}
			if(($Last instanceof ReviewProgressPost) && $Last->IsSend()==1)
			{
				$Error="اظهارنامه با کوتاژ ".$File->Cotag()."آخرین فرایندش ارسال بوده است.";
				return $Error;
			}
		}
		if ($File->LastReviewer()==null)
		{
			$Error="اظهارنامه با کوتاژ ".$File->Cotag()."هنوز برای بازبینی تخصیص نیافته است.";
			return $Error;

		}
		elseif (!$File->LastReview() || $File->LastReview()->Result()==false)
		{
			$Error="بازبینی اظهارنامه با کوتاژ".$File->Cotag()." هنوز تکمیل نگردیده است.";
			return $Error;
		}
		$User=MyUser::CurrentUser();
		$R=new ReviewProgressPost($File,$User,$Mail,$IsSend);
		ORM::Persist($R);
		ORM::Flush();
		return true;
	}
}