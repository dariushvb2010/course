<?php
class RakedSendtoreviewController extends JControl
{
	function Start()
	{
		j::Enforce("Raked");
		
		if (count($_POST))
		{
			$Cotag=$_POST['Cotag']*1;
			$Res=ORM::Query(new ReviewProgressReturn())->AddToFile($Cotag);
			if(is_string($Res))
			{
				$Error[]=$Res;
			}
			else 
			{
				$this->Result=true;
				$this->Result="اظهارنامه با شماره کوتاژ ".$Cotag."با موفقیت ارسال گردید.";
			}
		}
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		return $this->Present();
	}
}