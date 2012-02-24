<?php 
class FPlugin
{
	public static function RandomString($Length=null)
	{
		$str="";
		if($Length==null)
			$Length=rand(2,10);
		for ($i=0;$i<$Length;++$i)
		{
			$str.=chr(ord('a')+rand(0,25));
		}
		return $str;
	}
	
	
}