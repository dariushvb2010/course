<?php

/**
 * 
 * Progress units of Finite State Mahine
 * @author kavakebi
 *
 */
class FsmProgress extends FsmElement {
	
	public $beginState;
	public $nxtState;
	
	function __construct($inp = null) {
		parent::__construct($inp);
	}
	
	function is_printing(){
		return $this->getAttribute('is_Printing');
	}
	
	function is_MokatebatViewable(){
		$r = $this->getAttribute('is_MokatebatViewable');
		var_dump($r);
		if($r===false)
			return false;
		else
			return true;
	}
	
}
