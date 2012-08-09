<?php
class HandleTransferCotagbookSingle extends HandleTransferSingle
{
	public $List;
	public $Files;
	function Perform()
	{
		$Mail=$this->Mail;
		if(!$Mail)
		{
			$this->Error[]="نامه مورد نظر یافت نشد.";
			return ;
		}
		$this->MakeMainList(null);
		$Cotages=$this->MainList->GetRequest("Cotag");
		$Files=ReviewFile::RegulateWithError($Cotages);
		//-------------------------------------SAVE------------------------
		if(isset($_POST['Save']))
		{
			if($this->Action=="Get")
				$Mail->SaveGet($Files, $this->Error);
			else
				$Mail->Save($Files,$this->MainList->RemoveCalled(),$this->Error);
		}
		elseif(isset($_POST['Date']))
		{
			$c=new CalendarPlugin();
			$CYear=$_POST['CYear'];
			$CMonth=$_POST['CMonth'];
			$CDay=$_POST['CDay'];
			$CHour=$_POST['CHour'];
			$CMin=$_POST['CMin'];
			$FYear=$_POST['FYear'];
			$FMonth=$_POST['FMonth'];
			$FDay=$_POST['FDay'];
			$FHour=$_POST['FHour'];
			$FMin=$_POST['FMin'];
			$Cotag=$_POST['Cotag']*1;
			$CDate=$c->JalaliToGregorian($CYear,$CMonth, $CDay);
			$FDate=$c->JalaliToGregorian($FYear, $FMonth, $FDay);
			$StartTimestamp=strtotime($CDate[0]."/".$CDate[1]."/".$CDate[2]." ".$CHour.":".$CMin);
			$FinishTimestamp=strtotime($FDate[0]."/".$FDate[1]."/".$FDate[2]." ".$FHour.":".$FMin);
			$NewFiles=ORM::Query("ReviewFile")->FilesInTimeRange($StartTimestamp,$FinishTimestamp,MyUser::CurrentUser());
			$this->MakeMainList($NewFiles);
		}
		//-------------------------------------ACT: [Gvie], [Get], [Send], [Receive]-------------------------
		elseif(isset($_POST[$this->Action]))
		{
			$res=$Mail->Complete($Files, false, $this->Error);
			if($res)
			{
				$this->Result="اظهارنامه ها با موفقیت ارسال شدند.";
			}
			else 
				$this->Error[]="ارسال نشد. لطفا خطاهای موجود را رفع نمایید.";
			$this->MakeMainList($Files);
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
			$this->MakeMainList($Files);
		
		$this->MakeEditForm();
	}
	protected function MakeMainList($Files)
	{
		
		if(!$this->Mail)
			return ;
		$Data=$this->Mail->GetProgress();
		if($this->Mail->State()==Mail::STATE_EDITING)
			$al=new DynamiclistPlugin($Files);
		else 
			$al=new DynamiclistPlugin($Data);
		$al->SetHeader('Cotag', 'کوتاژ', "text");
		$al->Width="80%";
		$al->HasTier=true;
		$al->TierLabel="ردیف";
		$al->HasFormTag=ture;
		$al->HasRemove=false;
		$al->ObjectAccess=true;
		$al->InputValues['ColsCount']=5;
		$al->InputValues['RowsCount']=30;
		$al->_TierAttr=array("style"=>"font-size:11pt;");//ردیف
		
		$f=new AutoformPlugin();
		$f->HasFormTag=false;
		if($this->Mail->State()==Mail::STATE_EDITING)
			$f->AddElement(array("Type"=>"submit", "Value"=>$this->PersianAction(), "Name"=>$this->Action));
		$f->AddElement(array("Type"=>"hidden", "Name"=>"MailID", "Value"=>$this->Mail->ID()));
		$al->Autoform=$f;
		$al->AutoformAfter=true;
		$this->MainList=$al;
	}
	function ShowMails(){}
	
	function MailDescription()
	{
		
	}
}