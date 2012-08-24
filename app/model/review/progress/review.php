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
	
	function Result($type='field')
	{
		if ($type=='persian'){
			return $t[$this->Result];
		}
		return $this->Result;
	}
	
	public static function PersianResult($Value)
	{
		$t=array(0=>'مشکلدار',
				1=>'بدون مشکل');
		
		return (isset($t[$Value])?$t[$Value]:$Value);
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

	public static function PersianProvision($Value)
	{
		$t=array(''=>'بدون خطا',
				'109'=>"109",
				'248'=>"248",
				'528'=>'528',);

		return (isset($t[$Value])?$t[$Value]:$Value);
	}
	/**
	 * @Column(type="string")
	 * @var string
	 */
	protected $Difference;
	function Difference($type='field')
	{
		if ($type=='persian'){
			return self::PersianDifference($this->Difference);
		}
		return $this->Difference;
	}

	public static function PersianDifference($Value)
	{
		$t=array('Tariff'=>'تعرفه',
				'Value'=>'ارزش',
				'Other'=>'سایر',
		);

		$rr=explode(',', $Value);
		if ($rr==null)return "";
		foreach ($rr as $v){
			$rr2[]=(isset($t[$v])?$t[$v]:$v);
		}
		return implode(',',$rr2);
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
	function Amount($format='normal')
	{
		if($format=='formatted'){
			return number_format($this->Amount);
		}else{
			return $this->Amount;
		}
	}

	function SetAmount($a)
	{
		$this->Amount=$a;
	}

	function __construct(ReviewFile $File=null, $Dif=null,$Amount=null, $IfPersist=true)
	{
		parent::__construct($File,null, $IfPersist);
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
			$sum.='مشکلدار طبق کلاسه '.v::b("«".$this->Provision."»").
			" با علت تفاوت ".v::b("«".$this->Difference('persian')).
			"»"." و مبلغ تفاوت ".v::b("«".$this->Amount('formatted')."»")." ریال ";
		else
			$sum.=' بدون مشکل ';

		$sum.='اعلام گردید .';
		return $sum;
	}

	function  Title()
	{
		return "کارشناسی";
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
		$Reviewer=MyUser::CurrentUser();
		if ($Cotag<1 || $Cotag==null)
		{
			$Error=v::Ecnv($Cotag);
			return $Error;
		}
		else
		{
			$File=b::GetFile($Cotag);

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
					$R=new ReviewProgressReview($File,"","", false);
					$R->SetResult(1);
					$R->SetProvision("");
					$ch=$R->Check();
						
					if(is_string($ch))
						return $ch;
						
					$R=new ReviewProgressReview($File,"","", true);
					$R->SetResult(1);
					$R->SetProvision("");
					$R->Apply();
						
					ORM::Persist($R);
					return true;
				}
			}
		}
	}
	/**
	 *
	 * یک فرایند بازبینی به فایل با کوتاژ داده شده اضافه می کند
	 * با دریافت ورودی های ارسالی توسط فرم و تایپ اضافه کردن یا تغییر
	 * @param array $data
	 * @param string $type  {'Add','Edit'}
	 * @author morteza Kavakebi
	 */
	public function NewReview($data,$type='Add')
	{
		$data['User'] = MyUser::CurrentUser();

		$validation=$this->ValidateCorrectFilterInput($data,$type);
		if(is_string($validation))
			return $validation;
		else
			$validInput=$validation;

		$File=b::GetFile($validInput['Cotag']);

		$R=new ReviewProgressReview($File,$validInput['Difference'],$validInput['Amount'], false);
		$R->SetResult($validInput['Result']);
		$R->SetProvision($validInput['Provision']);
		$ch=$R->Check();

		if(is_string($ch))
			return $ch;

		if($type=='Edit'){
			$ProgReview=$File->LastReview();
			$ProgReview->kill();
		}

		$R=new ReviewProgressReview($File,$validInput['Difference'],$validInput['Amount'], true);
		$R->SetResult($validInput['Result']);
		$R->SetProvision($validInput['Provision']);
		$ch=$R->Apply();

		ORM::Persist($R);
		return $R;
	}

	/**
	 *
	 * @param integer $Cotag
	 * @author morteza kavaebi
	 */
	public function IsEditable($Cotag=null)
	{
		$data['User'] = MyUser::CurrentUser();
		$data['Cotag'] = $Cotag;
		return $this->ValidateCorrectFilterInput($data,'Editable');
	}

	/**
	 *
	 * @param integer $Cotag
	 * @author morteza kavaebi
	 */
	public function IsAddable($Cotag=null)
	{
		$data['User'] = MyUser::CurrentUser();
		$data['Cotag'] = $Cotag;
		return $this->ValidateCorrectFilterInput($data,'Addable');
	}

	/**
	 *
	 * validates corrects and filters the inputs
	 * and returns an array of corrected filtered values
	 * @param array $dataArray
	 * @param string $type {'Add','Edit','Addable','Editable'}
	 * @return array OR {string of error}
	 * @author morteza Kavakebi
	 */
	private function ValidateCorrectFilterInput($dataArray,$type)
	{
		$Cotag		=$dataArray['Cotag'];
		$Result		=$dataArray['Result'];

		if(is_array($dataArray['Difference'])){
			$Difference	=implode(',',$dataArray['Difference']);
		}else{
			$Difference	=$dataArray['Difference'];
		}

		$Provision	=$dataArray['Provision'];
		$Amount		=$dataArray['Amount'];
		$Reviewer	=$dataArray['User'];

		$Amount=str_replace(",", "", $Amount);
		//----------------input Validation--------------------
		if ($Cotag<1 && $Cotag==null)
			return v::Ecnv($Cotag);

		$File=b::GetFile($Cotag);

		if ($File==null)
			return "یافت نشد.";

		if(!($File->GetClass()==0 OR $File->GetClass()==null))
			return "این اظهارنامه کلاسه شده است. ویرایش ممکن نیست.";

		$lastreviewer=$File->LastReviewer();
		$ProgReview=$File->LastReview();

		if ($lastreviewer==null)
			return "این اظهارنامه هنوز به کارشناس جهت بازبینی تخصیص نیافته است.";

		if ($lastreviewer!=$Reviewer)
			return "کارشناس بازبینی این اظهارنامه شما نیستید.";

		if(strstr($type,'Edit'))
		{
			if($ProgReview==null)
				return "این اظهارنامه بازبینی نشده است";
				
		}
		if(!strstr($type,'Edit'))
		{
			if($ProgReview!=null)
				return "این اظهارنامه قبلا کارشناسی شده است،برای ویرایش آن به صفحه ی ویرایش مراجعه نمایید.";

		}


		if(!strstr($type,'able'))//NOT {Editable,Addable}
		{
			if($Result==0)
			{
				if(strlen($Provision)==0 OR $Provision==null)
					return 'شماره کلاسه انتخاب نشده است.';

				if(	($Provision=='248' OR $Provision=='528'))
				{
					if(strlen($Difference)==0)
						return 'علت تفاوت انتخاب نشده است.';

					if($Amount==0 OR $Amount<20000 OR !is_numeric($Amount))
						return 'مبلغ تفاوت نامناسب یا کم است.';
				}
			}

				
				
		}
		// 		$LLP=$File->LLP();
		// 		if(!($LLP instanceof ReviewProgressReivew || $LLP instanceof ReviewProgressAssign))
			// 		{
		// 			$Error="کارشناس هنوز تخصیص نیافته است یا اینکه از تاریخ بازبینی اظهارنامه گذشته است.";
		// 			return $Error;
		// 		}

		//----------------Correction of input--------------------
		if($Result==1){
			$Provision="";
			$Difference="";
			$Amount="";
		}
		//----------------Filtering input--------------------
		$validatedInput['Provision']	= $Provision;
		$validatedInput['Result']		= $Result;
		$validatedInput['Difference']	= $Difference;
		$validatedInput['Amount']		= $Amount;
		$validatedInput['Cotag']		= $Cotag;

		return $validatedInput;
}

