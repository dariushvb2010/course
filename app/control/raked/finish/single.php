<?php
class RakedFinishSingleController extends JControl
{
	function Start()
	{
		j::Enforce("Raked");
		
		if (count($_POST))
		{
			$Cotag=b::CotagFilter($_POST['Cotag']);
			$Res=ORM::Query(new ReviewProgressFinish())->FinishByCotag($Cotag);
			if(is_string($Res))
			{
				$Error[]=$Res;
			}
			else 
				$this->Result="با موفقیت مختومه گردید";
		}
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		return $this->Present();
	}
}