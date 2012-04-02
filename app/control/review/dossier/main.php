<?php
class ReviewDossierMainController extends JControl
{
	function Start()
	{
		j::Enforce("Reviewer");
		
		$Error=array();
		$Cotag=$_REQUEST['Cotag']*1;
		$File=ORM::Query(new ReviewFile())->GetRecentFile($Cotag);
		if($File)
			$State=$File->State();
		if(FileFsm::IsReviewerDisturbState($State))
		{
			$Option=array(	
							"ok"=>"قبول اعتراض صاحب کالا ",
							"nok"=>"رد اعتراض",
							"setad"=>"مکاتبه با گمرک ایران",
							"commission"=>"ارجاع پرونده به کمیسون",
							);
			if(isset($_REQUEST['Result']))
			{
				$JudgeResult=$_REQUEST['Result'];
				$Setad=($_REQUEST['Setad'])?($_REQUEST['Setad']):"";
				if($Setad=="other")
					$Setad=($_REQUEST['other'])?($_REQUEST['other']):"";
				$Res=ORM::Query(new ReviewProcessJudgement)->AddToFile($File,$JudgeResult,$Setad);
			}
		}
		elseif(FileFsm::IsReviewerConfirmState($State))
		{
			$Option=array(
							"ok"=>"ختم پرونده",
			);
			
			if(isset($_REQUEST['Result']))
			{
				$JudgeResult=$_REQUEST['Result'];
				$Setad=($_REQUEST['Setad'])?($_REQUEST['Setad']):"";
				if($Setad=="other")
					$Setad=($_REQUEST['other'])?($_REQUEST['other']):"";
				$Res=ORM::Query(new ReviewProcessJudgement)->AddToFile($File,$JudgeResult,$Setad);
			}
		}
		$this->Option=$Option;
		
		$this->makeForm();
		if(is_string($Res))
			$Error[]=$Res;
		else if($Res)
		{
			$this->Valid=true;
			$this->Result="نتیجه کارشناسی با موفقیت ثبت گردید.";	
		}
		
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		$this->Cotag=$Cotag;
		$this->makeForm();
		return $this->Present();
	}
	function makeForm()
	{
		$topics=ReviewTopic::Topics("correspondent");
		foreach($topics as $v)
		{
			$ts[$v['ID']]=$v['Topic'];
			
		}
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
			"Options"=>$this->Option,
			"Label"=>"نتیجه کارشناسی",
			"Default"=>"nok",
		));
		$f->AddElement(array(
			"Name"=>"Setad",
			"Type"=>"radio",
			"Options"=>array("tarif"=>"مکاتبه با دفتر تعیین تعرفه",
							"arzesh"=>"مکاتبه با دفتر تعیین ارزش",
							"varedat"=>"مکاتبه با دفتر واردات",
							"other"=>"مکاتبه با سایر "),
			"Default"=>"1",
			"Dependency"=>"Result",
			"DependencyValue"=>"=='setad'",
		));
		$f->AddElement(array(
					"Name"=>"other",
					"Type"=>"select",
					"Options"=>$ts,
		         	"Label"=>"سایر:",
					"Dependency"=>"Setad",
					"DependencyValue"=>"=='other'",
					"Class"=>"KoshtiMaRo",
		));
		
		$f->AddElement(array(
			"Type"=>"submit",
			"Value"=>"اعلام نتیجه کارشناسی",
		));
		$this->Form=$f;
	}
}