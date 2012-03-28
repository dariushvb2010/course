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
		if($this->Action=="Get")
		{
			$Cs=$_POST['Cotag'];
			foreach ($Cs as $Cotag)
			{
				$F=ORM::Find1("ReviewFile", "Cotag", $Cotag);
				if($F)
					$Files[]=$F;	
			}
		}
		else
		{
			$Cotages=$this->MainForm->List->GetRequest("Cotag");
			$Files=ReviewFile::RegulateWithError($Cotages);
		}
		//-------------------------------------SAVE------------------------
		if(isset($_POST['Save']))
		{
			if($this->Action=="Get")
				$Mail->SaveGet($Files, $this->Error);
			else
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
		}//-------------------------------------EditMail----------------------------
		elseif(isset($_POST['EditMail']))
		{
			$Num=$_POST['MailNum'];
			$Title=$_POST['Title'];
			$Comment=$_POST['Comment'];
			$ID=$_POST['MailID'];
			$Mail=ORM::Find("Mail", $ID);
			if(!$Mail)
			{
				$this->Error[]="نامه یافت نشد.";
				return;
			}
			if($Mail->Edit($Num, $Title, $Comment))
				$this->Result.="مشخصات نامه با موفقیت تغییر یافت.";
			else 
				$this->Error[]="مشخصات نامه تغییر نیافت.";
		}
		//------------------------------------SHOW THE LIST------------------------------
		else
		{
			$this->MakeMainForm();
		}
		$this->MakeEditForm();
	}
	protected function Validation()
	{
		
	}
	function ShowMails(){}
	protected function MakeEditForm()
	{
		if(!$this->Mail->CanEdit())
			return;
		$f=new AutoformPlugin("post");
		$f->Style="margin-right:20px;";
		$f->AddElement(array("Type"=>"custom", "HTML"=>"شناسه: ".$this->Mail->ID()));
		$f->AddElement(array("Type"=>"hidden", "Name"=>"MailID", "Value"=>$this->Mail->ID()));
		$f->AddElement(array("Type"=>"text","Name"=>"MailNum","Label"=>"شماره نامه", "Value"=>$this->Mail->Num()));
		$f->AddElement(array( "Type"=>"text", "Name"=>"Title","Label"=>"عنوان نامه", "Value"=>$this->Mail->Subject()));
		$f->AddElement(array("Type"=>"textarea","Name"=>"Comment","Label"=>"توضیحات", "Value"=>$this->Mail->Description()));
		$f->AddElement(array("Type"=>"submit","Name"=>"EditMail","Value"=>"ویرایش مشخصات نامه"));
		$this->EditForm=$f;
	}
	function __construct($Action, $Source, $Dest, $Mail)
	{
		$this->Mail=$Mail;
		parent::__construct($Action, $Source, $Dest);
	}
	function MailDescription()
	{
		
	}
}