<?php
class CorrespondenceAddprocessController extends JControl

{
	function Start()
	{
		if (count($_GET))
		{
			$Cotag=b::CotagFilter($_POST['Cotag']);
			$Res=ORM::Query("ReviewProgressFinishCorrespondence")->AddToFile($Cotag);
			if(is_string($Res))
			{
				
			}
			else 
				$this->Result="مکاتبه با موفقیت ختم شد.";
		}
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		return $this->Present();
	}
}