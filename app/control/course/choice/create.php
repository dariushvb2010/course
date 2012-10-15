<?php
class CourseChoiceCreateController extends BaseControllerClass
{
    function Start ()
    {
 		j::Enforce("MasterHand");
		
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
		$this->Form = $this->makeform();
	    $this->Error=$Error;
	    $this->Result=$Result;
	    if(count($this->Error))
	    	$this->Result = false;
    	return $this->Present();
    }
    
    function makeform(){
		$f=new AutoformPlugin("post");
		$f->AddElement(
				array(
						"Type"=>"text",
						"Label"=>"عنوان",
						"Name"=>"Title",
				));
		$f->AddElement(
				array(
						"Type"=>"text",
						"Label"=>"توضیحات",
						"Name"=>"Description",
				));
		$f->AddElement(
				array(
						"Type"=>"submit",
						"Value"=>"ایجاد",
				));
		return $f;
	}
	
}
?>
