<?php
class CotagUntiebtalController extends JControl
{
	function Start()
	{
		j::Enforce("CotagBook");
		if (count($_POST))
		{
			
			$Cotag=b::CotagFilter($_POST['Cotag']);
			$Res=ORM::Query("ReviewProgressStart")->UntiEbtalCotag($Cotag);
			if(is_string($Res))
			{
				$Error[]=$Res;
			}
			else 
			{

				$this->Result=true;
				$this->Result="اظهارنامه با شماره کوتاژ "."<span style='font-size:20px; color:black; font-weight:bold;'>".$Cotag."</span>"."وصول مجدد شد.";
				
				
			}
		}
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;

		return $this->Present();
	}
}