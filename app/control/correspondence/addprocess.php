<?php
class CorrespondenceAddprocessController extends JControl

{
	function Start()
	{
		j::Enforce("Correspondence");

		if (count($_REQUEST['Cotag']))
		{
			$Cotag=B::CotagFilter($_REQUEST['Cotag']);

			$this->Cotag=$Cotag;
			$File=b::GetFile($Cotag);

			if($File)
			{
				$this->File=$File;
				$FileState=$File->State();
				//if(FsmGraph::StateMatch($FileState, 'Mokatebat'))
				//{
					$ProcessArrayraw=FsmGraph::PossibleProgresses($FileState);
					$ProcessArray=array();
					foreach ($ProcessArrayraw as $r){
						if($r->is_MokatebatViewable())$ProcessArray[]=$r;
					}
					
					$er=$this->ManageProcesses($File);
					if($er)
					{
						$Error[]=$er;
					}
					$this->Result=$this->ManageSuccesses();
				//}
				//else
				//{
				//	$Error[]="هیچ فرایند مکاتباتی برای این پرونده امکان پذیر نیست";
				//}
			}
			else
			{
				$Error[]="اظهارنامه‌ای با شماره کوتاژ یا کلاسه داده شده در سیستم ثبت نشده است.";
			}
		}
		$this->ProcessArray=$ProcessArray;
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		return $this->Present();
	}
	/**
	 * finds the name of the button clicked and returns it
	 * @return string like 'prophecy'
	 */
	function GetInputClass(){
		$inputclass='';
		foreach ($_POST as $key=>$value){
			if(substr($key, 0,3)=="###"){
				$inputclass=substr($key, 3,strlen($key)-3 );
			}
		}
		return $inputclass;
	}

	/**
	 * redirects to the convinient page else returns
	 * @param ReviewFile $File
	 */
	function ManageProcesses(ReviewFile $File){
		$input_class=$this->GetInputClass();
		if($input_class!=''){
			$getParams = "?Cotag={$File->Cotag()}&input_class={$input_class}";
			switch ($input_class){
				case 'ProcessRegister_109':
				case 'ProcessRegister_248':
				case 'ProcessRegister_528':
					$this->Redirect("./register?Cotag={$File->Cotag()}&input_class={$input_class}");
				break;
				case 'Address_first':
				case 'Address_second':
				case 'Address_setad':
				case 'Address_commission': // this case is not usefull in rajaie
					$this->Redirect('./address'.$getParams);			
				break;
				case 'Prophecy_first':
				case 'Prophecy_second':
				case 'Prophecy_setad':
				case 'Prophecy_commission':
					$this->Redirect('./prophecy'.$getParams);
				break;
				case 'Protest_first':
				case 'Protest_second':
				case 'Protest_setad':
				case 'Protest_commission':
				case 'Protest_after_p7':
					$this->Redirect('./protest'.$getParams);
				break;
				case 'ProcessAssign':
					$this->Redirect('./assign'.$getParams);
				break;
				case 'P7':
					$this->Redirect('./p7'.$getParams);
				break;
				case 'Forward_setad':
				case 'Forward_commission':
				case 'Forward_appeals';
					$this->Redirect("./forward".$getParams);
				break;
				case 'Feedback_setad_gomrok':
				case 'Feedback_commission_gomrok':
				case 'Feedback_appeals_gomrok';
				case 'Feedback_setad_owner':
				case 'Feedback_commission_owner':
				case 'Feedback_appeals_owner';
				$this->Redirect("./feedback".$getParams);
				break;
				case 'Payment':
					$this->Redirect('./payment'.$getParams);
				break;
				case 'Clearance':
					$this->Redirect('./clearance'.$getParams);
					break;
				default:
					
						$this->Redirect("./addprocess?Cotag={$File->Cotag()}");
			}
		}
		return (isset($Res['Error'])?$Res['Error']:null);
	}

	function ManageSuccesses(){
		if(isset($_GET['success'])){
			$success_process=(isset($_GET['success'])?$_GET['success']:'');
			$types=explode('_',$success_process);
			$classe=$_GET['classe'];
			$Reviewer=$_GET['Reviewer'];
		}
		if($success_process!=''){
			switch ($types[0]){
				case 'ProcessRegister':
					$res="شماره کلاسه {$classe} ثبت گردید.";
					break;
				case 'Address':
					$res='ارسال مطالبه نامه ثبت گردید.';
					break;
				case 'ProcessAssign':
					$res="ارجاع پرونده به کارشناس ".v::b($Reviewer)." ثبت گردید.";
					break;
				case 'ProcessClearance':
					$res="پرونده مختومه و از مکاتبات خارج گردید.";
					break;
				case 'Protest':
					$res='اعتراض صاحب کالا ثبت گردید.';
					break;
				case 'Prophecy':
					switch ($types[1]){
						case 'first':
							$res='ابلاغ مطالبه نامه ثبت گردید.';
							break;
					}
					break;
			}
		}else{
			$res=null;
		}
		return $res;
	}
}