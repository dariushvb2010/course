<?php 
class FPlugin
{
        public static function hex2str($hex) {
		for($i=0;$i<strlen($hex);$i+=2)
			$str .= chr(hexdec(substr($hex,$i,2)));
	
		return $str;
	}
	public static function str2hex($string)
	{
		$hex='';
		for ($i=0; $i < strlen($string); $i++)
		{
		$hex .= dechex(ord($string[$i]));
		}
		return $hex;
	}
	public static function RandomString($len=null, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789')
	{
		$string = '';
		if($len==null)
			$len=rand(2, 10);
		for ($i = 0; $i < $len; $i++)
		{
			$pos = rand(0, strlen($chars)-1);
			$string .= $chars{$pos};
		}
		return $string;
	}
	public static function getAddress()
	{
		/*** check for https ***/
		$protocol = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
		/*** return the full address ***/
		return $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}
	public static $PersianMonthNames=array(
					1=>"فروردین",
					2=>"اردیبهشت",
					3=>"خرداد",
					4=>"تیر",
					5=>"مرداد",
					6=>"شهریور",
					7=>"مهر",
					8=>"آبان",
					9=>"آذر",
					10=>"دی",
					11=>"بهمن",
					12=>"اسفند",
				);
	/**
	 * --------------time line ------->-->---->----->------>----->------>------->------>------>---->----->----->----->----->-----
	 * ********|***M(monthCount-1)***|  ...  |***M(2)***|***M(1)***|***M(0)***|startMonth=4|***M***|***M***|***M***|***now***|****
	 * --------------------------------------------------------------------------------------------------------------------------
	 *
	 * @param integer $startMonth
	 * @param integer $monthCount
	 */
	public static function PersianMonthesInInterval($startMonth, $monthCount)
	{
		$c = new CalendarPlugin();
		$t = $c->TodayJalaliArray();
		$thisMonthNum = $t[1];
		$thisYearNum = $t[0];
		for($i=$startMonth; $i<$startMonth + $monthCount; $i++)
		{
			$div = $thisMonthNum-1 - $i ; 
			$j = $div %12 + 1;
			$year = $thisYearNum + intval($div/12);
			if($j<=0 )
			{
				$year--;
				$j+=12;
			}
			$res[$i]['monthName'] = self::$PersianMonthNames[$j];
			$res[$i]["year"]=$year;
		}
		krsort($res);// sort array by key high to low
		return $res;
		
	}
	
}