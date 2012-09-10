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
			$ProphecyDate = $_POST['ProphecyDate'];
			if(!b::DateValidation($ProphecyDate))
				$Error[] = v::Ednv($ProphecyDate);
			else{
				$sub = substr($this->input_class,9,30);
				$MailNum = $_POST['MailNum'];
				$jc = new CalendarPlugin();
// 				$dd = explode('/', $ProphecyDate);
// 				$ProphecyTimestamp = $jc->Jalali2Timestamp($dd[0], $dd[1], $dd[2]);
				$ProphecyTimestamp = $jc->JalaliStr2Timestamp($ProphecyDate);
				$R = ORM::Query('ReviewProcessProphecy')->AddToFile($this->File,$sub, $MailNum, $ProphecyTimestamp, $this->Comment);
				if(is_string($R))
					$Error[]= $R;
				else{
					$Result = 'ابلاغ به صاحب کالا ثبت شد.';
					$this->IfRedirect = true;
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
		$f = $this->makeFormTemplate();
		$f->AddElement(array(
				'Name'=>'MailNum',
				'Label'=>p::MailNum
				));
		$f->AddElement(array(
				'Name'=>'ProphecyDate',
				'Label'=>p::ProphecyDate,
				'Class'=>'date'
				));
		$f->AddElement(array(
				"Type"=>"submit",
				"Name"=>"submit",
				"Value"=>"ثبت",
		));
		$this->Form=$f;
	}
	
}