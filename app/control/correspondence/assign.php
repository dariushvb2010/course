<?php
class CorrespondenceAssignController extends CorrespondenceAbstractController
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
			$RID = $_POST['ProcessReviewerID'];
			$R = ORM::Query('ReviewProcessAssign')->AddToFile($this->File, $RID, $this->Comment);
			if(is_string($R))
				$Error[]= $R;
			else{
				$Result = 'تخصیص به کارشناس انجام شد .';
				$this->IfRedirect = true; //for redirecting to addprocess controller after success
			}
		}
		$rawRs= ORM::Query('MyUser')->Reviewers(MyUser::State_work);
		foreach($rawRs as $rawR){
			$rs[$rawR->ID()] = $rawR->getFullName();
		}
		$this->ProcessReviewers = $rs;
		$this->Result = $Result;
		$this->Error = $Error;
		if (count($this->Error)) $this->Result=false;
		$this->makeForm();
		return $this->Present();
	}
	function makeForm(){
		$f = $this->makeFormTemplate(false);
		$f->AddElement(array(
				"Name"=>"ProcessReviewerID",
				"Type"=>"select",
				"Options"=>$this->ProcessReviewers,
				"Label"=>"کارشناس بازبینی",
		));
		$f->AddElement(array(
				"Type"=>"submit",
				"Name"=>"submit",
				"Value"=>"ثبت",
		));
		$this->Form=$f;
	}
	
}