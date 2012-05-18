<?php


class b
{

	public static $CotagLength=7;
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
	public static function CotagValidation($Cotag)
	{
		$Cotag=strval($Cotag);
		$pattern="/\A[1-9]{1}\d{".(CotagLength-1)."}\z/";// "/\A\d{7}\z/" -------\A: start of string--------\z: end of string--------\d{7}: 7 digits
		$res=preg_match($pattern,$Cotag);
		return $res==0 ? false : true;
	}
	
	static public function __Initialize ()
	{
		self::$CotagLength=7;
	}

}
?>