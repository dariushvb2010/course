<?php
class CorrespondenceFeedbackController extends CorrespondenceAbstractController

{
	function Start()
	{

		j::Enforce("Correspondence");
		$this->Initialize();
		$forward = $this->File->LLP('Forward',true);
		if($forward and $forward->Setad())
			$ForwardTitle = $forward->Setad()->Topic();
		if(isset($_REQUEST['Cotag']) and !($this->File instanceof ReviewFile))
		{
			$Error[] = v::Ecnf($_REQUEST['Cotag']);
		}elseif(isset($_POST['submit'])){ // cotag is valid and file found
			$arr = explode('_', $this->input_class); // ex: Forward_setad_gomrok, Forward_setad_owner
			$subManner = $arr[1];
			$FeedbackTo = $arr[2];
			$MailNum = $_POST['MailNum'];
			$R = ORM::Query('ReviewProcessFeedback')->AddToFile($this->File,$subManner,$FeedbackTo, $MailNum, $this->Comment);
			if(is_string($R))
				$Error[]= $R;
			else{
				$Result = 'دریافت ثبت شد.';
				$this->IfRedirect = true;
			}
		}
		
		$this->ForwardTitle = $ForwardTitle;
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
				'Type'=>'custom',
				'HTML'=>'آخرین ارسال به '.$this->ForwardTitle. ' بوده است.'
				));
		$f->AddElement(array(
				"Type"=>"text",
				"Name"=>"MailNum",
				"Label"=>p::MailNum,
		));
// 		$f->AddElement(array(
// 				'Type'=>'select',
// 				'Name'=>'Result',
// 				'Label'=>'نتیجه',
// 				'Options'=>array(
// 						'gomrok'=>'به نفع گمرک',
// 						'owner'=>'به نفع صاحب کالا')
// 		));
		$f->AddElement(array(
				"Type"=>"submit",
				"Name"=>"submit",
				"Value"=>"ثبت",
		));
		$this->Form=$f;
	}
	
	
	
}