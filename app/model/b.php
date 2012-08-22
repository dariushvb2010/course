<?php


class b
{
	//const gate_code = 50100;
	const CotagLength=7;
	const file_initial_state = 0;
	const file_initial_class = 0;
	const upload_folder_root="../../../upload/";
	//const Cotag_jsPattern="/^0{0,2}\d{7}$/";
	const Cotag_jsPattern="/^(\d{2,6}-)?[1-9]?\d{6}$/";

	static function upload_folder_relative_from_japp()
	{
		$jl=new CalendarPlugin();
		$jArr=$jl->TodayJalaliArray();
	
		return self::upload_folder_root.GateCode.'/'.$jArr[0].'/'.$jArr[1].'/';
	}

	//////////////////////////////////////////////////
	public static $CommentMinLength=10;
	public static $Error=array();
	public static $Warning=array();
	/**
	 *
	 * save the last ClassNum used
	 * @var integer
	 */
	protected static $ClassNum528;
	protected static $ClassNum248;
	protected static $ClassNum109;

	 
	/**
	 *
	 *
	 * @param integer $Num 528,248,109
	 */
	public static function GenerateClassNum($Num)
	{
		if(!$Num)
			return;
		 
		$Num=$Num."";
		$C="ClassNum{$Num}";
		$CM=ORM::Query("ConfigMain")->GetObject($C);
		if(!$CM)
		{
			ConfigMain::Add($C,1,false);
			return 1;
		}
		else
		{
			$CM->SetValue($CM->Value()*1+1);
			ORM::Persist($CM);
			return $CM->Value();
		}
	}
	/**
	 * @param string_or_int $Cotag
	 * @return bool
	 */
	public static function CotagValidation($Cotag)
	{
		$Cotag=strval($Cotag);
		//$pattern="/^(\d{2,6}-)?[1-9]?\d{".b::CotagLength-1."}$/";// "/\A\d{7}\z/" -------\A: start of string--------\z: end of string--------\d{7}: 7 digits
		$pattern="/^(\d{2,6}-)?[0]?[1-9]?\d{6}$/";// "/\A\d{7}\z/" -------\A: start of string--------\z: end of string--------\d{7}: 7 digits
		$res=preg_match($pattern,$Cotag);
		return $res==0 ? false : true;
	}
	
	public static function CotagFilter($Cotag)
	{
		$Cotag=trim($Cotag);
		if(count(explode('-',$Cotag))==1){
			$Cotag=$Cotag*1;
		}
		return $Cotag;
	}
	
	static public function __Initialize ()
	{
	}
	
	static function generatePassword($length=9, $strength=0) {
		$vowels = 'aeuy';
		$consonants = 'bdghjmnpqrstvz';
		if ($strength & 1) {
			$consonants .= 'BDGHJLMNPQRSTVWXZ';
		}
		if ($strength & 2) {
			$vowels .= "AEUY";
		}
		if ($strength & 4) {
			$consonants .= '23456789';
		}
		if ($strength & 8) {
			$consonants .= '@#$%';
		}
	
		$password = '';
		$alt = time() % 2;
		for ($i = 0; $i < $length; $i++) {
			if ($alt == 1) {
				$password .= $consonants[(rand() % strlen($consonants))];
				$alt = 0;
			} else {
				$password .= $vowels[(rand() % strlen($vowels))];
				$alt = 1;
			}
		}
		return $password;
	}
	
	/**
	 * 
	 * @param unknown_type $input
	 * @return ReviewFile
	 * @author morteza kavakebi
	 */
	public static function GetFile($input){
		if($input instanceof ReviewFile){
			 $ret=ReviewFile::GetRecentFile($input);
		}else{
			$inpAr=explode('-',$input);
			if (count($inpAr)==2){
				$ret=ReviewFile::GetRecentFile($inpAr[1]*1,$inpAr[0]*1);
			}else{
				$ret=ReviewFile::GetRecentFile($inpAr[0]*1);
			}
		}
		
		if(!$ret)
			return null;
		
		if($ret->State()>=4){
			//$ret->UpdateSerial();
		}
		return $ret;
	}
	
	/**
	 * dumps orm object with orm::dump
	 * and simple variables by dump
	 * @param unknown_type $inp
	 * @author morteza kavakebi
	 */
	public static function Dump($inp){
		ORM::Dump($inp);
	}

}
?>