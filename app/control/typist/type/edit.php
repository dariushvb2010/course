<?php
class TypistTypeEditController extends BaseControllerClass
{
    function Start ()
    {
	    if (count($_POST))
	    {
	    	if(isset($_POST['ID']))
	    	{
	    		$Cotag=$_GET['Cotag'];
	    		$File=ORM::Query(new ReviewFile)->GetRecentFile($Cotag);
	    	
	    		$MyTemplate=Template::TemplateByFile($File);
    			if ($MyTemplate){
    				$MyTemplate->SetUParray($_POST);
    				$Document=$MyTemplate->GetFinalDocument();
    				$MyTemplate->GetFinalDocumentPDF();    				
	    			die();
    				$Result="با موفقیت چاپ شد.";
    			}else
    				$Error[]="خطا";
    		}else 
    			$Error[]="خطا";
	    }else{
	    	$Cotag=$_GET['Cotag'];
	    	$File=ORM::Query(new ReviewFile)->GetRecentFile($Cotag);
	    	if ($File==null)
	    	{
	    		$Error="این کوتاژ وصول نشده است.";
	    		return $Error;
	    	}else{
		    	$MyTemplate=Template::TemplateByFile($File);
		    	if (!$MyTemplate)
		    		$Error[]="قالب وجود ندارد.";
	    	}
	    }
	    if(count($Error))$this->Result=false;
	    $this->Template=$MyTemplate;
	    $this->File=$File;
	    $this->Error=$Error;
	    $this->Result=$Result;
    	return $this->Present();
    }
}
?>
