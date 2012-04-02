<?php
class ModerateController extends JControl
{
	
	function Start()
	{
		FileFsm::Moderate1();
		return $this->Present();
	}
	
}