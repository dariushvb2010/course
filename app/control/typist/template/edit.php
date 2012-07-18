<?php
class TypistTemplateEditController extends BaseControllerClass
{
    function Start ()
    {
	    if (count($_POST))
	    {
	    	$MyTemplate=ORM::Find("Template", $_POST['ID']);
    		if ($MyTemplate){
	    		$MyTemplate->SetHtml($_POST['Html']);
    			$Result="با موفقیت ثبت شد.";
    		}else 
    			$Error[]="خطا";
	    }else{
	    	$MyTemplate=ORM::Find("Template", $_GET['ID']);
	    	if (!$MyTemplate)
	    		$Error[]="قالب وجود ندارد.";
	    }
	    if(count($Error))$this->Result=false;
	    $this->Template=$MyTemplate;
	    $this->AllFields=Template::GetFields();
	    $this->Error=$Error;
	    $this->Result=$Result;
    	return $this->Present();
    }
}
?>
