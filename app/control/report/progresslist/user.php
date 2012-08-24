<?php
class ReportProgresslistUserController extends JControl
{
	function Start()
	{
		$Error=array();
		$User=ORM::find(new MyUser,j::UserID());
		$this->Data=$User->RecentProgresses(100);
		$this->Count=ORM::Query(MyUser)->CountProgresses($User);
		if(count($this->Data)==0)
			$Error[]='هیچ فرایندی توسط شما در سیستم انجام نشده.';
				
		$this->User=$User;	
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		return $this->Present();
	}
}