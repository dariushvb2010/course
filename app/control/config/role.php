<?php
class ConfigEventController extends BaseControllerClass
{

	function Start()
	{
		if(count($_POST))
		{
			$MyRoleID=$_POST['MyRoleID'];
			$Name=$_POST['RoleTitle'];
			$PersianTitle=$_POST['PersianTitle'];
			$DeleteAccess= $_POST['DeleteAccess'];
			$Comment=$_POST['Comment'];
			
				try
				{
					
					ORM::Query("ConfigEvent")->Add($Name,$PersianName, $DeleteAccess,$Comment);
					echo "OK";
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