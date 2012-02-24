<?php
class ArchiveReceiveController extends JControl

{
	function Start()
	{
//		j::Enforce("Archive");
		if(isset($_GET['Cotag']))
		{
			$Cotag=$_GET['Cotag']*1;
			$Res=ORM::Query(new ReviewProgressReceivefile)->LastSend($Cotag);
			if(is_string($Res))
			{
				$Error[]=$Res;
			}
			else
			{
				$this->Send=$Res; 
				$this->Cotag=$Cotag;
			}
				
		}
		if (isset($_POST['Mailnum']))
		{
			$Mailnum=$_POST['Mailnum'];
			$Requester=$_POST['Requester'];
			$Comment=$_POST['Comment'];
			$Res=ORM::Query(new ReviewProgressReceivefile)->AddToFile($Cotag,$Requester,$Mailnum,$Comment,"Archive");
			if(is_string($Res))
			{
				$Error[]=$Res;
			}
			else 
				$this->Result="مکاتبه با موفقیت ثبت شد.";
		}
		
		$this->makeForm();
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		return $this->Present();
	}
	function makeForm()
	{
		
		$f=new AutoformPlugin("post");
		$f->AddElement(array(
			"Type"=>"text",
			"Name"=>"Cotag",
			"Disabled"=>true,
			"Label"=>"کوتاژ",
			"Value"=>$this->Cotag,
		));
		$f->AddElement(array(
			"Type"=>"text",
			"Name"=>"Requester",
			"Options"=>$ts,
			"Label"=>"از",
			"Width"=>"150px",
			"Disabled"=>true,
			"Value"=>($this->Send)?$this->Send->Requester():""
		));
		$f->AddElement(array(
			"Type"=>"text",
			"Name"=>"Mailnum",
			"Label"=>"شماره نامه",
			"Value"=>" "
		));
		
		$f->AddElement(array(
			"Type"=>"textarea",
			"Name"=>"Comment",
			"Label"=>"توضیحات"
		));
		$f->AddElement(array(
			"Type"=>"submit",
			"Value"=>"دریافت"
		));
		$this->Form=$f;
	}
}