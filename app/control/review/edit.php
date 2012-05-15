<?php
class ReviewEditController extends JControl
{
	/**
	 * 
	 * information of the latest Review Progress of a File
	 * @param unknown_type $Cotag
	 * @return array(Result,ProvisionArray,DifferenceArray,Amount)
	 */
	private function getReviewInfo($Cotag)
	{
		$File=ORM::query(new ReviewFile)->GetRecentFile($Cotag);
		if($File==null)
			return false;
		$ProgReview=$File->LastReview();
		$Result=$ProgReview->Result();
		$Provision=$ProgReview->ProvisionArray();
		$Difference=$ProgReview->DifferenceArray();
		$Amount=$ProgReview->Amount('formatted');
		return array("Result"=>$Result,"Provision"=>$Provision,"Difference"=>$Difference,"Amount"=>$Amount);
	}
	function Start()
	{
		j::Enforce("Reviewer");
		
		$Error=array();
		$Cotag=$_REQUEST['Cotag']*1;
		
		if(count($_POST))
		{
			$dataArray=$_POST;
			$dataArray['Cotag']=$Cotag;
		
			$Res=ORM::Query("ReviewProgressReview")->NewReview($dataArray,'Edit');
		}
		//$this->makeForm();
		if(is_string($Res))
			$Error[]=$Res;
		else if($Res)
		{
			$this->Valid=true;
			$this->Result=$Res->Summary();//"ویرایش بازبینی با موفقیت ثبت گردید.";	
		}
		
		$info=$this->getReviewInfo($Cotag);		
		
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		$this->Cotag=$Cotag;
		$this->makeForm($info);
		return $this->Present(null,'review/main');
	}
	function makeForm($info)
	{
		$Result=$info["Result"]*1;
		$Amount=$info["Amount"];
		$f=new AutoformPlugin("post");
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
			"Default"=>"$Result",
		));
		
		//checkboxes 1
		$f->AddElement(array(
					"Name"=>"Provision",
					"Type"=>"radio",
					"Options"=>array("528"=>"528",
									"248"=>"248",
									"109"=>"109"),
					"Checks"=>$info["Provision"],
		         	"Label"=>"کلاسه",
					"Dependency"=>"Result",
					"DependencyValue"=>"==0",
		));
		//checkboxes 2
		$f->AddElement(array(
							"Name"=>"Difference",
							"Type"=>"checkbox",
							"Label"=>"علت تفاوت",
							"Options"=>array("Value"=>"ارزش",
												"Tariff"=>"تعرفه",
												"Other"=>"سایر"),
							"Checks"=>$info["Difference"],
							"Dependency"=>"Provision",
							"DependencyValue"=>">109",
			
		));
		
		$f->AddElement(array(
			"Type"=>"text",
			"Name"=>"Amount",
			"Value"=>"$Amount",
			"Class"=>"money",
			"Label"=>" مبلغ تفاوت به ریال",
			"Dependency"=>"Provision",
			"DependencyValue"=>">109",
		));

		$f->AddElement(array(
			"Type"=>"submit",
			"Value"=>"ویرایش نتیجه بازبینی",
		));
		$this->Form=$f;
	}
}