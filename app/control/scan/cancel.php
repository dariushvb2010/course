<?php
class ScanCancelController extends JControl
{
	function Start()
	{
		j::Enforce("Scan");
		if (count($_POST))
		{
			
			$Cotag=$_POST['Cotag']*1;
			$Res=ORM::Query("ReviewProgressScan")->CancelCotag($Cotag);
			if(is_string($Res))
			{
				$Error[]=$Res;
			}
			else 
			{

				$this->Result=true;
				$this->Result="وصول اظهارنامه با شماره کوتاژ "."<span style='font-size:20px; color:black; font-weight:bold;'>".$Cotag."</span>"."لغو شد.";
				
				
			}
		}
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;

		return $this->Present();
	}
}