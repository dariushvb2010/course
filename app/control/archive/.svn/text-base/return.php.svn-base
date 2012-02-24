<?php
class ArchiveReturnController extends JControl
{
	function Start()
	{
		j::Enforce("Archive");
		
		if (count($_POST))
		{
			$Cotag=$_POST['Cotag']*1;
			$Res=ORM::Query(new ReviewProgressRegisterarchive)->AddToFile($Cotag);
			if(is_string($Res))
			{
				$Error[]=$Res;
			}
			else 
			{
				$this->Result=true;
				$this->Result="اظهارنامه با شماره کوتاژ ".$Cotag."با موفقیت وصول گردید.";
			}
		}
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		return $this->Present();
	}
}