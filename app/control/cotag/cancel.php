<?php
class CotagCancelController extends JControl
{
	function Start()
	{
		j::Enforce("CotagBook");
		if (count($_POST))
		{
			
			$Cotag=$_POST['Cotag']*1;
			$Res=ORM::Query(new ReviewProgressStart())->CancelCotag($Cotag);
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