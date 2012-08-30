<?php
class ConfigAlarmController extends AlarmController
{
	function Start()
	{
		if(count($_POST) AND !isset($_POST['AutoEditSave']))
		{
			
			$this->SetPost();
			$res=ORM::Query("ConfigAlarm")->Add($this->Title, $this->Context, $this->Moratorium,$this->SelectedUserIDs, 
			$this->SelectedGroups, $this->SelectedMakerEvents, $this->SelectedKillerEvents, $this->DeleteAccess, $this->Comment, $this->ConfigStyle);
				
			if($res instanceof ConfigAlarm)
			$Alarm=$res;
			else if(count($res))
			$Error=$res;
	
			$this->Alarm=$Alarm;
			$this->Error=$Error;
			$this->Result="هشدار با موفقیت ایجاد شد.";
				
		}
	
		$this->Set();
		$this->MakeForm("Config");
 		$Error=array();
		$this->MakeList();
		$this->List->Update("ConfigAlarm", $Error);
		$this->ConfigError=$Error;
		if(count($this->Error))
		$this->Result=false;
		if(count($this->ConfigError))
		$this->ConfigResult=false;
		return $this->Present();
	
	}
	private function MakeList()
	{
		$Data=ORM::Query("ConfigAlarm")->GetAll();
		$al=new AutolistPlugin($Data);
		$al->ObjectAccess=true;
		$al->HasTier=true;
		$al->TierLabel=true;
		$al->SetHeader("ID", "شناسه",false,false,false);
		$al->SetHeader("Title", "عنوان");
		$al->SetHeader("Context", "متن");
		$al->SetHeader("MoratoriumInDays", "مهلت");
		$al->SetHeader("Context", "متن");
		$al->SetHeader("Style", "نوع", false,false,false);
		$al->EnableEdit("ویرایش","ID");
		$this->List=$al;
		
	}
}
?>