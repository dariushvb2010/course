<?php
class CorrespondenceTransferTooutController extends JControl
{
	//public $Handler;
	/**
	 * 
	 * @see BaseControllerClass::Start()
	 */
	function Start()
	{
		j::Enforce("Correspondence");
		$Dest=$_GET["Taraf"];
		//-----------------SINGLE------------
		if(isset($_REQUEST['MailID']) )
		{
			if(isset($_POST['MailID']))
				$MailID=$_POST['MailID']*1;
			else 
				$MailID=$_GET['MailID']*1;
			$Mail=ORM::Find("MailSend", $MailID);
			if(!$this->SecurityCheck($Mail))
				return $this->Present();
			$this->Handler=new HandleTransferSingle('Send',"Correspondence",$Dest, $Mail);
		}//----------------Search-----------------
		elseif(isset($_REQUEST['Search']))
		{
			$this->Handler=new HandleTransferSearch('Send',"Correspondence",$Dest);
		}
		//----------------PUBLIC-------------
		else 
		{
			$this->Handler=new HandleTransferPublic('Send','Correspondence',$Dest);
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
			if($Mail->SenderGroup()->Title()=="Correspondence")
				return true;
			else
				return false;
		}
		else 
			return false;
	}
}