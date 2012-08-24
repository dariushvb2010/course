<?php

class CorrespondenceProphecyController extends JControl

{
	function Start()
	{
		j::Enforce("Correspondence");
		
		if (count($_POST))
		{
			$Cotag=b::CotagFilter($_POST['Cotag']);
			$Res=ORM::Query("ReviewProgressFinishCorrespondence")->AddToFile($Cotag);
			if(is_string($Res))
			{
				$Error[]=$Res;
			}
			else 
				$this->Result="مکاتبه با موفقیت ختم شد.";
		}
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		return $this->Present();
	}
}