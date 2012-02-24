<?php
class ConfigMainController extends BaseControllerClass
{

	function Start()
	{
		if(count($_POST) && !isset($_POST['AutoEditSave']))
		{
			$this->Name=$_POST['Name'];
			$this->Value=$_POST['Value'];
			$this->Comment=$_POST['Comment'];
			$this->DeleteAccess= $_POST['DeleteAccess'];
			$this->PersianName=$_POST['PersianName'];
			$res=ConfigMain::Add($this->Name, $this->Value, $this->DeleteAccess, $this->Comment, $this->PersianName);
			if(is_string($res))
				$Error[]=$res;
			else 
			{
				$Result="با موفقیت اضافه شد. نام: ".$res->Name();
				$Result.=" مقدار: ".$res->Value();
			}
			
		}
 		$this->MakeList();
 		$Error=array();
 		$this->List->Update("ConfigMain", $Error);
		$this->MakeForm();
		$this->Result=$Result;
		$this->Error=$Error;
		if(count($this->Error))
			$this->Result=false;
		return $this->Present();

	}
	function MakeList()
	{
		$ConfigMains=ORM::Query("ConfigMain")->GetAll();
		$al=new AutolistPlugin($ConfigMains);
		$al->Width="auto";
		$al->HasSelect=true;
		$al->ObjectAccess=true;
		$al->SetHeader("ID", "شناسه",false,false,false);
		$al->SetHeader("Name", "عنوان",false,false,false);
		$al->SetHeader("PersianName", "عنوان فارسی");
		$al->SetHeader("Value", "مقدار");
		$al->SetHeader("DeleteAccess", "قابل حذف",false,false,false);
		$al->SetHeader("Comment","توضیحات");
		$al->HasEdit=true;
		$al->HasTier=true;
		$al->EnableEdit();
		$this->List=$al;
	}
	function MakeForm()
	{
		$f=new AutoformPlugin("post");
		$f->AddElement(array(
						"Type"=>"text",
						"Name"=>"Name",
						"Validation"=>"alphanumeric",
						"Label"=>"نام",
						"Value"=>$this->Name
		));
		$f->AddElement(array(
						"Type"=>"text",
						"Name"=>"Value",
						"Label"=>"مقدار",
						"Value"=>$this->Value
		));
		$f->AddElement(array(
						"Type"=>"text",
						"Name"=>"PersianName",
						"Label"=>"عنوان فارسی",
						"Value"=>$this->PersianName
		));
		$f->AddElement(array(
						"Type"=>"textarea",
						"Name"=>"Comment",
						"Label"=>"توضیحات",
						"Value"=>$this->Comment
		));
		
		$f->AddElement(array(
								"Type"=>"select",
								"Name"=>"DeleteAccess",
								"Label"=>"امکان حذف",
								"Options"=>array('1'=>'بله',
												'0'=>'خیر'),
								"Default"=>$this->DeleteAccess
		));
		
		$f->AddElement(array(
						"Type"=>"submit",
						"Value"=>"اضافه",
		));
		$this->Form=$f;
	}
}
?>