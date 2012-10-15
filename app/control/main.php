<?php

class MainController extends BaseControllerClass
{

	function Start()
	{
		

		$this->User=MyUser::CurrentUser();
		if($this->User && $this->User->Group())
			$this->GroupTitle=$this->User->Group()->PersianTitle();

		if(count($this->Error))
			$this->Result=false;
		return $this->Present ();

	}
}
?>
