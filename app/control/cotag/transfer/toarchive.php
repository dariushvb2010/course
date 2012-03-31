<?php
class CotagTransferToarchiveController extends JControl
{
	//public $Handler;
	/**
	 * 
	 * @see BaseControllerClass::Start()
	 */
	function Start()
	{
		j::Enforce("CotagBook");
		$c=new CalendarPlugin();
		$this->today=explode("/", $c->JalaliFromTimestamp(time()));
		$this->tomorrow=explode("/", $c->JalaliFromTimestamp(strtotime("tomorrow")));
		$this->PublicShow=false;
		
		//-----------------SINGLE------------
		if(isset($_POST['MailID']) OR isset($_GET['MailID']))
		{
			if(isset($_POST['MailID']))
				$MailID=$_POST['MailID']*1;
			else 
				$MailID=$_GET['MailID']*1;
			$Mail=ORM::Find("MailGive", $MailID);
			if(!$this->SecurityCheck($Mail))
				return $this->Present();
			$this->Handler=new HandleTransferCotagbookSingle("Give","CotagBook","Archive", $Mail);
		}
		elseif(isset($_POST['Search']))
		{
			$this->Handler=new HandleTransferSearch("Give","CotagBook","Archive");
		}
		//----------------PUBLIC-------------
		else 
		{
			$this->Handler=new HandleTransferCotagbookPublic("Give","CotagBook","Archive");
		}
		
		$this->Handler->Perform();
		$this->Error=$Error;
		if (count($Error))
		$this->Result=false;
		return $this->Present();
	}
	private function SecurityCheck($Mail)
	{
		if(!($Mail instanceof Mail))
			return true;
		if($Mail instanceof MailGive)
		{
			if($Mail->GiverGroup()->Title()=="CotagBook" AND $Mail->GetterGroup()->Title()=="Archive" AND ($Mail->State()<=Mail::STATE_INWAY or $Mail->State()==Mail::STATE_CLOSED))
				return true;
			else
				return false;
		}
		else 
			return false;
	}

}