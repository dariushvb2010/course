<?php 
class DeskAlarmController extends JControl
{
	function Start()
	{
		$this->User=MyUser::CurrentUser();
		if($this->User && $this->User->Group())
			$this->GroupTitle=$this->User->Group()->PersianTitle();
		
		$this->Alarm_Personal=ORM::Query("Alarm")->CurrentUserAlarms_Personal(0,9999999);
		$this->Alarm_Group=ORM::Query("Alarm")->CurrentUserAlarms_Group(0,9999999);
		
		return $this->Present();
	}
}
