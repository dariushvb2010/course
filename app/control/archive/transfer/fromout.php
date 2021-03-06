<?php
class ArchiveTransferFromoutController extends JControl
{
	//public $Handler;
	/**
	 * 
	 * @see BaseControllerClass::Start()
	 */
	function Start()
	{
		j::Enforce("Archive");
		$Source=$_GET["Taraf"];
		
		//-----------------SINGLE------------
		if(isset($_POST['MailID']) OR isset($_GET['MailID']))
		{
			if(isset($_POST['MailID']))
				$MailID=$_POST['MailID']*1;
			else 
				$MailID=$_GET['MailID']*1;
			$Mail=ORM::Find("MailReceive", $MailID);
			if(!$this->SecurityCheck($Mail))
				return $this->Present();
			if(!$Topic)
			{
				$this->Error[]="محل دریافت یافت نشد.";
			}
			$this->Handler=new HandleTransferSingle("Receive",$Source, "Archive", $Mail);
		}
		elseif(isset($_REQUEST['Search']))
		{
			$this->Handler=new HandleTransferSearch("Receive",$Source, "Archive");
		}
		//----------------PUBLIC-------------
		else 
		{
			$this->Handler=new HandleTransferPublic("Receive",$Source,"Archive");
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
		if($Mail instanceof MailReceive)
		{
			if($Mail->ReceiverGroup()->Title()=="Archive")
				return true;
			else
				return false;
		}
		else 
			return false;
	}

}