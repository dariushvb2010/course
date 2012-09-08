<?php

/**
 * 
 * Finite State Mahine
 * @author sonukesh,kavakebi
 *
 */
class FsmGraph extends JModel
{

	/**
	 * 
	 * An array of dictionary for graph of state machine 
	 * progresses going out of each state with their outcome state are indicated 
	 * @var array
	 */
	private static $StateGraph=array(
	//-----------------------Review---------------------
	0=>array('Scan'=>1,'Start_cotagbook'=>2, 'Start_archive'=>4, 'Start_raked'=>14, 'Start_correspondence'=>9),
	1=>array('Scan'=>1,'Start_cotagbook'=>2, 'Start_archive'=>4, 'Start_raked'=>14, 'Start_correspondence'=>9),
	2=>array('Give_cotagbook_to_archive'=>3),
	3=>array('Get_archive_from_cotagbook'=>4),
	4=>array('Assign'=>5),
	5=>array('Review_nok'=>9, 'Review_ok'=>7),
	7=>array('Review_nok'=>9, 'Give_archive_to_raked'=>13, 'Send_archive_to_out'=>12),
	11=>array('Assign_by_manager'=>5, 'Give_archive_to_raked'=>13, 'Send_archive_to_out'=>12),
	12=>array('Receive_archive_from_out'=>11),
	13=>array('Get_raked_from_archive'=>14),
	14=>array('Send_raked_to_out'=>15, 'Give_raked_to_archive'=>16),
	15=>array('Receive_raked_from_out'=>14),
	16=>array('Get_arhive_from_raked'=>4),
	'Ebtalable'=>array('Ebtal'=>1),
	//--------------------------Correspondence-----------
	9=>array('ProcessRegister_109'=>36, 'ProcessRegister_248'=>18,'ProcessRegister_528'=>18, 'Review_ok'=>7, 'Review_nok'=>9),
	18=>array('Address_first'=>40, 'Protest_first'=>42),
	40=>array('Protest_first'=>42,'Prophecy_first'=>41),
	41=>array('Protest_first'=>42,'P7'=>780),
	42=>array('ProcessAssign'=>43),
	43=>array('ProcessReview_accept'=>44,'ProcessReview_deny'=>45,'ProcessReview_setad'=>53),
	44=>array('ProcessConfirm_ok'=>80,'ProcessConfirm_nok'=>45),
	45=>array('Address_second'=>46, 'Protest_second'=>50),
	46=>array('Prophecy_second'=>47,'Protest_second'=>50),
	47=>array('Payment'=>48,'Protest_second'=>50,'P7'=>780),
	48=>array('Clearance'=>11, 'ProcessAssign'=>79),
	49=>array('ProcessAssign'=>79, 'Clearance'=>11, 'Protest_after_p7'=>50),
	50=>array('ProcessAssign'=>51),
	51=>array('ProcessReview_accept'=>52,'ProcessReview_setad'=>53),
	52=>array('Processconfirm_ok'=>80,'Processconfirm_nok'=>53),
	53=>array('Forward_setad'=>55),
	55=>array('Feedback_setad_to_owner'=>78,'Feedback_setad_to_gomrok'=>56),
	56=>array('Address_setad'=>57,'Protest_setad'=>59),	
	57=>array('Prophecy_setad'=>58,'Protest_setad'=>59),
	58=>array('Protest_setad'=>59,'Payment'=>78,'P1415'=>781),
	59=>array('Forward_commission'=>60),
	60=>array('Feedback_commission_to_owner'=>78,'Feedback_commission_to_gomrok'=>62),
	62=>array('Protest_commission'=>64,'Prophecy_commission'=>63),
	63=>array('Protest_commission'=>64,'Payment'=>78,'P7'=>782),
	64=>array('Forward_appeals'=>65),	
	65=>array('Feedback_appeals_to_owner'=>78,'Feedback_appeals_to_gomrok'=>67),
	67=>array('Payment'=>78, 'P7'=>782),
	78=>array('Clearance'=>11, 'Assign'=>79),
	79=>array('ProcessReview_ok'=>80),
	80=>array('Clearance'=>11),
	85=>array('ProcessAssign'=>79, 'Clearance'=>11, 'Protest_after_p7'=>59),
	780=>array('Payment'=>49),
	781=>array('Payment'=>85),
	782=>array('Payment'=>78),
	'Mokatebat_Clearancable'=>array('Clearance'=>11),
	);
	
	
	
