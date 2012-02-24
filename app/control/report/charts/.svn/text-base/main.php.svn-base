<?php
class ReportChartsMainController extends JControl
{
	function Start()
	{
		$days=30;
		$r=ORM::Query(new ReviewProgressStart)->DailyStart($days);
		$this->firstday=(time()-24*60*60*$days)*1000;
		foreach ($r as $key =>$value){
			$daily1[]=$value['count'];
		}
		$this->daily1=$daily1;
		$this->Error=$Error;
		if(Count($Error))
			$this->Result=false;
		return $this->Present();
	}

}