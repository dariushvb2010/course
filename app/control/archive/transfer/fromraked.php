<?php
class ArchiveTransferFromrakedController extends JControl
{
	function Start()
	{j::Enforce("Archive");
		
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
			$this->Handler=new HandleTransferSingle("Get","Raked","Archive", $Mail);
		}//----------------Search-----------------
		elseif(isset($_REQUEST['Search']))
		{
			$this->Handler=new HandleTransferSearch("Get","Raked","Archive");
		}
		//----------------PUBLIC-------------
		else 
		{
			$this->Handler=new HandleTransferPublic("Get","Raked","Archive");
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
			if($Mail->GiverGroup()->Title()=="Raked" AND $Mail->GetterGroup()->Title()=="Archive" AND $Mail->State()>=Mail::STATE_INWAY)
				return true;
			else
				return false;
		}
		else 
			return false;
	}
}