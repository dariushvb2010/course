<?php
class CorrespondenceProphecyController extends CorrespondenceAbstractController

{
	function Start()
	{

		j::Enforce("Correspondence");
		$this->Initialize();
		
		if(isset($_REQUEST['Cotag']) and !($this->File instanceof ReviewFile))
		{
			$Error[] = v::Ecnf($_REQUEST['Cotag']);
		}elseif(isset($_POST['submit'])){ // cotag is valid and file found
			$sub = substr($this->input_class,9,30);
			$indicator = $_POST['Indicator'];
			$R = ORM::Query('ReviewProcessProphecy')->AddToFile($this->File,$sub, $indicator, $this->Comment);
			if(is_string($R))
				$Error[]= $R;
			else{
				$Result = 'ابلاغ به صاحب کالا ثبت شد.';
				$this->IfRedirect = true;
			}
		}
		
		$this->Result = $Result;
		$this->Error = $Error;
		if (count($this->Error)) $this->Result=false;
		$this->makeForm();
		return $this->Present();
	}
	function makeForm(){
		$f = $this->makeFormTemplate(true);
		$f->AddElement(array(
				"Type"=>"submit",
				"Name"=>"submit",
				"Value"=>"ثبت",
		));
		$this->Form=$f;
	}
	
}