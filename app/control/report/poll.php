<?php
class ReportPollController extends BaseControllerClass
{
    function Start ()
    {
		if(isset($_POST['Title'])){
			$title = $_POST['Title'];
			$desc = $_POST['Description'];
			$r = ORM::Query('CourseChoice')->Add($title, $desc);
			if(is_string($r))
				$Error[] = $r;
			else
				$Result = 'ایجاد شد.';
		}
		$r = ORM::Query('CourseChoice')->GetAllWithPollScore();
		$this->ChoiceArray = $r;
	    $this->Error=$Error;
	    $this->Result=$Result;
	    if(count($this->Error))
	    	$this->Result = false;
    	return $this->Present();
    }
    
    
	
}
?>
