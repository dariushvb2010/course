<?php
class ArchiveTransferFromcotagController extends JControl
{
	//public $Handler;
	/**
	 * @see BaseControllerClass::Start()
	 */
	function Start()
	{
		j::Enforce("Archive");
		
		//----------------Assign-----------------
		if(isset($_POST['Assign']))
		{
			if(isset($_POST['MailID']))
				$MailID=$_POST['MailID']*1;
			else
				$MailID=$_GET['MailID']*1;
			$Mail=ORM::Find("MailGive", $MailID);
			if(!$this->SecurityCheck($Mail))
				return $this->Present();
			$this->Handler=new HandleTransferSingle("Get","CotagBook","Archive", $Mail);
			$Files=$_POST['Cotag'];
			$Files=ReviewFile::RegulateWithError($Files);
			$Reviewer=ORM::Query("MyUser")->getRandomReviewer();
			if(!($Reviewer instanceof MyUser))
			{
				$Error[]="هیچ کارشناس بازبینی یافت نشد.";
			}
			else
			{
				$this->Result.="اظهارنامه های زیر به کارشناس بازبینی";
				$this->Result.=v::b($Reviewer->getFullName())." تخصیص داده شدند.".BR;
				foreach ($Files as $File)
				{
					if(!($File instanceof ReviewFile))
					{
						$Error[]=strval($File);
						continue;
					}
					$P=ORM::Query("ReviewProgressAssign")->AddToFile($File, $Reviewer);
					if(is_string($P))
					{
						$Error[]=$P;
					}
					else
					{
						$AtLeastOne=true;
						$this->Result.=$File->Cotag()." ";
					}
				}
				if(!$AtLeastOne)
				{
					$this->Result=false;
					$this->Error[]="هیچ اظهارنامه ای تخصیص داده نشد.";
				}
			}
		}
		//-----------------SINGLE------------
		elseif(isset($_POST['MailID']) OR isset($_GET['MailID']))
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
		elseif(isset($_REQUEST['Search']))
		{
			$this->Handler=new HandleTransferSearch("Get","CotagBook","Archive");
		}
		//----------------PUBLIC-------------
		else 
		{
			$this->Handler=new HandleTransferPublic("Get","CotagBook","Archive");
		}
		$this->Handler->Perform();
		if($this->Handler->MainForm)
		{
			if($this->Handler->Mail->State()==Mail::STATE_GETTING or $this->Handler->Mail->State()==Mail::STATE_INWAY)
			$this->Handler->MainForm->List->Autoform->AddElement(array("Type"=>"submit", "Name"=>"Assign", "Value"=>"تخصیص به کارشناس"));
		}
		$this->Error=$Error;
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