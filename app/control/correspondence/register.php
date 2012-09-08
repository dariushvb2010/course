<?php
class CorrespondenceRegisterController extends CorrespondenceAbstractController{
	function Start(){
		
		j::Enforce("Correspondence");
		$init = $this->Initialize();
		if(isset($_REQUEST['Cotag']) and !($this->File instanceof ReviewFile))
		{
			$Error[] = v::Ecnf($_REQUEST['Cotag']);
		}elseif(isset($_POST['Register'])){ // cotag is valid and file found
			$input_class=$_GET['input_class'];// ex: ProcessRegister_109, ProcessReister_248, ProcessRegister_528
			$cat = substr($input_class,16,3);
			$classe = $_POST['Classe'];
			if(reg('mokatebat/register/mode')=='manual' and empty($classe))
				$Error[] = 'شماره کلاسه وارد نشده است.';
			else{
				$Res=ORM::Query('ReviewProcessRegister')->AddToFile($this->File,$cat, $classe);
				if(is_string($Res))
					$Error[] = $Res;
				else{
					$Result = 'پرونده ثبت کلاسه شد.';
					$this->IfRedirect = true;
				}
			}
		}
		
		$this->Error = $Error;
		$this->Result = $Result;
		$this->Form = $this->makeForm();
		if(count($this->Error))
			$this->Result=false;
		return $this->Present();
	}
	function makeForm(){
		$f = $this->makeFormTemplate();
		if(reg('mokatebat/register/mode')=='manual')
		$f->AddElement(array(
				'Name'=>'Classe',
				'Label'=>'شماره کلاسه'
				));
		$f->AddElement(array(
				'Name'=>'Register',
				'Type'=>'submit',
				'Value'=>'ثبت',
		));
		return $f;
	}
}





