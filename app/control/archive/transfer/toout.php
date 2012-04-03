<?php
class ArchiveTransferTooutController extends JControl
{
	//public $Handler;
	/**
	 * 
	 * @see BaseControllerClass::Start()
	 */
	function Start()
	{
		j::Enforce("Archive");
		$Dest=$_GET["Taraf"];
		
		//-----------------SINGLE------------
		if(isset($_POST['MailID']) OR isset($_GET['MailID']))
		{
			if(isset($_POST['MailID']))
				$MailID=$_POST['MailID']*1;
			else 
				$MailID=$_GET['MailID']*1;
			$Mail=ORM::Find("MailSend", $MailID);
			if(!$this->SecurityCheck($Mail))
				return $this->Present();
			$this->Handler=new HandleTransferSingle("Send","Archive",$Dest, $Mail);
		}//----------------Search-----------------
		elseif(isset($_POST['Search']))
		{
			$this->Handler=new HandleTransferSearch("Send","Archive",$Dest);
		}
		//----------------PUBLIC-------------
		else 
		{
			$this->Handler=new HandleTransferPublic("Send","Archive",$Dest);
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
		if($Mail instanceof MailSend)
		{
			if($Mail->SenderGroup()->Title()=="Archive")
				return true;
			else
				return false;
		}
		else 
			return false;
	}

}