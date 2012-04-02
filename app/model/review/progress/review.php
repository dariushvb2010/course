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
	 * true= بدون مشکل
	 * false=مشکل دار
	 * @Column(type="boolean")
	 * @var boolean
	 */
	protected $Result;
	function SetResult($value){
		$this->Result=$value;
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
	function Difference($type='field')
	{
		$t=array('Tariff'=>'تعرفه',
		'Value'=>'ارزش',
		);
		if ($type=='persian'){
			return (isset($t[$this->Difference])?$t[$this->Difference]:$this->Difference); 
		}
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
			$sum.='مشکلدار طبق کلاسه'."<b> «".$this->Provision."» </b>"."با علت تفاوت"."<b> «".$this->Difference('persian')."» </b>"."و مبلغ تفاوت"."<b> «".$this->Amount."» </b>";
		else
			$sum.=' بدون مشکل ';
		
		$sum.='اعلام گردید .';
		return $sum;
	}
	
	function  Title()
	{
		return "بازبینی";
	}
	function Event()
	{
		if(!isset($this->Result))
			throw new Exception("Result is not set!");
		if($this->Result)
			return "Review_ok";
		else 
			return "Review_nok";
	}
}

use \Doctrine\ORM\EntityRepository;
class ReviewProgressReviewRepository extends EntityRepository
{
	
	
/**
 * یک فرایند بازبینی  بدون مشکل به فایل با کوتاژ داده شده اضافه می کند 
 * @param integer $Cotag
 * @return string for error and true for success
 */
	public function AddReviewOked($Cotag=null)
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
				$LLP=$File->LLP();
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
				else if(!($LLP instanceof ReviewProgressReivew || $LLP instanceof ReviewProgressAssign))
				{
					$Error="کارشناس هنوز تخصیص نیافته است یا اینکه از تاریخ بازبینی اظهارنامه گذشته است.";
					return $Error;
				}
				else
				{
					$R=new ReviewProgressReview($File,$Reviewer,"","");
					$R->SetResult(1);
					$R->SetProvision("");
					$ch=$R->Apply();
					if(is_string($ch))
						return $ch;
					ORM::Persist($R);
					return true;	
				}					
			}
		}
	}
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
				$LLP=$File->LLP();
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
				else if(!($LLP instanceof ReviewProgressReivew || $LLP instanceof ReviewProgressAssign))
				{
					$Error="کارشناس هنوز تخصیص نیافته است یا اینکه از تاریخ بازبینی اظهارنامه گذشته است.";
					return $Error;
				}
				else
				{
					if (count($_POST))
					{
							if($_POST['Result']==0 AND count($_POST['Provision'])==0)
								return 'شماره کلاسه انتخاب نشده است.'; 
								 
							if(is_array($_POST['Provision']))
								$Provision=$_POST['Provision'];
							else 
								$Provision="";
							if(is_array($_POST['Difference']))
								$Difference=implode(",",$_POST['Difference']);
							else 
								$Difference="";
							//echo"<br>pro dif: ". $Provision.$Difference."<br>";
							$Amount=($_POST['Amount']==null ? "" : $_POST['Amount']);
							$Amount=str_replace(",", "", $Amount);
							
							$R=new ReviewProgressReview($File,$Reviewer,$Difference,$Amount);
							$R->SetResult($_POST['Result']);
							$R->SetProvision($Provision);
							$ch=$R->Apply();
							if(is_string($ch))
								return $ch;
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
						if($_POST['Result']==0 AND count($_POST['Provision'])==0)
							return 'شماره کلاسه انتخاب نشده است.';
						var_export($_POST['Provision']);	
						if($_POST['Provision']!=null)
							$Provision=$_POST['Provision'];
						if($_POST['Difference']!=null)
						$Difference=implode(",",$_POST['Difference']);
						$Amount=($_POST['Amount']==null ? "" : $_POST['Amount']);
						$Amount=str_replace(",", "", $Amount);
						$ProgReview->kill();
						$R=new ReviewProgressReview($File,$Reviewer,$Difference,$Amount);
						$R->SetResult($_POST['Result']);
						$R->SetProvision($Provision);
						$ch=$R->Apply();
						if(is_string($ch))
							return $ch;						
						ORM::Persist($R);
						ORM::Persist($ProgReview);
						return $R;
					}
				}
			}
		}
	}
	
	public function ReviewPercentage()
	{
		$r1=j::SQL("SELECT COUNT(*) as c FROM App_ReviewProgressReview WHERE Result=1");
		$r2=j::SQL("SELECT COUNT(*) as c FROM App_ReviewProgressReview WHERE Result=0 AND Provision=528");
		$r3=j::SQL("SELECT COUNT(*) as c FROM App_ReviewProgressReview WHERE Result=0 AND Provision=248");
		$r4=j::SQL("SELECT COUNT(*) as c FROM App_ReviewProgressReview WHERE Result=0 AND Provision=109");
		$res=array('oked'=>$r1[0]['c'],'a528'=>$r2[0]['c'],'a248'=>$r3[0]['c'],'a109'=>$r4[0]['c']);
		return $res;
	}

	public function karshenas_work_lastmounth()
	{
		$r=j::ODQL("SELECT COUNT(P.ID) as co,U.ID as user
					FROM ReviewProgress AS P JOIN P.User AS U 
					WHERE P INSTANCE OF	ReviewProgressReview AND ".time()."-P.CreateTimestamp<60*60*24*30 
					GROUP BY P.User");
		foreach($r as $s)
		{
			$u=j::ODQL("SELECT U FROM MyUser U WHERE U.ID=?",$s['user']);
			$r2[]=array('count'=>$s['co'],'user'=>$u[0]);
		}
		return $r2;
	}
}