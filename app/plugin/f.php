<?php 
class FPlugin
{

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
	
}