<?php

use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity
 * @entity(repositoryClass="ReviewProgressReviewRepository")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="Type", type="string")
 * */
class ReviewProgressReview extends ReviewProgress
{
	/**
	 * @Column(type="boolean")
	 * @var boolean
	 */
	protected $Result;
	function SetResult($value){
		$this->Result=$value;
		if (!$value AND $this->File){
			$this->File->SetState(9);
		}
	}
	function Result(){
		return $this->Result;
	}
	
	/**
	* @Column(type="string")
	* @var string
	*/
	protected $Provision;
	function SetProvision($value){
		if($value==null)
			$this->Provision="";
		else
			$this->Provision=$value;
	}
	function Provision(){
		return $this->Provision;
	}
	function ProvisionArray()
	{
		$res = explode(",", $this->Provision);
		return $res;
	}
	/**
	* @Column(type="string")
	* @var string
	*/
	protected $Difference;
	function Difference()
	{
		return $this->Difference;
	}
	function DifferenceArray()
	{
		$res = explode(",", $this->Difference);
		return $res;
	}
	/**
	* @Column(type="integer")
	* @var string
	*/
	protected $Amount;
	function Amount()
	{
		return $this->Amount;
	}
	function SetAmount($a)
	{
		$this->Amount=$a;
	}
	
	function __construct(ReviewFile $File=null,MyUser $User=null,$Dif=null,$Amount=null)
	{
		parent::__construct($File,$User);
		$this->SetResult(false);
		$this->Provision="";
		if($Dif==null)
			$this->Difference="";
		else
			$this->Difference=$Dif;
		if($Amount==null)
			$this->Amount=0;
		else
			$this->Amount=$Amount;
	}
	
	function  Summary()
	{
		$sum="کارشناسی انجام شد و نتیجه آن، ";
		if($this->Result==false)
			$sum.='مشکلدار طبق ماده'."<b> «".$this->Provision."» </b>"."با علت تفاوت"."<b> «".$this->Difference."» </b>"."و مبلغ تفاوت"."<b> «".$this->Amount."» </b>";
		else
			$sum.=' بدون مشکل ';
		
		$sum.='اعلام گردید .';
		return $sum;
	}
	
	function  Title()
	{
		return "بازبینی";
	}

}

use \Doctrine\ORM\EntityRepository;
class ReviewProgressReviewRepository extends EntityRepository
{
	
	
/**
 * یک فرایند بازبینی به فایل با کوتاژ داده شده اضافه می کند 
 * @param integer $Cotag
 * @param request array  $_POST که از یک فرم که نتیجه بازبینی را می فرستد آمده است که ماده هم دارد 
 * @return string for error and true for success
 */
	public function AddReview($Cotag=null,$_POST=null)
	{
		
		if ($Cotag<1 || $Cotag==null)
		{
			$Error="کوتاژ ناصحیح است.";
			return $Error;
		}
		else 
		{
			$File=ORM::query(new ReviewFile)->GetRecentFile($Cotag);

			if ($File==null)
			{
				$Error ="یافت نشد.";
				return $Error;
			}
			else
			{
				$lastreviewer=$File->LastReviewer();
				$ProgReview=$File->LastReview();
				$LastProgress=$File->LastProgress();
				$Reviewer=MyUser::CurrentUser();
				if ($lastreviewer==null)
				{
					$Error="این اظهارنامه هنوز به کارشناس جهت بازبینی تخصیص نیافته است.";
					return $Error;
				}	
				elseif ($ProgReview  !=null)
				{
					$Error="اظهارنامه مذکور قبلا بازبینی شده است.به قسمت ویرایش نتیجه بازبینی مراجعه نمایید.";
					return $Error;
				}
				elseif ($lastreviewer!=$Reviewer)
				{
					$Error="کارشناس بازبینی این اظهارنامه شما نیستید.";
					return $Error;
				}
				else if(!($LastProgress instanceof ReviewProgressReivew || $LastProgress instanceof ReviewProgressAssign))
				{
					$Error="کارشناس هنوز تخصیص نیافته است یا اینکه از تاریخ بازبینی اظهارنامه گذشته است.";
					return $Error;
				}
				else
				{
					if (count($_POST))
					{
							//var_dump($_POST);
							if(is_array($_POST['Provision']))
								$Provision=implode(",", $_POST['Provision']);
							else 
								$Provision="";
							if(is_array($_POST['Difference']))
								$Difference=implode(",",$_POST['Difference']);
							else 
								$Difference="";
							//echo"<br>pro dif: ". $Provision.$Difference."<br>";
							$Amount=($_POST['Amount']==null ? "" : $_POST['Amount']);
							
							$R=new ReviewProgressReview($File,$Reviewer,$Difference,$Amount);
							$R->SetResult($_POST['Result']);
							$R->SetProvision($Provision);
							//echo "review:<br>";
							//ORM::Dump($R);echo "<br>";
							ORM::Persist($R);
							return true;
							
//						}
					}
				}					
			}
		}
	}
	public function EditReview($Cotag=null,$_POST=null)
	{
		if ($Cotag<1 && $Cotag==null)
		{
			$Error="کوتاژ ناصحیح است.";
			return $Error;
		}
		else
		{
			$File=ORM::query(new ReviewFile)->GetRecentFile($Cotag);
	
			if ($File==null)
			{
				$Error ="یافت نشد.";
				return $Error;
			}
			else if(!($File->GetClass()==0 OR $File->GetClass()==null))
			{
				$Error="این اظهارنامه کلاسه شده است. ویرایش ممکن نیست.";
				return $Error;
			}
			else
			{
				$lastreviewer=$File->LastReviewer();
				$ProgReview=$File->LastReview();
				$Reviewer=MYuser::CurrentUser();
				
				if ($lastreviewer==null)
				{
					$Error="این اظهارنامه هنوز به کارشناس جهت بازبینی تخصیص نیافته است.";
					return $Error;
				}
				elseif($ProgReview==null)
				{
					$Error="این اظهارنامه بازبینی نشده است";
					return $Error;
				}
				elseif ($lastreviewer!=$Reviewer)
				{
					$Error[]="کارشناس بازبینی این اظهارنامه شما نیستید.";
					return $Error;
				}
				else
				{
					if (count($_POST))
					{
						if($_POST['Provision']!=null)
							$Provision=implode(",", $_POST['Provision']);
						if($_POST['Difference']!=null)
						$Difference=implode(",",$_POST['Difference']);
						$Amount=($_POST['Amount']==null ? "" : $_POST['Amount']);
						$R=new ReviewProgressReview($File,$Reviewer,$Difference,$Amount);
						$R->SetResult($_POST['Result']);
						$R->SetProvision($Provision);
						ORM::Persist($R);
						return $R;
					}
				}
			}
		}
	}
}