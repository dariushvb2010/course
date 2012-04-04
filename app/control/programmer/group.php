<?php
class ProgrammerGroupController extends JControl
{
	function Start()
	{
		j::Enforce("MasterHand");
		print_r($_POST);
		if(isset($_POST['Create']) and !isset($_POST['AutoEditSave']))
		{
			
			$Title=$_POST['Title'];
			$PersianTitle=$_POST['PersianTitle'];
			$Description=$_POST['Description'];
			
			$Res=ORM::Query(new MyGroup())->Add($Title, $PersianTitle, $Description);
			if(is_string($Res))
			{
				$Error[]=$Res;
			}
			else
			{
				$this->Result="گروه با موفقیت ایجاد شد.";
			}
		}
		else if(isset($_POST['Assign']))
		{
			$uids=$_POST['User'];
			$gid=$_POST['Group'];
			$g=ORM::Find("MyGroup", $gid);
			
			if($uids)
			foreach($uids as $uid)
			{
				$u=ORM::Find("MyUser", $uid);
				if($u)$u->SetGroup($g);
			}
			$this->UserResult="با موفقیت انتصاب یافت.";
		}
		
		$Groups=ORM::Query("MyGroup")->GetAll();
		//ORM::Dump($Groups);
		$this->MakeList($Groups);
		if($Groups)
		foreach ($Groups as $G)
		{
			$gs[$G->ID()]=$G->PersianTitle();
		}
		$this->Groups=$gs;
		$Users=ORM::Query("MyUser")->GetAll();
		if($Users)
		foreach ($Users as $U)
		{
			$us[$U->ID()]=$U->getFullName();
		}
		$this->Users=$us;
		$this->List->Update("MyGroup", $Error);
		$this->MakeForm();
		$this->MakeUserForm();
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
			
		return $this->Present();
	}
	private function MakeList($Groups)
	{
		$al=new AutolistPlugin($Groups);
		$al->HasTier=true;
		$al->SetHeader('ID',"شناسه",false,false,false);
		$al->SetHeader('Title',"عنوان انگلیسی");
		$al->SetHeader('PersianTitle',"عنوان فارسی");
		$al->SetHeader('Description',"توضیحات");
		$al->ObjectAccess=true;
		$al->Width="auto";
		$al->EnableEdit();
		$this->List=$al;
	}
	private function MakeUserForm()
	{
		$f=new AutoformPlugin("post");
		$f->AddElement(array(
							"Type"=>"select",
							"Name"=>"Group",
							"Label"=>"گروه",
							"Options"=>$this->Groups
		));
		$f->AddElement(array(
							"Type"=>"select",
							"Name"=>"User[]",
							"Multiple"=>"Multiple",
							"Label"=>"کاربران",
							"Options"=>$this->Users
		));
		$f->AddElement(array(
							"Type"=>"submit",
							"Name"=>"Assign",
							"Value"=>"انتصاب",
		));
		$this->UserForm=$f;
	}
	private function MakeForm()
	{
		$f=new AutoformPlugin("post");
		$f->AddElement(array(
						"Type"=>"text",
						"Name"=>"Title",
						"Label"=>"عنوان انگلیسی",
						"Value"=>$this->Title
		));
		$f->AddElement(array(
						"Type"=>"text",
						"Name"=>"PersianTitle",
						"Label"=>"عنوان فارسی",
						"Value"=>$this->PersianTitle
		));
		$f->AddElement(array(
						"Type"=>"textarea",
						"Name"=>"Context",
						"Label"=>"توضیحات ",
						"Value"=>$this->Description
		));
		$f->AddElement(array(
						"Type"=>"submit",
						"Name"=>"Create",
						"Value"=>"ایجاد",
		));
		$this->Form=$f;
	}
}
?>
