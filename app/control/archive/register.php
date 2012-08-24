<?php
class ArchiveRegisterController extends JControl
{
	function Start()
	{
		j::Enforce("Archive");
		if(isset($_POST["register"]))
		{
			$Cotag = $_POST["Cotag"];
			$res=ORM::Query("ReviewProgressRegisterarchive")->AddToFile($Cotag);
			if(is_string($res))
				$Error[]=$res;
			else 
				$Result="وصول شد.";
		}
		$this->Error=$Error;
		$this->Result=$Result;
		if (count($Error))
			$this->Result=false;
		return $this->Present();
	}
	

}