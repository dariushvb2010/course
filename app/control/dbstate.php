<?php

class DbstateController extends BaseControllerClass
{

	function Start()
	{
		$num=$_GET['num'];
		if($num>0)
			call_user_func("FileFsm::Moderate{$num}");
		return $this->BarePresentString('--');		
	}
}
?>