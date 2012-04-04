<?php
class CorrespondenceMainController extends JControl

{
	function Start()
	{
		j::Enforce("Correspondence");

		if (count($_REQUEST['Cotag'])||count($_REQUEST['Classe']))
		{
			$Cotag=$_REQUEST['Cotag']*1;
			$Classe=$_POST['Classe']*1;
			if($Cotag>0)
			{
				$this->Cotag=$Cotag;
				$File=ORM::Query(new ReviewFile)->GetRecentFile($Cotag);
				$this->Classe=$File->GetClass();

			}
			else if($Classe>0)
			{
				$File=ORM::Query(new ReviewFile)->GetRecentFileByClasse($Classe);
				if($File)
				$this->Cotag=$File->Cotag();
				$this->Classe=$Classe;
			}
			if($File)
			{
				$FileState=$File->State();
				$this->ProcessArray=FileFsm::PossibleProgresses($FileState);

				$er=$this->ManageProcesses($File);
				if($er)
				{
					$Error[]=$er;
				}
				$this->Result=$this->ManageSuccesses();
					
				if($this->ProcessArray==null)
				{
					$Error[]="هیچ فرایند مکاتباتی برای این پرونده امکان پذیر نیست";
				}
			}
			else
			{
				$Error[]="اظهارنامه‌ای با شماره کوتاژ یا کلاسه داده شده در سیستم ثبت نشده است.";
			}
		}
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		return $this->Present();
	}

	function GetInputClass(){
		$inputclass='';
		foreach ($_POST as $key=>$value){
			if(substr($key, 0,3)=="###"){
				$inputclass=substr($key, 3,strlen($key)-3 );
			}
		}
		return $inputclass;
	}

	function ManageProcesses(ReviewFile $File){
		$input_class=$this->GetInputClass();
		if($input_class!=''){
			switch ($input_class){
				case 'ProcessRegister':
					$Res=ORM::Query("ReviewProcessRegister")->AddToFile($File);
					if(!$Res['Error']){
						$this->Redirect("./?Cotag={$File->Cotag()}&success={$input_class}&classe={$File->GetClass()}");
					}
					break;
				case 'ProcessAssign':
					$Res=ORM::Query(new ReviewProcessAssign)->AddToFile($File);
					if(!isset($Res['Error'])){
//						orm::Dump($Res);
						$this->Redirect("./?Cotag={$File->Cotag()}&success={$input_class}&Reviewer={$Res['Class']->Reviewer()->getFullName()}");
					}
					break;
				default:
					$this->Redirect("./demand?Cotag={$File->Cotag()}&input_class={$input_class}");
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
				case 'Senddemand':
					$res='ارسال مطالبه نامه ثبت گردید.';
					break;
				case 'ProcessAssign':
					$res="ارجاع پرونده به کارشناس <b> {$Reviewer}</b> ثبت گردید.";
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