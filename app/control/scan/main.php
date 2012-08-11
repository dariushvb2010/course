<?php
class ScanMainController extends JControl
{
	function Start()
	{
                $this->title='اظهارنامه الکترونیک';
		if (count($_POST))
		{
			$this->cotag=$_POST['Cotag'];
			$sid2=session_id();
                        $this->action='create';
		}
		$this->sid = $sid2;
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		return $this->Present();
	}	
}