	public static $Persian=array(
	'setad'=>"دفاتر ستادی",
	'commission'=>'کمیسیون',
	'appeals'=>'کمیسیون تجدید نظر',
	'karshenas'=>'کارشناس'
	);
	
	public static $Name2State=array(	
		'CotagStart'=>array(2),
		'CotagSend'=>array(3),
		'Cotag'=>array('CotagStart','CotagSend'),
		'archive'=>array(4),
		'Assignable'=>array('archive'),
		'reviewing'=> array(5),
		'review_nok'=> array(9),
		'ProcessRegister'=> array(18),
		'Prophecy_first'=>array(41),
		'Prophecy_second'=>array(47),
		'Prophecy_setad'=>array(58),
		'Prophecy_commission'=>array(63),
		'Address_first'=>array(40),
		'Address_second'=>array(46),
		'Address_setad'=>array(57),
		'Ebtalable'=>array(2,3,4,5,9,11,7),
		'Prophecies'=>array('Prophecy_first','Prophecy_second','Prophecy_setad','Prophecy_commission'),
		'Addresses'=>array('Address_first','Address_setad','Address_second'),
		'Mokatebat_Clearancable'=>array('review_nok',18,'Prophecies','Addresses'),
		'PrintForDemand'=>array('ProcessRegister'),
	);
	
	/**
	 * 
	 * maps state name with state numbers
	 * it's Complicated isn't it? ;)
	 * @param string
	 * @return array of integer
	 * @author Morteza Kavakebi
	 */
	static function Name2State($name){
		if (!array_key_exists($name,self::$Name2State))
			return null;
		$Temp=self::$Name2State[$name];
		
		$res=array();
		foreach ($Temp as $val){
			if(is_int($val)){
				$res[]=$val;
			}else{
				$res=array_merge($res,self::Name2State($val));
			}
		}
		return array_unique($res);
	}
	
	/**
	 * Calrifies if two states match if they are string or state number
	 * as string states can have multiple number states 
	 * @param unknown_type $state1
	 * @param unknown_type $state2
	 * @author Kavakebi
	 */
	static function StateMatch($state1,$state2){
		if($state2===$state1){
			//TODO: rewise
			return true;
		}
		if(is_string($state1) AND is_int($state2)){
			 return self::StateMatch($state2,$state1);
		}
		if(is_int($state1) AND is_string($state2)){
			
			$StateArray=self::Name2State($state2);
			return in_array($state1, $StateArray);
		}
		
		return false;
	}
	
	/**
	 * 
	 * return possible progresses for each input state
	 * @param integer $state\
	 * @return array with keys as state english name and value as FsmProgress with persian label name
	 */
	static function PossibleProgresses($state){
		$Graph=self::$StateGraph;
		$ar2=array();
		foreach($Graph as $s=>$ar){
			if( self::StateMatch($s, $state))
			{
				foreach ($ar as $key=> $value){
					$c=self::GetProgressByName($key);
					$c->beginState=$state;
					$c->Name=$key;
					$c->nxtState=self::$StateGraph[$state][$key];
					$ar2[$key]=$c;
				}
			}
		}
		
		return $ar2;
	}
	
