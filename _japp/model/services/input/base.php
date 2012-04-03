<?php
abstract class BaseServiceInputFormatter
{
	public $App;
	function __construct(ApplicationController $App)
	{
		$this->App=$App;
	}
	abstract function Format($Data);
}
?>