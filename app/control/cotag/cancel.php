<?php
class CotagCancelController extends JControl
{
	function Start()
	{
		j::Enforce("CotagBook");
		if (count($_POST))
		{
			$Cotag=$_POST['Cotag']*1;
			$Res=ORM::Query("ReviewProgressStart")->CancelCotag($Cotag);
			if(is_array($Res))
			{
				if($Res['result']==true)
					$Result = $Res['message'];
				else 
				$Error[]=$Res['message'];
			}
			else 
			{
				throw new Exception(" return value must be an array!");
				//$this->Result="وصول اظهارنامه با شماره کوتاژ "."<span style='font-size:20px; color:black; font-weight:bold;'>".$Cotag."</span>"."لغو شد.";
				
				
			}
		}
		$this->Result=$Result;
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;

		return $this->Present();
	}
}