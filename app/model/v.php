<?php
class v
{
	/**
	 * Persian Cotag
	 * @var string
	 */
	const PCOT = "کوتاژ";
	/**
	 * Persian File
	 * @var string
	 */
	const PFL = "اظهارنامه";
	
	/**
	 * bold tag
	 */
	public static function b($str)
	{
		return "<b>".$str."</b>";
	}
	
	/**
	 * Green Cotag
	 * @param unknown_type $cot
	 */
	public static function gc($cot)
	{
		return "<span class='v-cotag v-green'>".$cot."</span>";
	}
	/**
	 * Big Green Cotag
	 * @param unknown_type $cot
	 */
	public static function bgc($cot)
	{
		return "<span class='v-cotag v-green v-big'>".$cot."</span>";
	}
	/**
	 * Red Cotag
	 * @param unknown_type $cot
	 */
	public static function rc($cot)
	{
		return "<span class='v-cotag v-red'>".$cot."</span>";
	}
	/**
	 * Big Red Cotag
	 * @param unknown_type $cot
	 */
	public static function brc($cot)
	{
		return "<span class='v-cotag v-red v-big'>".$cot."</span>";
	}
	/**
	 * Blue Cotag
	 * @param unknown_type $cot
	 */
	public static function bc($cot)
	{
		return "<span class='v-cotag v-blue'>".$cot."</span>";
	}
	/**
	 * Big Blue Cotag
	 * @param unknown_type $cot
	 */
	public static function bbc($cot)
	{
		return "<span class='v-cotag v-blue v-big'>".$cot."</span>";
	}

	/**
	 * Custom Cotag Show
	 * @param unknown_type $cot
	 */
	public static function cuc($cot,$attr='')
	{
		$classes=array('v-cotag', 'v-blue', 'v-big');
		$ClassStr=implode(' ',$Classes);
		//if ($attrib)
		return "<span class='$ClassStr'>$cot</span>";
	}
	
	////////////////////////////////////////////////////////
	/**
	 * ERROR: cotag not found
	 *  کوتاژ یافت نشد!
	 * @param integer|string|ReviewFile $Cot
	 */
	static function Ecnf($Cot=null)
	{
		if($Cot==null){ 
			return "اظهارنامه با این کوتاژ یافت نشد!";
		}else{
			if($Cot instanceof ReviewFile)
				$myCot=$Cot->Cotag();
			else
				$myCot=strval($Cot);
			
			return "اظهارنامه ".self::rc($myCot)." یافت نشد!";
		}
	}
	/**
	 * ERROR:contag not valid
	 *  کوتاژ ناصحیح است!
	 * @param integer_string $Cot
	 */
	static function Ecnv($Cot=null)
	{
		return ( $Cot==null ?
				"کوتاژ باید ".b::CotagLength." رقمی باشد!"
				:
				"کوتاژ ".self::rc($Cot)." ناصحیح است."
		);
	}
	
	/**
	 * 'a' tag
	 * link with href and label
	 * @param integer_string $Cot
	 */
	static function link($label='',$href='')
	{
		$t='<a href="'.$href.'">'.$label.'</a>';
		return $t;
	}
	
}