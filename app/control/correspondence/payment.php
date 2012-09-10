<?php
class CorrespondencePaymentController extends CorrespondenceAbstractController

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
			$MailNum= $_POST['MailNum'];
			$PaymentValue = $_POST['PaymentValue'];
			$R = ORM::Query('ReviewProcessPayment')->AddToFile($this->File,$PaymentValue, $MailNum, $this->Comment);
			if(is_string($R))
				$Error[]= $R;
			else{
				$Result = ' مبلغ '.$R->PaymentValue(). ' پرداخت شد.';
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
				"Type"=>"text",
				"Name"=>"PaymentValue",
				"Label"=>'مبلغ پرداخت به ریال',
		));
		$f->AddElement(array(
				"Type"=>"submit",
				"Name"=>"submit",
				"Value"=>"ثبت",
		));
		$this->Form=$f;
	}
	
	
	
}