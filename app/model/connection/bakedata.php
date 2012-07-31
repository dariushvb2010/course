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
		if(!isset($d->IsSuccess))
			return 'Request Timeout.';
		
		if($d->IsSuccess==false)
			return $d->messages;
		
		return true;
	}
	
	public function GetResult(){
		if($this->Validate())
			return $this->datapackage->result;
		
		return false;
	}
	
}