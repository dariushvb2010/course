<?php

/**
 * 
 * Finite State Mahine
 * @author sonukesh
 *
 */
class FileFsm extends JModel
{

	/**
	 * 
	 * An array of dictionary for graph of state machine 
	 * progresses going out of each state with their outcome state are indicated 
	 * @var array
	 */
	
	Public static $StateGraph=array(
	//-----------------------Review---------------------
	1=>array('Start'=>2),
	2=>array('Give_cotag_to_archive'=>3),
	3=>array('Get_archive_from_cotag'=>4),
	4=>array('Assign'=>5),
	5=>array('Review_nok'=>6, 'Review_ok'=>7),
	6=>array('Review_nok'=>6, 'Confirm_ok'=>9, 'Confirm_nok'=>10),
	7=>array('Review_nok'=>6, 'Give_archive_to_raked'=>13, 'Send_archive_to_out'=>12),
	10=>array('Review_ok'=>6, 'Review_nok'=>7),
	11=>array('Assign_by_manager'=>5, 'Give_archive_to_raked'=>13, 'Send_archive_to_out'=>12),
	12=>array('Receive_archive_from_out'=>11),
	13=>array('Get_raked_from_archive'=>14),
	14=>array('Send_raked_to_out'=>15, 'Give_raked_to_archive'=>16),
	15=>array('Receive_raked_from_out'=>14),
	16=>array('Get_arhive_from_raked'=>4),
	//--------------------------Correspondence-----------
	9=>array('ProcessRegister'=>18),
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
	'Senddemand_demand'=>'ارسال مطالبه نامه',
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
	'ProcessRegister'=>'ثبت کلاسه',
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
		'reviewing'=> 5,
		'archive'=>4,
		'Prophecy_first'=>41,
		'Prophecy_second'=>47,
		'Prophecy_setad'=>58,
		'Prophecy_commission'=>63,
	);
	
	/**
	 * 
	 * maps state name with state numbers
	 * @param string
	 */
	function Name2State($name){
		if (!array_key_exists($name,FileFsm::$Name2State))
			return null;
		 return FileFsm::$Name2State[$name];
	}
	
	/**
	 * 
	 * return possible progresses for each input state
	 * an array with keys as state english name and value as persian label name
	 * @param integer $state
	 */
	static function PossibleProgresses($state){
		if (!array_key_exists($state,FileFsm::$StateGraph))
			return null;
		$ar=FileFsm::$StateGraph[$state];
		foreach ($ar as $key=> $value)
			$ar[$key]=FileFsm::$ProcessList[$key];
		
		return $ar;
	} 
	static function PossibleProgresses2($state){
		if (!array_key_exists($state,FileFsm::$StateGraph))
			return null;
		return FileFsm::$ProcessList;
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
		$ar=FileFsm::$StateGraph[$currentstate];
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
		$currentstate=$currentstate*1;
		$ar=FileFsm::$StateGraph[$currentstate];
		if(array_key_exists($progressname,$ar)){
			return $ar[$progressname];
		}
	}
	static function IsReviewerDisturbState($state)
	{
		if($state==43 || $state==50 ||$state==52)
			return true;
		return false;
	}
	static function IsReviewerConfirmState($state)
	{
		if ($state==71)
			return true;
		return false;
	}
	function __construct()
	{
		
		 
	}
	static function Moderate1()
	{
		$files=ORM::Query(new ReviewFile)->GetOnlyProgressStartObject(0,999999999);
		//ORM::Dump($files);
		//j::SQL("UPDATE ReviewFile set State=2 ");
		//ORM::Flush();
		foreach($files as $f)
		{
			if($f)
				$f->SetState(2);
			$p=$f->LastProgress();
			if($p->MailNum()==0)
			{
		 		echo "3";
				$f->SetState(3);
			}
			ORM::Persist($f);
		}
		ORM::Flush();
	}
	static function Moderate2()
	{
		echo "<br/>";
		$files=ORM::Query(new ReviewFile)->GetFilesWithLastProgress("Registerarchive",0,99999999);
		//ORM::Query(new ReviewFile)->UpdateStateOfFilesWithLastProgress("Registerarchive",4);
		//ORM::Dump($files);
		foreach($files as $f)
		{
			$f->SetState(4);
			ORM::Persist($f);
		}
		ORM::Flush();
	}
	static function Moderate3()
	{
		$files=ORM::Query(new ReviewFile)->GetFilesWithLastProgress("Assign",0,99999999);
		foreach($files as $f)
		{
			$f->SetState(5);
			ORM::Persist($f);
		}
		ORM::Flush();
	}
	public static function Moderate4()
	{
		$files=ORM::Query(new ReviewFile)->GetFilesWithLastProgress("Review",0,99999999);
		foreach($files as $f)
		{
			if($f->LastProgress()->Result()==0)
			$f->SetState(9);
			else
			$f->SetState(11);
		}
	}
	public static function Moderate5()
	{
		$files=ORM::Query(new ReviewFile)->GetFilesWithLastProgress("Sendfile",0,99999999);
		//ORM::Dump($files);
		foreach($files as $f)
		{
			$f->SetState(12);
		}
		ORM::Flush();
		$files=ORM::Query(new ReviewFile)->GetFilesWithLastProgress("Receivefile",0,99999999);
		echo "4";
		//ORM::Dump($files);
		foreach($files as $f)
		{
			$f->SetState(11);
		}
	}
	public static function Moderate6()
	{
		
		$files=ORM::Query(new ReviewFile)->GetFilesWithLastProgress("Post",0,99999999);
		echo "5";
		//ORM::Dump($files);
		foreach($files as $f)
		{
			if($f->LastProgress()->IsSend()==1)
			$f->SetState(13);
			else
			$f->SetState(14);
		}
	}
}