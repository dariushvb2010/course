<?php
class ConfigEventController extends BaseControllerClass
{

	function Start()
	{
		//FileFsm::Moderate();
		if(count($_POST))
		{
			$Name=$_POST['Name'];
			$PersianName=$_POST['PersianName'];
			$DeleteAccess= $_POST['DeleteAccess'];
			$Comment=$_POST['Comment'];

				try
				{
					
					ORM::Query("ConfigEvent")->Add($Name,$PersianName, $DeleteAccess,$Comment);
				}
				catch(Exception $e)
				{
					$Error[]="امکان اضافه کردن وجود ندارد.";
				}
			
		}
		
		$Events=ORM::Query("ConfigEvent")->GetAll();
		
		$this->List=$this->MakeList($Events);
		if(count($this->Error))
			$this->Result=false;
		return $this->Present();
	}
	
	function MakeList($Events)
	{
		$al=new AutolistPlugin($Events,null,"Select");
		$al->Width="auto";
		$al->HasSelect=true;
		$al->HasForm=true;
		$al->ObjectAccess=true;
		$al->SetHeader("ID", "شناسه",false,false,false);
		$al->SetHeader("EventName", "عنوان");
		$al->SetHeader("PersianName","عنوان فارسی");
		$al->SetHeader("Comment","توضیحات");
		$al->HasEdit=true;
		$al->HasTier=true;
		return $al;
	}
}
?>