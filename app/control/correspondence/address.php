<?php
class CorrespondenceAddressController extends CorrespondenceAbstractController

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
			$indicator = $_POST['Indicator'];
			$R = ORM::Query('ReviewProcessAddress')->AddToFile($this->File,$sub, $indicator, $this->Comment);
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
				"Name"=>"Indicator",
				"Label"=>"اندیکاتور",
		));
		$f->AddElement(array(
				"Type"=>"submit",
				"Name"=>"submit",
				"Value"=>"ثبت",
		));
		$this->Form=$f;
	}
	
	
	
}