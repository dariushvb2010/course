<?php

/**
 * 
 * Progress and state base class units of Finite State Mahine
 * @author kavakebi
 *
 */
class FsmElement {

	public $Label;
	public $Name;
	/**
	 * @example rawData['is_MokatebatViewable']
	 * @var array of attributes
	 */
	public $rawData;
	
	function __construct($inp = null) {
		if ($inp) {
			if (is_string($inp))
				$this->Label = $inp;
			elseif (is_array($inp))
				$this->Label = $inp['Label'];

			$this->rawData = $inp;
		}
	}
	
	function getAttribute($attrib){
		if(isset($this->rawData[$attrib]))
			return $this->rawData[$attrib];
		else
			return null;
	}

}
