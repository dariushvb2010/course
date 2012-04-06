<?php
class ManagerConfirmMainController extends JControl
{
	function Start()
	{
		//j::Enforce("Reviewer");
		
		$Error=array();
		
		$Cotag=$_REQUEST['Cotag']*1;
		$this->Cotag=$Cotag;
		
		
		$file=ORM::Query("ReviewFile")->GetRecentFile($Cotag);
		if(!ORM::Query("ReviewProgressClasseconfirm")->ValidateFile($file)){
			$this->Redirect("./select");
		}
		
		$lastreview=$file->LastProgress('Review');
		$this->lastreview=$lastreview;
		
		$lastprogress=$file->LastProgress();
		if($lastprogress instanceof ReviewProgressClasseconfirm){
			$this->lastprogress=$lastprogress;
		}
			
		if (count($_POST))
		{	
					
			echo $Confirmation=$_REQUEST['Confirmation']*1;
			$Comment=$_REQUEST['Comment']*1;
			
			if($Confirmation==0){
				$this->Redirect("./select");
			}
			if($Confirmation==1){
				$ConfirmationResult=true;
			}
			if($Confirmation==2){
				$ConfirmationResult=false;
			}
			$this->Confirmation=$Confirmation;
			$this->Comment=$Comment;
			
			$Res=ORM::Query("ReviewProgressClasseconfirm")->AddToFile($Cotag,$ConfirmationResult,$Comment);
			if(is_string($Res))
				$Error[]=$Res;
			else if($Res)
			{
				$this->makeForm();
				$this->Valid=true;
				$this->Result="نظر مسئول بازبینی ثبت گردید.";	
			}
		}
		
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		$this->Cotag=$Cotag;
		$this->makeForm();
		return $this->Present();
	}
	function makeForm()
	{
		$f=new AutoformPlugin("post");
		$f->setCheckboxClass("KoshtiMaRo");
		$f->AddElement(array(
			"Name"=>"Confirmation",
			"Type"=>"radio",
			"Options"=>array("0"=>"بدون نظر",
							"1"=>"تایید برای صدور کلاسه",
							/*"2"=>"عدم تایید و ارجاع"*/),
			"Label"=>"تایید نتیجه بازبینی",
			"Default"=>"0",
		));
		
		$f->AddElement(array(
			"Type"=>"textarea",
			"Name"=>"Comment",
			"Label"=>"توضیحات",
			"Dependency"=>"Confirmation",
			"DependencyValue"=>"==2",
		));

		$f->AddElement(array(
			"Type"=>"submit",
			"Value"=>"ثبت نظر",
		));
		$this->Form=$f;
	}
}