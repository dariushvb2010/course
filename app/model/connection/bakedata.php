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
		$this->hoststring=reg("link/MohamadTavbal");
	}
	
	public function GetMojavezBargiriYear($Cotag,$Year){
		$RequestArray=array(
				'kutaj'=>$Cotag,
				"year"=>$Year,
		);
		$c=new ConnectionGetdata($this->hoststring."/GetMojavezBargiri", $RequestArray);
		$this->datapackage=$c->GetData();
	}

	public function GetParvaneFromAsycudaYear($Cotag,$Year){
		$RequestArray=array(
				'kutaj'=>$Cotag,
				"year"=>$Year,
		);
		$c=new ConnectionGetdata($this->hoststring."/GetParvaneFromAsycuda", $RequestArray);
		$this->datapackage=$c->GetData();
	}
	
	public function GetMojavezBargiri($Cotag,$Reapeat=3)
	{
			$calendar=new CalendarPlugin();
			$today=$calendar->TodayJalali();
			$todayArr=explode('-', $today);
			$year=$todayArr[0];
			
		$i=0;//@todo use a constant for retry number
		while($i++<$Reapeat)
		{
			$this->GetMojavezBargiriYear($Cotag, $year+$i-1);
			if($this->Validate())
				break;
		}
		if($f==false)
		{
			return false;
		}
	}
	
	public function GetParvaneyeVaredat($Mojavez)
	{
		$RequestArray=array(
				'mojavez'=>$Mojavez,
		);
		$c=new ConnectionGetdata($this->hoststring."/GetParvaneyeVaredat", $RequestArray);
		$this->datapackage=$c->GetData();
	}
	
	public function Validate(){
		$d=$this->datapackage;
		if(!isset($d->isSuccess)){
			$this->Error= 'Request Timeout.';
		}elseif($d->isSuccess==false){
			$this->Error= $d->messages;
		}else{
			return true;
		}
		return false;
	}
	
	public function GetResult(){
		if($this->Validate())
			return $this->datapackage->result;
		
		return false;
	}
	
}