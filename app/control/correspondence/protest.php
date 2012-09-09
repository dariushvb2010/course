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
			$ProtestDate = $_POST['ProtestDate'];
			if(!b::DateValidation($ProtestDate))
				$Error[] = v::Ednv($ProtestDate);
			else{
				$jc = new CalendarPlugin();
				$ProtestTimestamp = $jc->JalaliStr2Timestamp($ProtestDate);
				var_dump($ProtestTimestamp);
				$sub = substr($this->input_class,8,30);
				$indicator = $_POST['Indicator'];
				$R = ORM::Query('ReviewProcessProtest')->AddToFile($this->File,$sub, $indicator, $ProtestTimestamp, $this->Comment);
				if(is_string($R))
					$Error[]= $R;
				else{
					$Result = 'اعتراض صاحب کالا ثبت شد.';
					$this->IfRedirect = true; //for redirecting to addprocess controller after success
				}
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
				'Name'=>'ProtestDate',
				'Label'=>'تاریخ ثبت اعتراض در دبیرخانه'
				));
		$f->AddElement(array(
				"Type"=>"submit",
				"Name"=>"submit",
				"Value"=>"ثبت",
		));
		$this->Form=$f;
	}
	
}