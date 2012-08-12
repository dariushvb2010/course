<?php

/**
 * 
 * to use Curl and get json data from switch and other programs
 * @author kavakebi
 *
 */
class ConnectionGetdata extends JModel
{

	private $url;
	private $RequestArray;
	public $timeout;
	public $error;
	
	function __construct($url,$RequestArray)
	{
		$this->url=$url;		 
		$this->RequestArray=$RequestArray;
		$this->timeout=2;
		$this->error=null;		 
	}
	
	public function GetData(){
		$url=$this->make_url();
		echo '<p>url: ';
		var_dump($url);
		echo '</p>';
		$hexdata= $this->get_data($url);
		$json=$this->hex2str($hexdata);
		$ret=json_decode($json);
		return $ret;
	}
	
	private function hex2str($hex) {
		for($i=0;$i<strlen($hex);$i+=2)
			$str .= chr(hexdec(substr($hex,$i,2)));
	
		return $str;
	}
	private function strToHex($string)
	{
		$hex='';
		for ($i=0; $i < strlen($string); $i++)
		{
		$hex .= dechex(ord($string[$i]));
		}
		return $hex;
	}
	
	private function make_url(){
		$req=array();
		foreach ($this->RequestArray as $par=>$val)
		{
			$req[]=$par."=".$this->strToHex($val);
		}
		
		$req=implode('&',$req);
		return $this->url.(count($this->RequestArray)?"?".$req:'');
	}
	
	/* gets the data from a URL */
	private function get_data($url)
	{
		//echo $url;
		$ret=null;
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$this->timeout);
		
		$data = curl_exec($ch);
		if($data === false)
		{
			$this->error= curl_error($ch);
			$ret=false;
		}
		else
		{
			$ret=$data;
		}
		curl_close($ch);
		return $ret;		
	}
}