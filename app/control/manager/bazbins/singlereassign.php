<?php
class ManagerBazbinsSinglereassignController extends JControl
{
	function Start()
	{
		j::Enforce("MasterHand");
		$this->MakeReassignList();
		$this->MakeCancelList();
		
		if(isset($_POST['CancelAssign']))
		{
			$res=$this->CancelList->GetRequest();
			foreach ($res as $Data)
			{
				$this->CancelAssign($Data, $CancelError);
			}
		}
		else if(isset($_POST['Reassign']))
		{
			$res=$this->ReassignList->GetRequest();
			foreach ($res as $Data)
			{
				$this->Reassign($Data, $ReassignError);
			}
		}
		
		
		$this->Cotag=$Cotag;
		
		//---REASSIGN PART: List of enable reviewers to be shown in drop box in the form
		$reviewers = ORM::Query("MyUser")->Reviewers(true);
		foreach($reviewers as $r){
			$x[$r->ID]=$r->getFullName();
		}
		$this->ListOfBazbins=$x;
		//----------------------------------------------------------------------------
		
		$this->ReassignError=$ReassignError;
		$this->CancelError=$CancelError;
		$this->MakeCancelForm();
		$this->MakeReassignForm();
		return $this->Present();
	}
	function MakeCancelList()
	{
		///----inner form-----
		$f=new AutoformPlugin();
		$f->HasFormTag=false;
		$f->AddElement(array(
						"Type"=>"submit",
						"Value"=>"لغو تخصیص ",
						"Name"=>"CancelAssign"
		));
		$f->Style="border:none;";
		//-------list----------
		$al=new DynamiclistPlugin();
		$al->HasTier=true;
		$al->TierLabel="ردیف";
		$al->RemoveLabel="حذف";
		$al->HasFormTag=true;
		$al->_List=".autoform[num=first] .autolist tbody";
		$al->_Button="div.autoform[num=first] button";
		$al->EnterTextName="Cotag";
		//--------al custom javascript code for validating the format of the Cotag-------------
		$CotagCode="var patt=/^\d{".b::$CotagLength."}$/;";
		$CotagCode.="if(patt.test(?)); else {alert('فرمت کوتاژ رعایت نشده است.'); return false;}";
		//----------A C J C------------------------------
		$CotagMetaData=array("Unique"=>true,"Clear"=>true, "CustomValidation"=>$CotagCode);
		$CommentCode="var patt=/.{".b::$CommentMinLength."}/;";//patt=/.{6}/
		$CommentCode.="if(patt.test(?)); else {alert('توضیحات کامل بنویسید.'); return false;}";
		$al->SetHeader("Cotag", "کوتاژ", "div.autoform[num=first] :text[name=Cotag]","Text",$CotagMetaData);
		$al->SetHeader("Comment", "توضیحات", "div.autoform[num=first] textarea[name=Comment]","Textarea",array("CustomValidation"=>$CommentCode));
		$al->Autoform=$f;
		$al->AutoformAfter=true;
		$this->CancelList=$al;
	}
	function MakeReassignList()
	{
		///----inner form-----
		$f=new AutoformPlugin();
		$f->HasFormTag=false;
		$f->AddElement(array(
			"Type"=>"submit",
			"Value"=>"تخصیص مجدد",
			"Name"=>"Reassign"
		));
		$f->Style="border:none;";
		//-------list----------
		$al=new DynamiclistPlugin();
		$al->HasTier=true;
		$al->TierLabel="ردیف";
		$al->RemoveLabel="حذف";
		$al->HasFormTag=true;
		$al->_List=".autoform[num=last] .autolist tbody";
		$al->_Button="div.autoform[num=last] button";
		$al->EnterTextName="Cotag";
		//--------al custom javascript code for validating the format of the Cotag-------------
		$CotagCode="var patt=/^\d{".b::$CotagLength."}$/;";
		$CotagCode.="if(patt.test(?)); else {alert('فرمت کوتاژ رعایت نشده است.'); return false;}";
		//----------A C J C------------------------------
		$CotagMetaData=array("Unique"=>true,"Clear"=>true, "CustomValidation"=>$CotagCode);
		$CommentCode="var patt=/.{".b::$CommentMinLength."}/;";//patt=/.{6}/
		$CommentCode.="if(patt.test(?)); else {alert('توضیحات کامل بنویسید.'); return false;}";
		$al->SetHeader("Cotag", "کوتاژ", "div.autoform[num=last] :text[name=Cotag]","Text",$CotagMetaData);
		$al->SetHeader("ID", "کارشناس", "div.autoform[num=last] select[name=ID] option:selected","Select");
		$al->SetHeader("Comment", "توضیحات", "div.autoform[num=last] textarea[name=Comment]","Textarea",array("CustomValidation"=>$CommentCode));
		$al->Autoform=$f;
		$al->AutoformAfter=true;
		$this->ReassignList=$al;
	}
	/**
	*
	* @param $Data a 1D array of Data
	*/
	private function CancelAssign($Data, &$Error)
	{
		$Cotag=$Data['Cotag']*1;
		$Comment=$Data['Comment'];
		$File=ORM::Query("ReviewFile")->GetRecentFile($Cotag);
		$this->Comment=$Comment;
		$str="کوتاژ:".$Cotag." ";
		//-------too short comment
		if(strlen($Comment)<6){
			$Error[]='متن توضیحات بسیار کوتاه است.'."<br/>".$str;
		}
		//--------REASSIGN
		else
		{
			if(!$File->LLP() instanceof ReviewProgressAssign)
			{
				$Error[]="امکان لغو تخصیص نیست. ".$str;
				return;
			}
			$CancelResult=ORM::Query("ReviewProgressRemove")->AddToFile($File,$Comment);
			if(is_string($CancelResult))
			{
				$Error[]=$CancelResult."<br/>".$str;
			}
			else
			{
				$this->CancelResult.=" لغو تخصیص ".$str."<br/>";
			}
		}
	}
	/**
	 * 
	 * @param $Data a 1D array of Data
	 */
	private function Reassign($Data, &$Error)
	{
		$Cotag=$Data['Cotag']*1;
		$RID=$Data['ID'];
		$Comment=$Data['Comment'];
		$File=ORM::Query("ReviewFile")->GetRecentFile($Cotag);
		$Reviewer=ORM::Find("MyUser",$RID);
		$this->Comment=$Comment;
			
		$str="کوتاژ:".$Cotag." ";
		$str.="کارشناس:".($Reviewer ? $Reviewer->getFullName() : "یافت نشد")." ";
		//-------too short comment
		if(strlen($Comment)<6){
			$Error[]='متن توضیحات بسیار کوتاه است.'."<br/>".$str;
		}
		//--------REASSIGN
		else
		{
			$AssignResult=ORM::Query("ReviewProgressAssign")->AddToFile($File,$Reviewer,$Comment);
			if(is_string($AssignResult))
			{
				$Error[]=$AssignResult."<br/>".$str;
			}
			else
			{
				$this->ReassignResult.="تخصیص مجدد ".$str."<br/>";
			}
		}
	}
	function MakeCancelForm()
	{
		$MyComment=ORM::Find(new ReviewTopic,"Type","comment");
		foreach ($MyComment as $c)
		{
			$com[]=$c->Topic();
		}
		$f=new AutoformPlugin("post");
		//------place the list into the form-----
		$f->HasFormTag=false;
		$f->List=$this->CancelList;
		//------P T L I T F----------
		$f->CustomAttribs['num']='first';// for javascript distinguish between two main forms in the page
		$f->AddElement(array(
				"Type"=>"text",
				"Name"=>"Cotag",
				"Label"=>"کوتاژ",
				"Value"=>$this->Cotag,
		));
		$f->AddElement(array(
				"Name"=>"CommentBox",
				"ID"=>"CommentBox",
				"Width"=>"60%",
				"Type"=>"select",
				"Label"=>"توضیحات",
				"Options"=>$com,
		));
		$f->AddElement(array(
				"Name"=>"Comment",
				"ID"=>"Comment",
				"Type"=>"textarea",
				"Value"=>$this->Comment,
				"Label"=>"توضیحات",
		));
		$f->AddElement(array(
				"Type"=>"button"
		));
		$this->CancelForm=$f;
	}
	function MakeReassignForm()
	{
		$MyComment=ORM::Find(new ReviewTopic,"Type","comment");
		foreach ($MyComment as $c)
		{
			$com[]=$c->Topic();
		}
		$f=new AutoformPlugin("post");
		//------place the list into the form-----
		$f->HasFormTag=false;
		$f->List=$this->ReassignList;
		//------P T L I T F----------
		$f->CustomAttribs['num']='last';// for javascript distinguish between two main forms in the page
		$f->AddElement(array(
			"Type"=>"text",
			"Name"=>"Cotag",
			"Label"=>"کوتاژ",
			"Value"=>$this->Cotag,
		));
		$f->AddElement(array(
			"Name"=>"ID",
			"Type"=>"select",
			"Options"=>$this->ListOfBazbins,
			"Label"=>"کارشناس بازبینی",
		));
		$f->AddElement(array(
			"Name"=>"CommentBox",
			"ID"=>"CommentBox",
			"Width"=>"60%",
			"Type"=>"select",
			"Label"=>"توضیحات",
			"Options"=>$com,
		));
		$f->AddElement(array(
			"Name"=>"Comment",
			"ID"=>"Comment",
			"Type"=>"textarea",
			"Value"=>$this->Comment,
			"Label"=>"توضیحات",
		));
		$f->AddElement(array(
			"Type"=>"button"
		));
		$this->ReassignForm=$f;
	}
}