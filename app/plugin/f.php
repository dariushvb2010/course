<?php 
class FPlugin
{
// 	public static function RandomString($Length=null)
// 	{
// 		$str="";
// 		if($Length==null)
// 			$Length=rand(2,10);
// 		for ($i=0;$i<$Length;++$i)
// 		{
// 			$str.=chr(ord('a')+rand(0,25));
// 		}
// 		return $str;
// 	}
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
	
	
}