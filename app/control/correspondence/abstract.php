<?php
/**
 * childs of this controller:
 * 1- Register
 * 2- Address
 * 3- Prophecy
 * 4- Protest
 * @author dariush
 *
 */
class CorrespondenceAbstractController extends JControl
{
	/**
	 * sets $this->Cotag, $this->File and $this->input_class
	 * @return
	 */
	function Initialize(){
		$this->Cotag = b::CotagFilter($_REQUEST['Cotag']);
		$this->File=b::GetFile($this->Cotag);
		$this->input_class=$_GET['input_class'];
		$this->Comment = $_REQUEST['Comment'];
	}
	function Start()
	{
		return $this->Present();
	}
	
	function makeFormTemplate($addIndicator=false){
		$f = new AutoformPlugin('post');
		$f->AddElement(array(
				'Name'=>'Cotag',
				'Label'=>p::Cotag,
				'Value'=>$this->Cotag
				));
		if($addIndicator)
		$f->AddElement(array(
				"Type"=>"text",
				"Name"=>"Indicator",
				"Label"=>"اندیکاتور",
		));
		return $f;
	}
	
	
	
}