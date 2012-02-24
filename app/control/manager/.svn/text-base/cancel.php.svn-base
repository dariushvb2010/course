<?php
class ManagerCancelController extends JControl
{
	function Start()
	{
		j::Enforce("CotagBook");
		if (count($_POST))
		{
			
			$Cotag=$_POST['Cotag']*1;
			$Res=ORM::Query(new ReviewProgressRegisterarchive())->CancelCotag($Cotag);
			if(is_string($Res))
			{
				$Error[]=$Res;
			}
			else 
			{

				$this->Result=true;
				$this->Result="وصول اظهارنامه با شماره کوتاژ ".$Cotag."لغو شد.";
				
				
			}
		}
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;

		return $this->Present();
	}
}