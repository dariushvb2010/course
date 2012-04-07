<?php
class ReviewMainController extends JControl
{
	function Start()
	{
		j::Enforce("Reviewer");
		
		$Error=array();
		$Cotag=$_REQUEST['Cotag']*1;
	
		$Res=ORM::Query("ReviewProgressReview")->AddReview($Cotag,$_POST);
		$this->makeForm();
		if(is_string($Res))
			$Error[]=$Res;
		else if($Res)
		{
			$this->Valid=true;
			$this->Result="نتیجه بازبینی با موفقیت ثبت گردید.";	
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
			"Type"=>"text",
			"Name"=>"Cotag",
			"Disabled"=>true,
			"Label"=>"کوتاژ",
			"Value"=>$this->Cotag,
		));
		$f->AddElement(array(
			"Name"=>"Result",
			"Type"=>"radio",
			"Options"=>array("0"=>"مشکل‌دار",
							"1"=>"بدون مشکل"),
			"Label"=>"نتیجه بازبینی",
			"Default"=>"1",
		));
		
		//checkboxes 1
		$f->AddElement(array(
					"Name"=>"Provision",
					"Type"=>"radio",
					"Options"=>array("528"=>"528",
									"248"=>"248",
									"109"=>"109"),
		         	"Label"=>"کلاسه",
					"Dependency"=>"Result",
					"DependencyValue"=>"==0",
					"Class"=>"KoshtiMaRo",
		));
		//checkboxes 2
		$f->AddElement(array(
							"Name"=>"Difference",
							"Type"=>"checkbox",
							"Options"=>array("Value"=>"ارزش",
												"Tariff"=>"تعرفه",
												"Other"=>"سایر"),
							"Dependency"=>"Provision",
							"DependencyValue"=>">109",
							"Class"=>"KoshtiMaRo",
							"Label"=>"علت تفاوت",
		));
		
		$f->AddElement(array(
			"Type"=>"text",
			"Name"=>"Amount",
			"Class"=>"money",
			"Label"=>" مبلغ تفاوت به ریال",
			"Dependency"=>"Provision",
			"DependencyValue"=>">109",
		));

		$f->AddElement(array(
			"Type"=>"submit",
			"Value"=>"اعلام نتیجه بازبینی",
		));
		$this->Form=$f;
	}
}