	static function GetProgressByName($name){
		$a=self::$ProcessList[$name];
		return new FsmProgress($a);
	}
	Public static $ProcessList=array(
			'Scan'=>'*********',
			'Start'=>'*********',
			'Give_cotagbook_to_archive'=>'وصول دفتر کوتاژ',
			'Get_archive_from_cotagbook'=>'دریافت از دفتر کوتاژ',
			'Assign'=>'تخصیص به کارشناس',
			'Confirm_ok'=>'*********',
			'Confirm_nok'=>'*********',
			'Review_nok'=>'*********',
			'Give_archive_to_raked'=>'*********',
			'Send_archive_to_out'=>'*********',
			'Review_ok'=>array(
					'Label'=>'*********',
					'is_MokatebatViewable'=>false,
			),
			'Review_nok'=>array(
					'Label'=>'*********',
					'is_MokatebatViewable'=>false,
			),
			'Clearance'=>array(
					'Label'=>'تسویه و مختومه',
			),
			'Assign_by_manager'=>'*********',
			'Give_archive_to_raked'=>'*********',
			'Send_archive_to_out'=>'*********',
			'Receive_archive_from_out'=>'*********',
			'Get_raked_from_archive'=>'*********',
			'Send_raked_to_out'=>'*********',
			'Give_raked_to_archive'=>'*********',
			'Receive_raked_from_out'=>'*********',
			'Get_arhive_from_raked'=>'*********',
			'Ebtal'=>array(
					'Label'=>'ابطال',
					'is_MokatebatViewable'=>false,
			),
			'Address_first'=>array(
					'Label'=>'ارسال مطالبه نامه',
			),
			'Address_setad'=>'ارسال رای دفاتر ستادی به صاحب کالا',
			'Address_second'=>'ارسال نظر کارشناس',
			'Forward_commission'=>'ارسال پرونده به کمیسیون',
			'Forward_setad'=>'ارسال به دفاتر ستادی',
			'Forward_appeals'=>'ارسال به کمیسیون تجدید نظر',
			'Feedback_appeals_toowner'=>'رای کمیسون تجدید نظر به نفع صاحب کالا',
			'Feedback_appeals_togomrok'=>'رای کمیسون تجدید نظر به نفع گمرک',
			'Feedback_commission_toowner'=>'رای کمیسون به نفع صاحب کالا',
			'Feedback_commission_togomrok'=>'رای کمیسون به نفع گمرک',
			'Feedback_setad_toowner'=>'رای دفاتر ستادی به نفع صاحب کالا',
			'Feedback_setad_togomrok'=>'رای دفاتر ستادی به نفع گمرک',
			'Prophecy_first'=>'ثبت ابلاغ مطالبه نامه',
			'Prophecy_second'=>'ثبت ابلاغ ثانویه',
			'Prophecy_setad'=>'ابلاغ رای دفاتر ستادی',
			'Prophecy_commission'=>'ابلاغ رای کمیسیون',
			'ProcessRegister_109'=>array('Label'=>'ثبت کلاسه ۱۰۹'),
			'ProcessRegister_248'=>'ثبت کلاسه ۲۴۸',
			'ProcessRegister_528'=>'ثبت کلاسه ۵۲۸',
			'ProcessAssign'=>'تحویل به کارشناس',
			'Payment'=>'تمکین و پرداخت',
			'Protest_first'=>'اعتراض صاحب کالا به مطالبه نامه',
			'Protest_second'=>'اعتراض صاحب کالا به رای کارشناس',
			'Protest_setad'=>'اعتراض صاحب کالا به رای دفاتر ستادی',
			'Protest_commission'=>'اعتراض صاحب کالا به رای کمیسیون بدوی',
			'Protest_after_p7'=>'اعتراض صاحب کالا بعد از لغو ماده هفت',
			'ProcessReview_accept'=>array(
					'Label'=> 'پذیرش اعتراض صاحب کالا',
					'is_MokatebatViewable'=>false),
			'ProcessReview_deny'=>array(
					'Label'=> 'رد اعتراض صاحب کالا',
					'is_MokatebatViewable'=>false),
			'ProcessReview_commission'=>array(
					'Label'=>'نظر کارشناس ارسال به کمیسیون',
					'is_MokatebatViewable'=>false),
			'ProcessReview_setad'=>array(
					'Label'=>'نظر کارشناس ارسال به دفاتر ستادی',
					'is_MokatebatViewable'=>false),
			'ProcessReview_ok'=>array(
					'Label'=>'تایید پرونده',
					'is_MokatebatViewable'=>false),
			'ProcessConfirm_ok'=>array(
					'Label'=>'تایید مدیر',
					'is_MokatebatViewable'=>false),
			'ProcessConfirm_nok'=>array(
					'Label'=>'عدم تایید مدیر',
					'is_MokatebatViewable'=>false),
			'P7'=>'ماده هفت',
	);
	static $StateFeatures  = array(
			2=>array(
					'Desc'=>'در دفتر کوتاژ',
					'Place'=>p::CotagBook),
			3=>array(
					'Desc'=>'تحویل شده از دفتر کوتاژ به بایگانی بازبینی',
					'Place'=>p::Archive),
			4=>array(
					'Desc'=>'در بایگانی بازبینی آماده برای کارشناس',
					'Place'=>p::Archive),
			5=>array(
					'Desc'=>'در دست کارشناسی',
					'Place'=>'دست کارشناس'),
			7=>array(
					'Desc'=> 'آماده برای ارسال به بایگانی راکد',
					'Place'=>p::Archive),
			11=>array(
					'Desc'=> 'آماده برای ارسال به بایگانی راکد',
					'Place'=>p::Archive),
			9=>array(
					'Desc'=>'آماده برای ثبت کلاسه مکاتبات',
					'Place'=>p::Archive),
			12=>array(
					'Desc'=>'ارسال شده به خارج توسط بایگانی بازبینی',
					'Place'=>'خارج'),
			13=>array(
					'Desc'=>'تحویل شده از بایگانی بازبینی به بایگانی راکد',
					'Place'=>p::Raked),
			14=>array(
					'Desc'=>'در بایگانی راکد',
					'Place'=>p::Raked),
			15=>array(
					'Desc'=>'ارسال شده به خارج توسط بایگانی راکد',
					'Place'=>'خارج'),
			16=>array(
					'Desc'=>'تحویل شده از بایگانی راکد به بایگانی بازبینی',
					'Place'=>p::Archive),
			17=>array(
					'Desc'=>'',
					'Place'=>''),
			18=>array(
					'Desc'=>'',
					'Place'=>''),
			19=>array(
					'Desc'=>'',
					'Place'=>''),
			20=>array(
					'Desc'=>'',
					'Place'=>''),
			21=>array(
					'Desc'=>'',
					'Place'=>''),
			22=>array(
					'Desc'=>'',
					'Place'=>''),
			23=>array(
					'Desc'=>'',
					'Place'=>''),
			);

	
	/**
	*
	* this function checks if the progress is possible for this situation of file
	* according to current state and progress given to it.
	* @param integer $currentstate
	* @param string $progressname
	* @return boolean
	*/
	static function IsPossible($currentstate,$progressname){
		$ar=self::PossibleProgresses($currentstate*1);
		return array_key_exists($progressname,$ar);
	}
	
