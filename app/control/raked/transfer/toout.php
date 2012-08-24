<?php
class RakedTransferTooutController extends JControl
{
	//public $Handler;
	/**
	 * 
	 * @see BaseControllerClass::Start()
	 */
	function Start()
	{
		j::Enforce("Raked");
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
			$this->Handler=new HandleTransferSingle("Send","Raked",$Dest, $Mail);
		}//---------------Search-----------------
		elseif(isset($_REQUEST['Search']))
		{
			$this->Handler=new HandleTransferSearch("Send","Raked",$Dest);
		}
		//----------------PUBLIC-------------
		else 
		{
			$this->Handler=new HandleTransferPublic("Send","Raked",$Dest);
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
			if($Mail->SenderGroup()->Title()=="Raked")
				return true;
			else
				return false;
		}
		else 
			return false;
	}

}