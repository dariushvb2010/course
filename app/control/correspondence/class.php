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
				$File=b::GetFile($Cotag);
				if ($File==null)
				{
					$Error[]=v::Ecnf($Cotag);
				}else{
					$class=$File->Classe();
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