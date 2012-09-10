<?php
class TimeoutMainController extends JControl

{
	function Start()
	{
		//'Prophecy_first'=>41,
		//'Prophecy_second'=>47,
		//'Prophecy_setad'=>58,
		//'Prophecy_commission'=>63,
		$DaySeconds=30*30*24;
		$Files=ORM::Query(new ReviewFile)->ExpiredStateFiles('Prophecy_first',$DaySeconds*10);
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		return $this->BarePresent();
	}

}