	/**
	 * 
	 * this function returns the next state of files 
	 * current state and progress given to it.
	 * @param integer $currentstate
	 * @param string $progressname
	 * @return integer
	 */
	static function NextState($currentstate,$progressname){
		$state=$currentstate*1;
		$Graph=self::$StateGraph;
		$ar2=array();
		foreach($Graph as $s=>$ar){
			$StateArray=self::Name2State($s);
			if( (is_int($s) AND $s===$state) OR (is_string($s) AND in_array($state, $StateArray)) )
			{
				if(array_key_exists($progressname,$ar)){
					return $ar[$progressname];
				}
			}
		}
		return null;
	}
	static function IsReviewerDisturbState($state)
	{
		return 'deprecated call morteza';
		if($state==43 || $state==50 ||$state==52)
			return true;
		return false;
	}
	static function IsReviewerConfirmState($state)
	{
		return 'deprecated call morteza';
		if ($state==71)
			return true;
		return false;
	}
	function __construct()
	{
		
		 
	}
	
	static function Moderate10(){echo 'ss';}

	static function testFsm(){
		//-----------IsPossible--------//
		assert(self::IsPossible(11, 'Assign_by_manager'));
		assert(!self::IsPossible(12, 'Assign_by_manager'));
		//-----------PossibleProgra--------//
		self::PossibleProgresses(5);
	}
}