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
	    		$File=b::GetFile($Cotag);
	    	
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
	    	$File=b::GetFile($Cotag);
	    	if ($File==null)
	    	{
	    		$Error="این کوتاژ وصول نشده است.";
	    		return $Error;
	    	}else{
		    	$MyTemplate=Template::TemplateByFile($File);
		    	if (!$MyTemplate)
		    		$Error[]="قالب وجود ندارد.";
		    	else
		    		$f=$this->makeform($MyTemplate,$File);
	    	}
	    }
	    if(count($Error))$this->Result=false;
	    $this->Template=$MyTemplate;
	    $this->File=$File;
	    $this->Error=$Error;
	    $this->Result=$Result;
    	$this->Autoform=$f;
    	return $this->Present();
    }
    
    function makeform($Template=null,$File=NULL){
		$f=new AutoformPlugin("post");
		$f->AddElement(
				array(
						"Type"=>"hidden",
						"Name"=>"ID",
						"Value"=>$File->ID(),
				));
		if(Count($Template)){
			$Fields=$Template->GetUnknownFields();
			foreach ($Fields as $v)
			{
				$f->AddElement(
					array(
							"Type"=>"Text",
							"Label"=>$v,
							"Name"=>"UF_".$v,
					));
			}
		}
		$f->AddElement(
				array(
						"Type"=>"submit",
						"Name"=>"submit",
						"Value"=>"چاپ",
				));
		return $f;
	}
}
?>
