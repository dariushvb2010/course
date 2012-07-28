<?php

/**
 * 
 * Progress and state base class units of Finite State Mahine
 * @author kavakebi
 *
 */
class FileFsmelementclass extends JModel {

	public $Label;
	public $Name;
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
