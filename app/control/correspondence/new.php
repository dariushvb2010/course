<?php
class CorrespondenceNewController extends JControl

{
	function Start()
	{
		j::Enforce("Correspondence");
		
		$Error=array();
		if(isset($_GET['Cotag'])){
			$Cotag=$_GET['Cotag']*1;
		
			if(count($_POST)){
				$Cotag=$_GET['Cotag']*1;
				
				$Res=ORM::Query(new ReviewProgressManualcorrespondence())->AddToFile($Cotag,$_POST['Number'],$_POST['SubjectID'],$_POST['Destination'],$_POST['Comment']);
				if(is_string($Res))
				{
					$Error[]=$Res;
				}
				else
				$this->Result="مکاتبه مورد نظر ثبت گردید";
			}
		}else{
			$this->Redirect("select?Cotag={$Cotag}");
		}
		
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		$this->Cotag=$Cotag;
		$this->makeForm();
		return $this->Present();
	}
	function makeForm()
	{
		$topics=ReviewCorrespondenceTopic::Topics();
		foreach($topics as $v)
		{
			$ts[$v['ID']]=$v['Topic'];
		}
		$f=new AutoformPlugin("post");
		$f->AddElement(array(
			"Type"=>"text",
			"Name"=>"Cotag",
			"Label"=>"کوتاژ",
			"Value"=>$this->Cotag,
			"Disabled"=>true,
		));
		$f->AddElement(array(
					"Name"=>"SubjectID",
					"Type"=>"select",
					"Options"=>$ts,
					"Label"=>"عنوان",
					"Default"=>"0",
					"width"=>"150px"
		));
		
		
		$f->AddElement(array(
			"Type"=>"text",
			"Name"=>"Number",
			"Label"=>"شماره نامه",
		));
		$f->AddElement(array(
			"Type"=>"text",
			"Name"=>"Destination",
			"Label"=>"طرف مکاتبه",
		));
		$f->AddElement(array(
			"Type"=>"textarea",
			"Name"=>"Comment",
			"Label"=>"شرح مختصر",
		));
		$f->AddElement(array(
			"Type"=>"submit",
			"Name"=>"submit",
			"Value"=>"ثبت مکاتبه",
		));
		$this->Form=$f;
	}
}