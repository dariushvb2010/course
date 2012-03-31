<?php
class ArchiveTransferFromcotagController extends JControl
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
			$this->Handler=new HandleTransferSingle("Get","CotagBook","Archive", $Mail);
		}//----------------Search-----------------
		elseif(isset($_POST['Search']))
		{
			$this->Handler=new HandleTransferSearch("Get","CotagBook","Archive");
		}
		//----------------PUBLIC-------------
		else 
		{
			$this->Handler=new HandleTransferPublic("Get","CotagBook","Archive");
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
			if($Mail->GiverGroup()->Title()=="CotagBook" AND $Mail->GetterGroup()->Title()=="Archive" AND $Mail->State()>=Mail::STATE_INWAY)
				return true;
			else
				return false;
		}
		else 
			return false;
	}

}