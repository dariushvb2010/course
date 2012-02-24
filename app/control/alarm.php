<?php
class AlarmController extends BaseControllerClass
{

	function Start()
	{
		if(count($_POST))
		{
			$this->SetPost();
			
			$res=ORM::Query("AlarmFree")->Add($this->Cotag,$this->Title, $this->Context, $this->Moratorium,
			 $this->SelectedUserIDs,$this->SelectedGroups, $this->SelectedKillerEvents);
			
			if($res instanceof AlarmFree)
				$Alarm=$res;
			else if(count($res))
				$Error=$res;
				
			$this->Alarm=$Alarm;
			$this->Error=$Error;
			$this->Result="هشدار با موفقیت ایجاد شد.";
			
		}
		
		$this->Set();
		$this->MakeForm("Free");
		
		if(count($this->Error))
			$this->Result=false;
		return $this->Present();

	}
	protected function SetPost()
	{
		$this->Cotag=$_POST['Cotag'];
		$this->Title=$_POST['Title'];
		$this->Moratorium=$_POST['Moratorium'];
		$this->Context=$_POST['Context'];
		$this->SelectedGroups=$_POST['Group'];
		$this->SelectedUserIDs=$_POST['User'];
		$this->SelectedKillerEvents=$_POST['KillerEvent'];
		//---------- only for ConfigAlarm-------------
		$this->Comment=$_POST['Comment'];
		$this->DeleteAccess=$_POST['DeleteAccess'];
		$this->SelectedMakerEvents=$_POST['MakerEvent'];
		$this->ConfigStyle=$_POST['Style'];
	}
	
	protected function Set()
	{
		$CU=MyUser::CurrentUser();
		//-------------Groups--------------
		$Groups=$CU->LegalGroups();
		if($Groups)
		foreach($Groups as $G)
		{
			$GroupTitles[$G->ID()]=$G->RTitle();
		}
		$GroupTitles['All']="++همه++";
		$GroupTitles['None']="--هیچکدام--";
		$this->Groups=$GroupTitles;
		//------------Users-----------------
		$Users=MyUser::UsersOfGroups($Groups);
		if($Users)
		foreach($Users as $U)
		{
			$FullNames[$U->ID()]=$U->GetFullName();
		}
		$FullNames[-1]="--هیچ کدام--";
		$this->Users=$FullNames;
		
		//--------------Events------------------
		$Events=ORM::Query("ConfigEvent")->GetAll();
		if($Events)
		foreach ($Events as $Event)
		{
			if($Event->PersianName()==null OR $Event->PersianName()=="")
			$E[$Event->ID()]=$Event->EventName();
			else
			$E[$Event->ID()]=$Event->PersianName();
		}
		$this->Events=$E;
	}
	
function MakeForm($type="Free")
	{
		$f=new AutoformPlugin("post");
		if($type=="Free")
		$f->AddElement(array(
				"Type"=>"text",
				"Name"=>"Cotag",
				"Validation"=>"number",
				"Label"=>"کوتاژ",
				"Placeholder"=>"کوتاژ",
				"Value"=>$this->Cotag
		));
		$f->AddElement(array(
				"Type"=>"text",
				"Name"=>"Title",
				"Label"=>"عنوان",
				"Placeholder"=>"عنوان",
				"Width"=>"250px",
				"Value"=>$this->Title
		));
		$f->AddElement(array(
				"Type"=>"textarea",
				"Name"=>"Context",
				"Label"=>"متن یادآور",
				"Width"=>"250px",
				"Value"=>$this->Context
		));
		$f->AddElement(array(
				"Type"=>"text",
				"Name"=>"Moratorium",
				"Validation"=>"number",
				"Label"=>"مدت زمان مهلت به روز",
				"Placeholder"=>"مهلت",
				"Width"=>"250px",
				"Value"=>$this->Moratorium
		));
		if($type=="Config")
		$f->AddElement(array(
						"Type"=>"select",
						"Name"=>"Style",
						"Label"=>"نوع",
						"Width"=>"250px",
						"Options"=>ConfigData::$CONFIG_STYLE['Alarm'],
						"Default"=>$this->ConfigStyle
		));
		$f->AddElement(array(
				"Name"=>"Group[]",
				"Type"=>"select",
				"Multiple"=>"Multiple",
				"Size"=>5,
				"Width"=>"150px",
				"Options"=>$this->Groups,
				"Label"=>"گروه کاربران",
				"Width"=>"250px",
		));
		$f->AddElement(array(
				"Name"=>"User[]",
				"Type"=>"select",
				"Multiple"=>"Multiple",
				"Size"=>7,
				"Width"=>"150px",
				"Options"=>$this->Users,
				"Label"=>"کاربران",
				"Width"=>"250px",
		));
		if($type=="Config")
		$f->AddElement(array(
				"Name"=>"MakerEvent[]",
				"Type"=>"select",
				"Multiple"=>"Multiple",
				"Size"=>7,
				"Width"=>"150px",
				"Options"=>$this->Events,
				"Label"=>"رویدادهای فعال کننده",
				"Width"=>"250px",
		));
		$f->AddElement(array(
				"Name"=>"KillerEvent[]",
				"Type"=>"select",
				"Multiple"=>"Multiple",
				"Size"=>7,
				"Width"=>"150px",
				"Options"=>$this->Events,
				"Label"=>"رویدادهای غیرفعال کننده",
				"Width"=>"250px",
		));
		if($type=="Config")
		$f->AddElement(array(
				"Name"=>"DeleteAccess",
				"Type"=>"select",
				"Width"=>"150px",
				"Options"=>array(0=>"خیر",
								1=>"بله"),
				"Label"=>"امکان حذف",
				"Width"=>"250px",
		));
		if($type=="Config")
		$f->AddElement(array(
				"Type"=>"textarea",
				"Name"=>"Comment",
				"Label"=>"توضیحات",
				"Width"=>"250px",
		));
		$f->AddElement(array(
				"Type"=>"submit",
				"Value"=>"ایجاد",
		));
		$this->Form=$f;
	}
}
?>