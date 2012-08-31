<?php

/**
 * 
 * to use Curl and get json data from switch and other programs
 * @author kavakebi
 *
 */
class ConnectionBakedata extends JModel
{

	private $datapackage;
	public $timeout;
	public $hoststring;
	public $Error;
	
	
	function __construct()
	{
		$this->hoststring=reg("link/switch/url");
	}
	
	/**
	 * 
	 * @param ConnectionGetdata $getdata
	 */
	private function Handle_DataError($getdata){
		//GetData() must be in the first line
		$d=$getdata->GetData();
		//echo BR;
		//var_dump($d);
		//echo BR;
		$this->Error=array();
		
		if($getdata->error){
			$this->Error=array($getdata->error);
			$this->datapackage=null;
		}else{
			$this->Error=array();
			$this->datapackage=$d;
			
			if(!isset($d->isSuccess)){
				$this->Error[]= 'bad return.';
			}elseif($d->isSuccess==false){
				$this->Error =array_merge( $this->Error, $d->messages);
			}else{
				$ret=false;
			}
			
		}
		//var_dump($d->messages);
		return $ret;
	}
	
	/**
	 * returns the mojavez bargiri from exit door 
	 * and registers the bazbini request for it
	 * and is accessible after exit door makes the mojavez bargiri
	 * (not good yet for us)
	 * @param unknown_type $Cotag
	 * @param unknown_type $Year
	 */
	public function GetMojavezBargiriYear($Cotag,$Year){
		$RequestArray=array(
				'kootaj'=>$Cotag,
				"year"=>$Year,
		);
		$c=new ConnectionGetdata($this->hoststring."/GetMojavezBargiri", $RequestArray);
		$this->Handle_DataError($c);
	}

	/**
	 * returns the asyquda data for a kootaj and year
	 * it registers nothing and is accessible where exit door is installed
	 * @param unknown_type $Cotag
	 * @param unknown_type $Year
	 */
	public function GetParvanehFromAsycudaYear($Cotag,$Year){
		$RequestArray=array(
				'kootaj'=>$Cotag,
				"year"=>$Year,
		);
		$c=new ConnectionGetdata($this->hoststring."/GetParvanehFromAsycuda", $RequestArray);
		$this->Handle_DataError($c);
	}
	
	/**
	 * returns the asyquda data
	 * checks if the kootaj request with in three years if found then return else false
	 * @param unknown_type $Cotag
	 * @param unknown_type $Reapeat
	 */
	public function GetMojavezBargiri($Cotag,$Reapeat=3)
	{
			$calendar=new CalendarPlugin();
			$today=$calendar->TodayJalali();
			$todayArr=explode('-', $today);
			$year=$todayArr[0];
			
		$i=0;//@todo use a constant for retry number
		while($i++<$Reapeat)
		{
			$myYear=$year-$i+1;
			$this->GetMojavezBargiriYear($Cotag, $myYear."");
			if($this->Validate())
				return $myYear;
		}
		if($f==false)
		{
			return false;
		}
	}
	/**
	 * returns the asyquda data
	 * checks if the kootaj request with in three years if found then return else false
	 * @param unknown_type $Cotag
	 * @param unknown_type $Reapeat
	 */
	public function GetYear($Cotag,$Reapeat=5)
	{
			$calendar=new CalendarPlugin();
			$today=$calendar->TodayJalali();
			$todayArr=explode('-', $today);
			$year=$todayArr[0];
			
		$i=0;//@todo use a constant for retry number
		while($i++<$Reapeat)
		{
			$myYear=$year-$i+1;
			$this->GetParvanehFromAsycudaYear($Cotag, $myYear."");
			if($this->Validate())
				return $myYear;
		}
		if($f==false)
		{
			return false;
		}
	}
	
	/**
	 * returns parvane varedat from exit door 
	 * by its serial number 
	 * and is accessible when mojavez bargiri is generated
	 * @param unknown_type $Serial
	 */
	public function GetParvaneVaredati($Serial)
	{
		$RequestArray=array(
				'serial'=>$Serial,
		);
		$c=new ConnectionGetdata($this->hoststring."/GetParvanehVaredati", $RequestArray);
		$this->Handle_DataError($c);
	}

	/**
	 * /GetBijaks_Entrance    {kootaj} : List<VorudeEttelaatDarbeKhorujVaredat>
	 * @param unknown_type $Serial
	 */
	public function GetBijaks_Entrance($Serial)
	{
		$RequestArray=array(
				'kootaj'=>$Serial,
		);
		$c=new ConnectionGetdata($this->hoststring."/GetBijaks_Entrance", $RequestArray);
		$this->Handle_DataError($c);
	}
	
	public function Validate(){
		return ($this->Error==null);
	}
	
	public function GetResult(){
		if($this->Validate())
			return $this->datapackage->result;
		
		return false;
	}
	
}