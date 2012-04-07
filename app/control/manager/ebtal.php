<?php
class ManagerEbtalController extends JControl
{
	function Start()
	{
		j::Enforce("MasterHand");
		if (count($_POST))
		{
			
			$Cotag=$_POST['Cotag']*1;
			$Res=ORM::Query("ReviewProgressEbtal")->EbtalCotag($Cotag);
			if(is_string($Res))
			{
				$Error[]=$Res;
			}
			else 
			{

				$this->Result=true;
				$this->Result="اظهارنامه با شماره کوتاژ "."<span style='font-size:20px; color:black; font-weight:bold;'>".$Cotag."</span>"."ابطال شد.";
				
				
			}
		}
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;

		return $this->Present();
	}
}