<?php
class ScanAddController extends JControl
{
	function Start()
	{
                $this->title="اضافه کردن عکس";
		if (count($_POST))
		{
			$this->cotag=$_POST['Cotag'];
			$sid2=session_id();
                        $this->action='add';
                        
		}
		$this->sid = $sid2;
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		return $this->Present('','scan/main');
	}	
}