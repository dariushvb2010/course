<?php
class CorrespondenceProtestController extends CorrespondenceAbstractController

{
function Start()
	{

		j::Enforce("Correspondence");
		$this->Initialize();
		
		if(isset($_REQUEST['Cotag']) and !($this->File instanceof ReviewFile))
		{
			$Error[] = v::Ecnf($_REQUEST['Cotag']);
		}elseif(isset($_POST['submit'])){ // cotag is valid and file found
			$sub = substr($this->input_class,8,30);
			$indicator = $_POST['Indicator'];
			$R = ORM::Query('ReviewProcessProtest')->AddToFile($this->File,$sub, $indicator, $this->Comment);
			if(is_string($R))
				$Error[]= $R;
			else{
				$Result = 'اعتراض صاحب کالا ثبت شد.';
				$this->IfRedirect = true; //for redirecting to addprocess controller after success
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