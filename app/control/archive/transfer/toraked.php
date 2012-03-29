<?php
class ArchiveTransferTorakedController extends JControl
{
	//public $Handler;
	/**
	 * 
	 * @see BaseControllerClass::Start()
	 */
	function Start()
	{
		j::Enforce("Archive");
		
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
			$this->Handler=new HandleTransferSingle("Give","Archive","Raked", $Mail);
			$this->Handler->Perform();
		}
		elseif(isset($_POST['Search']))
		{
			$this->Handler=new HandleTransferSearch("Give","Archive","Raked");
			$this->Handler->Perform();
		}
		//----------------PUBLIC-------------
		else 
		{
			$this->Handler=new HandleTransferPublic("Give","Archive","Raked");
			$this->Handler->Perform();
		}
		
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
			if($Mail->GiverGroup()->Title()=="Archive" AND $Mail->GetterGroup()->Title()=="Raked" AND $Mail->State()<=Mail::STATE_INWAY)
				return true;
			else
				return false;
		}
		else 
			return false;
	}

}