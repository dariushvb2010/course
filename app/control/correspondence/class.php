<?php
class CorrespondenceClassController extends JControl
{
	function Start()
	{
		j::Enforce("Correspondence");
		if (count($_GET))
		{
			if (isset($_GET['Cotag']))
			{
				$Cotag=$_GET['Cotag'];
				$File=ORM::Query(new ReviewFile)->GetRecentFile($Cotag);
				if ($File==null)
				{
					$Error[]="اظهارنامه‌ای با شماره کوتاژ داده شده در سیستم ثبت نشده است.";
				}else{
					$class=$File->GetClass();
					if($class>0){
						$this->file=$File;
						$this->class=$class;
					}else{
						$Error[]="اظهارنامه‌ای با شماره کوتاژ داده شده ثبت کلاسه نشده است.";
					}
				}
			} 
				
			$this->Cotag=$Cotag;
		}
		$this->Error=$Error;
		if (count($Error))
		$this->Result=false;
		return $this->Present();
	}
}