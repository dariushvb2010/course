<?php
class CorrespondenceForwardController extends CorrespondenceAbstractController

{
	function Start()
	{

		j::Enforce("Correspondence");
		$this->Initialize();
		
		if(isset($_REQUEST['Cotag']) and !($this->File instanceof ReviewFile))
		{
			$Error[] = v::Ecnf($_REQUEST['Cotag']);
		}elseif(isset($_POST['submit'])){ // cotag is valid and file found
			$sub = substr($this->input_class,8,20);
			$MailNum = $_POST['MailNum'];
			$SetadID = $_POST['SetadID'];
			$R = ORM::Query('ReviewProcessForward')->AddToFile($this->File,$sub, $MailNum, $SetadID, $this->Comment);
			if(is_string($R))
				$Error[]= $R;
			else{
				$Result = 'ارسال ثبت شد.';
				$this->IfRedirect = true;
			}
		}
		
		
		
		$this->Result = $Result;
		$this->Error = $Error;
		if (count($this->Error)) $this->Result=false;
		$this->makeForm();
		return $this->Present();
	}
	
	
	function makeForm()
	{
		$f = $this->makeFormTemplate();
		$f->AddElement(array(
				"Type"=>"text",
				"Name"=>"MailNum",
				"Label"=>p::MailNum,
		));
		$f->AddElement(array(
				'Type'=>'select',
				'Name'=>'SetadID',
				'Label'=>p::Setad,
				'Options'=>ReviewTopic::TopicsArray(ReviewTopic::Type_mokatebat)
		));
		$f->AddElement(array(
				"Type"=>"submit",
				"Name"=>"submit",
				"Value"=>"ثبت",
		));
		$this->Form=$f;
	}
	
	
	
}