<?php
class ManagerNewsController extends BaseControllerClass
{
    function Start ()
    {
    	j::Enforce('MasterHand');
    	
    	
    	$this->makeList();
    	$this->List->Update("News", $Error);
    	$this->makeForm();
    	if(isset($_POST['Add'])){
    		$Title = $_POST['Title'];
    		$Description = $_POST['Description'];
    		$r = ORM::Query('News')->Add($Title, $Description);
    		if($r instanceof News)
    			$Result = 'اضافه شد.';
    		else 
    			$Error = 'اضافه نشد.';
    	}
    	$this->Result = $Result;
    	$this->Error = $Error;
    	return $this->Present();
    }
    function makeList(){
    	$news = ORM::Query('News')->GetAllArray();
    	$al = new AutolistPlugin($news);
    	$al->EnableEdit();
    	$al->SetHeader('Title', 'عنوان');
    	$al->SetHeader('Description', 'متن');
    	$this->List = $al;
    }
    function makeForm(){
    	$f = new AutoformPlugin('post');
    	$f->AddElement(array(
    			'Type'=>'text',
    			'Name'=>'Title',
    	));
    	$f->AddElement(array(
    			'Type'=>'textarea',
    			'Name'=>'Description',
    	));
    	$f->AddElement(array(
    			'Type'=>'submit',
    			'Name'=>'Add',
    			'ٰValue'=>'اضافه',
    	));
    	$this->Form = $f;
    }
    
	
}
?>
