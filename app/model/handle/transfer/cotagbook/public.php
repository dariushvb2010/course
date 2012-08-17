<?php

class HandleTransferCotagbookPublic extends HandleTransferCotagbookSingle
{
	public $MainList;
	function Perform()
	{
		
		if(isset($_POST['Create']))
		{
			$Num=$_POST['Num'];
			$Subject=$_POST['Subject'];
			$Description=$_POST['Description'];
			
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
			$Cotag=b::CotagFilter($_POST['Cotag']);
			$CDate=$c->JalaliToGregorian($CYear,$CMonth, $CDay);
			$FDate=$c->JalaliToGregorian($FYear, $FMonth, $FDay);
			$StartTimestamp=strtotime($CDate[0]."/".$CDate[1]."/".$CDate[2]." ".$CHour.":".$CMin);
			$FinishTimestamp=strtotime($FDate[0]."/".$FDate[1]."/".$FDate[2]." ".$FHour.":".$FMin);
			
			if(!$this->validateDate($CYear,$CMonth,$CDay))
			{
				$this->Error[]="تاریخ آغازی ناصحیح است.";
			}
			elseif(!$this->validateDate($FYear,$FMonth,$FDay))
			{
				$this->Error[]="تاریخ پایانی ناصحیح است.";
			}
			elseif(!$Num)
			{
				$this->Error[]="شماره نامه نمی تواند خالی باشد.";
			}
			else 
			{
				$Files=ORM::Query(new ReviewFile())->FilesInTimeRange($StartTimestamp,$FinishTimestamp,MyUser::CurrentUser());
				$Mail=ORM::Query("MailGive")->Add($Num, $Subject, $this->SourceGroup, $this->DestGroup, $Description);
				if(is_string($Mail))
					$this->Error[]=$Mail;
				elseif(!count($this->Error))
				{
					$this->Result.="نامه با شماره ".$Mail->Num()." با موفقیت ایجاد شد.";
					ORM::Flush();//i want to use the ID of the created Mail thus i have to Flush()!
					$this->Mail=$Mail;
				}
			}
				
			if(count($this->Error))
				$this->Result=false;
		}
		else
		{
			if($this->Action=="Give" OR $this->Action=="Get")
			if(!$this->SourceGroup OR !$this->DestGroup)
			{
				$this->Error[]="گروه یافت نشد.";
				return;
			}
		}
		$this->MakeMainList($Files);
		$this->MakeSearchForm();
		//$this->ShowMails();
	}
	function ShowMails()
	{
		$Mails=ORM::Query("MailGive")->GetAll($this->SourceGroup,$this->DestGroup, MailGive::STATE_EDITING);
		ViewMailPlugin::GroupShow($Mails,"Give");
	}
	private function validateDate($y,$m,$d)
	{
		if($y<1357)
		return false;
		else if($m<1 || $m>12)
		return false;
		else if($d>31||$d<1)
		return false;
		return true;
	}
	function __construct($Action, $Source, $Dest)
	{
		parent::__construct($Action, $Source, $Dest,null);
	}
}