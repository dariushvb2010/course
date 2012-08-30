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
	9=>array('ProcessRegister'=>18, 'Review_ok'=>7, 'Review_nok'=>9),
	18=>array('Senddemand_demand'=>40),
	40=>array('Protest'=>42,'Prophecy_first'=>41),
	41=>array('Protest'=>42,'P1415'=>1514),
	42=>array('ProcessAssign'=>43),
	43=>array('Judgement_ok'=>44,'Judgement_nok'=>45,'Judgement_commission'=>59,'Judgement_setad'=>55),
	44=>array('ProcessConfirm_ok'=>80,'ProcessConfirm_nok'=>45),
	45=>array('Senddemand_karshenas'=>46),
	46=>array('Prophecy_second'=>47,'Protest'=>49),
	47=>array('Payment'=>80,'Protest'=>49,'P1415'=>1415),
	49=>array('ProcessAssign'=>50),
	50=>array('Judgement_ok'=>51,'Judgement_setad'=>55,'Judgement_commission'=>59),
	51=>array('Processconfirm_ok'=>80,'Processconfirm_nok'=>55),
	52=>array('Judgement_ok'=>53,'Judgement_commission'=>59,'Judgement_setad'=>55),
	53=>array('Processconfirm_ok'=>54,'Processconfirm_nok'=>55),
	54=>array('Refund'=>80),
	75=>array('Feedback_setad_toowner'=>68,'Feedback_setad_togomrok'=>56),
	56=>array('Senddemand_setad'=>57),	
	57=>array('Prophecy_setad'=>58,'Protest'=>59),
	58=>array('Protest'=>59,'Payment'=>68,'P1415'=>1415),
	59=>array('Forward_commission'=>60),
	60=>array('Feedback_commission_toowner'=>68,'Feedback_commission_togomrok'=>62),
	62=>array('Protest'=>64,'Prophecy_commission'=>63),
	63=>array('Protest'=>64,'Payment'=>68,'P1415'=>1415),
	64=>array('Forward_appeals'=>65),	
	65=>array('Feedback_appeals_toowner'=>68,'Feedback_appeals_togomrok'=>67),
	67=>array('Payment'=>68),
	69=>array('Protest'=>70),
	70=>array('ProcessAssign'=>52),
	);
	
	Public static $ProcessList=array(
	'Scan'=>'*********',
	'Start_cotagbook'=>'ورود سند در دفتر کوتاژ',
	'Give_cotagbook_to_archive'=>'تحویل دادن ـ دفتر کوتاژ به بایگانی',
	'Get_archive_from_cotagbook'=>'تحویل گرفتن ـ بایگانی از دفتر کوتاژ',
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
	'Senddemand_demand'=>array(
			'Label'=>'ارسال مطالبه نامه',
			'IsPrinting'=>true,
			),
	'Senddemand_setad'=>'ارسال رای دفاتر ستادی به صاحب کالا',
	'Senddemand_karshenas'=>'ارسال نظر کارشناس',
	'Refund'=>'استرداد',
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
	'ProcessRegister'=>array('Label'=>'ثبت کلاسه'),
	'ProcessAssign'=>'تحویل به کارشناس',
	'Payment'=>'تمکین و پرداخت',
	'Protest'=>'اعتراض صاحب کالا',
	'Judgement_ok'=>'قبول اعتراض',
	'Judgement_nok'=>'رد اعتراض',
	'Judgement_commission'=>'نظر کارشناس ارسال به کمیسیون',
	'Judgement_setad'=>'نظر کارشناس ارسال به دفاتر ستادی',
	'ProcessConfirm_ok'=>'تایید مدیر',
	'ProcessConfirm_nok'=>'عدم تایید مدیر',
	'P1415'=>'ماده1415',
	);
	
	public static $Persian=array(
	'setad'=>"دفاتر ستادی",
	'commission'=>'کمیسیون',
	'appeals'=>'کمیسیون تجدید نظر',
	'karshenas'=>'کارشناس'
	);
	
	public static $Name2State=array(	
		'CotagStart'=>2,
		'CotagSend'=>3,
		'Cotag'=>array('CotagStart','CotagSend'),
		'archive'=>4,
		'Assignable'=>'archive',
		'reviewing'=> 5,
		'review_notok'=> 9,
		'ProcessRegister'=> 18,
		'Prophecy_first'=>41,
		'Prophecy_second'=>47,
		'Prophecy_setad'=>58,
		'Prophecy_commission'=>63,
		'Senddemand_demand'=>40,
		'Senddemand_setad'=>57,
		'Senddemand_karshenas'=>46,
		'Ebtalable'=>array(2,3,4,5,9,11,7),
		'Prophecies'=>array('Prophecy_first','Prophecy_second','Prophecy_setad','Prophecy_commission'),
		'Senddemands'=>array('Senddemand_demand','Senddemand_setad','Senddemand_karshenas'),
		'Mokatebat'=>array('review_notok','ProcessRegister','Prophecies','Senddemands'),
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
		if(!is_array($Temp))
			$Temp=Array($Temp);
		
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
	 * an array with keys as state english name and value as persian label name
	 * @param integer $state
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
		//return new FileProgressclass($a);
	}

	
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