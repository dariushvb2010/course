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
			$TopicID=$_POST['TopicID'];//for Send and receive
			$Topic=ORM::Find("ReviewTopic", $TopicID);
			if(!$Topic)
			{
				$this->Error[]="محل دریافت یافت نشد.";
			}
			$this->Handler=new HandleTransferSingle("Receive",$Topic, "Archive", $Mail);
			$this->Handler->Perform();
		}
		//----------------PUBLIC-------------
		else 
		{
			$Source=$_GET["Taraf"];
			echo $Source;
			$this->Handler=new HandleTransferPublic("Receive",$Source,"Archive");
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