public function ReviewPercentage()
{
	$r1=j::SQL("SELECT COUNT(*) as c FROM app_ReviewProgressReview WHERE Result=1");
	$r2=j::SQL("SELECT COUNT(*) as c FROM app_ReviewProgressReview WHERE Result=0 AND Provision=528");
	$r3=j::SQL("SELECT COUNT(*) as c FROM app_ReviewProgressReview WHERE Result=0 AND Provision=248");
	$r4=j::SQL("SELECT COUNT(*) as c FROM app_ReviewProgressReview WHERE Result=0 AND Provision=109");
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

public function BazbiniPerMonth()
{
	$r=j::SQL("SELECT COUNT(P.ID) as count,
			PMONTH(FROM_UNIXTIME(P.CreateTimestamp))as month,
			PMONTHNAME(FROM_UNIXTIME(P.CreateTimestamp))as monthname,
			PYEAR(FROM_UNIXTIME(P.CreateTimestamp))as year
			FROM app_ReviewProgress AS P
			WHERE P.Type='Start'
			GROUP BY PMONTH(FROM_UNIXTIME(P.CreateTimestamp)),PYEAR(FROM_UNIXTIME(P.CreateTimestamp))
			ORDER BY year,month");
	return $r;
}

/**
 *
 * @param integer $StartTimestamp
 * @param integer $EndTimestamp
 * @param group by field $fieldname
 * @return array
 */
public function ReviewStatistics($StartTimestamp,$EndTimestamp,$fieldname)
{
	$r=j::DQL("SELECT P.{$fieldname},COUNT(P),SUM(P.Amount)
	FROM ReviewProgressReview AS P
	WHERE P.CreateTimestamp BETWEEN ? AND ? AND P.Dead=0
	GROUP BY P.{$fieldname}
	ORDER BY P.{$fieldname}",$StartTimestamp,$EndTimestamp);
	$r2=array();
	foreach($r as $k=>$v){
		$r2[]=array(
				$fieldname=>$v[$fieldname],
				'Count'=>$v[1],
				'Sum'=>$v[2],
		);
	}
	return $r2;
}

public function NotokedList($Offset=0,$Limit=100,$Sort="Cotag", $Order="ASC")
{
	$StartTimestamp=0;
	$EndTimestamp=10000000000;
	$r=j::ODQL("SELECT P,F
			FROM ReviewProgressReview AS P JOIN ReviewFile AS F
			WHERE P.CreateTimestamp BETWEEN ? AND ? AND P.Dead=0
			ORDER BY P.Difference",$StartTimestamp,$EndTimestamp);

	return $r;
}

	/**
	 * @author dariush
	 * @tutorial  bazdoc/review/progress/start/monthlystart.html
	 * @see http://dev.mysql.com/doc/refman/5.5/en/date-and-time-functions.html#function_date-add
	 *
	 * --------------time line ------->-->---->----->------>----->------>------->------>------>---->----->----->----->----->-----
	 * ********|***M(monthCount-1)***|  ...  |***M(2)***|***M(1)***|***M(0)***|startMonth=4|***M***|***M***|***M***|***now***|****
	 * --------------------------------------------------------------------------------------------------------------------------
	 * مقدارهای برگشتی بر حسب میلیون ریال می باشد
	 * @param integer $monthCount: number of monthes to show
	 * @param integer $startMonth: how many monthes ago
	 * @return 2DArray ['']->{[0]->2342, [1]->2342, ...}, ['109']->{[0]->2342, [1]->2342, ...}, ['248']->{[0]->2342, [1]->2342, ...}, ['528']->{[0]->2342, [1]->2342, ...}, ['total']->{['']->0,['109']->0, ['248']->0, ['528']->0}
	 */
	public function ReviewAmountPerMonth($monthCount, $startMonth=0)
	{
		//-----get the numberof days of last month---------------------------
		$c = new CalendarPlugin();
		$t = $c->TodayJalaliArray();
		$dayOfMonth= $t[2]; //our purpose--------------------------------
		$addDays = 30 - $dayOfMonth;
	
	
		$oc=j::SQL("SELECT SUM(R.Amount) as amount,R.Provision as provision,
				floor(
				DATEDIFF(
				curdate() + INTERVAL ? DAY - INTERVAL ? MONTH,
				FROM_UNIXTIME(P.CreateTimestamp)
		)/30.3
		) as month
				FROM app_ReviewProgress AS P join app_ReviewProgressReview AS R on P.ID=R.ID
				WHERE P.Type=?
				AND R.Result=0
				AND P.Dead=0
				AND DATEDIFF(
				curdate() + INTERVAL ? DAY - INTERVAL ? MONTH,
				FROM_UNIXTIME(P.CreateTimestamp)
		) >= 0
				GROUP BY month, provision ",$addDays,$startMonth,"Review",$addDays,$startMonth);
		
		$res["248"]=array();//----[0]->299000 [1]->40000
		$res["109"]=array();
		$res["528"]=array();
		$res[""]=array();//some of them has not been set unfortunately
		$res["total"]=array(
				""=>0,
				"109"=>0,
				"248"=>0,
				"528"=>0
		);
		//-----------------making $res------------------
		foreach ($oc as $t)
		{
			$t=array_pop($oc);
			$month=$t['month'];
			$provision = $t['provision'];
			if($month*1<$monthCount)
			{
				$res[$provision][$month*1+$startMonth] += $t['amount'];
				$res["total"][$provision]+= $t['amount'];
			}
		}
	
		//------------------fill none existing fields of arrays and sort---------
		foreach ($res as $key=>$pv) //only 4 : $res['248'], $res['109'], $res['528'], $res['']
		{//ex: $pv = $p['248']
	
			//------------------make total array------------------
			if($key=="total")
			{
				foreach ($res[$key] as $k=>$v)//ex: $k=248, $v=232398000
				{
					$res[$key][$k]= round($v/1000000,1);
				}
				continue;
			}//---------------------------------------------------
	
			for($i=$startMonth;$i<$startMonth+$monthCount;$i++)
			{
				if(!array_key_exists($i,$pv))
				{
					$pv[$i]=0;//array('count'=>0,'month'=>$i);
				}
				else
					$pv[$i]=round($pv[$i]/1000000,1);
			}
			krsort($pv);
			$res[$key]=$pv; // !important : for applying changes in $pv (overwrite changes into $res)
			//--------sort array by key high to low
		}
		return $res;
	}
}