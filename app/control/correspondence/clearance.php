<?php
class CorrespondenceClearanceController extends CorrespondenceAbstractController

{
	function Start()
	{

		j::Enforce("Correspondence");
		$this->Initialize();
		
		if(isset($_REQUEST['Cotag']) and !($this->File instanceof ReviewFile))
		{
			$Error[] = v::Ecnf($_REQUEST['Cotag']);
		}elseif(isset($_POST['submit'])){ // cotag is valid and file found
			$amount = $_POST['Amount'];
			$R=ORM::Query("ReviewProcessClearance")->AddToFile($this->File);
			if(is_string($R))
				$Error[]= $R;
			else{
				$Result = 'پرونده مختومه شد.';
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
				"Name"=>"Amount",
				"Label"=>"مبلغ دریافتی",
		));
		$f->AddElement(array(
				"Type"=>"submit",
				"Name"=>"submit",
				"Value"=>"ثبت",
		));
		$this->Form=$f;
	}
	
	
	
}