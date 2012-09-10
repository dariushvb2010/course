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
	public $InputFormat;
	
	function __construct($url,$RequestArray,$InputFormat='hex')
	{
		$this->url=$url;		 
		$this->RequestArray=$RequestArray;
		$this->timeout=2;
		$this->error=null;
		$this->InputFormat=$InputFormat;		 
	}
	
	public function GetData(){
		$url=$this->make_url();
		//echo '<p>url: ';
		//echo '</p>';
		$hexdata= $this->get_data($url);
		$json=FPlugin::hex2str($hexdata);
		$ret=json_decode($json);
		return $ret;
	}
	
	private function make_url(){
		$req=array();
		foreach ($this->RequestArray as $par=>$val)
		{
			if($this->InputFormat=='hex'){
				$FormattedValue=FPlugin::strToHex($val);
			}else{
				$FormattedValue=$val;
			}
			$req[]=$par."=".$FormattedValue;
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