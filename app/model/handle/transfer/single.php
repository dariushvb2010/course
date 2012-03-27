<?php
class HandleTransferSingle extends HandleTransfer
{
	public $List;
	function Perform()
	{
		parent::Perform();
	
		$Mail=$this->Mail;
		if(!$Mail)
		{
			$this->Error[]="نامه مورد نظر یافت نشد.";
			return ;
		}
			$this->MakeMainForm();
			$Cotages=$this->MainForm->List->GetRequest("Cotag");
			$Files=ReviewFile::RegulateWithError($Cotages);
		//-------------------------------------SAVE------------------------
		if(isset($_POST['Save']))
		{
			
			
			$Mail->Save($Files,$this->MainForm->List->RemoveCalled(),$this->Error);
		}
		//-------------------------------------ACT: [Gvie], [Get], [Send], [Receive]-------------------------
		elseif(isset($_POST[$this->Action]))
		{
			$res=$Mail->{$this->Action}($Files, $this->MainForm->List->RemoveCalled(), $this->Error);
			if($res)
				$this->Result="اظهارنامه ها با موفقیت ارسال شدند.";
			else 
				$this->Error[]="ارسال نشد. لطفا خطاهای موجود را رفع نمایید.";
		}
		//------------------------------------SHOW THE LIST------------------------------
		else
		{
			$this->MakeMainForm();
		}
	}
	protected function Validation()
	{
		
	}
	
	function __construct($Action, $Source, $Dest, $Mail)
	{
		parent::__construct($Action, $Source, $Dest);
		$this->Mail=$Mail;
	}
	function MailDescription()
	{
		
	}
}