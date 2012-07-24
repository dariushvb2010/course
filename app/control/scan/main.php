<?php
class ScanMainController extends JControl
{
	function Start()
	{

		if (count($_POST))
		{
			$this->cotag=$_POST['Cotag'];
			$this->sid=session_id();
			
		}
		
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		return $this->Present();
	}	
}