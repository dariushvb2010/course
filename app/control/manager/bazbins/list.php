<?php
class ManagerBazbinsListController extends JControl
{
	function Start()
	{
		j::Enforce("MasterHand");
// 		$this->EnableReviewersCount=ORM::Query(new MyUser())->ReviewersCount(true);
// 		$this->DisableReviewersCount=ORM::Query(new MyUser())->ReviewersCount(false);
		// if checkboxes selected
		if(isset($_POST['Work']))
		{
			$this->SetReviewersState("Work");
		}
		else if(isset($_POST['Vacation']))
		{
			$this->SetReviewersState("Vacation");
		}
		
		//if orange or green state clicked
		if($_GET['id']){ 
			$my_reviewer=ORM::Query(new MyUser)->find($_GET['id']);
			if(isset($_GET['State'])){
				$my_reviewer->SetState($_GET['State']*1);
				ORM::Persist($my_reviewer);
			}
		}
		
		if(MyUser::CurrentUser()->MainSetting())
		{
			if(MyUser::CurrentUser()->MainSetting()->ShowRetireds())
				$reviewers = ORM::Query(new MyUser())->Reviewers();
			else 
				$reviewers = ORM::Query(new MyUser())->Reviewers(0,1);
		}
		else
			$reviewers = ORM::Query(new MyUser())->Reviewers();
		
		$this->SetReviewersCount();
		
		if(count($reviewers))
		{
			$this->Reviewers = $reviewers;
			$al=new AutolistPlugin($this->Reviewers,null,"Select");
			//$al->SetMetadata(array('CreateTimestamp'=>array('CData'=>'?')));
			$al->ObjectAccess=true;
			$al->Width="auto";
			$al->HasTier=true;
			$al->TierLabel="ردیف";
			$al->SetHeader("Select", "انتخاب",true);
			$al->SetHeader('View', 'مشاهده',true);
			
			$al->SetHeader("State","وضعیت",true);
			$al->SetHeader('getFullName', 'نام',true);
			$al->SetHeader('Count', 'تعداد اظهارنامه ',true);
			$al->SetFilter(array($this,"myfilter"));
			$this->ReviewersList=$al;
		}
		$this->UserList=$this->MakeUserList();
		
		//--------------Disable Form -------------------
		if(isset($_POST['DisableID']))
		{
			$Reviewer=ORM::Find("MyUser",$_POST['DisableID']);
			if($Reviewer)
			{
				$this->Result="کاربری ".$Reviewer->getFullName();
				if(isset($_POST['Enable']))
				{
					$Reviewer->Enable();
					$this->Result.= " فعال شد.";
				}
				else if(isset($_POST['Disable']))
				{
					$Reviewer->Disable();
					$this->Result.= " غیرفعال شد.";
				}
				ORM::Persist($Reviewer);
			}
		}
		$this->DisableForm=$this->MakeDisableForm();
		//-----------------------D F----------------------
		
		$this->Error=$Error;
		if (count($Error))
			$this->Result=false;
		return $this->Present();
	}
	
	function myfilter($k,$v,$D)
	{
		if($k=='View')
		{
			return "<a href='./bazbin?id=".$D->ID."' name='item[]' /><img src='/img/search-32.png' style='width:16px;' title='مشاهده' /></a>";
		}
		else if($k=="Count")
		{
			$c=ORM::Query(new MyUser)->AssignedReviewableFileCount($D);
			if(!$c)
				$c=0;
			return $c;
		}
		else if($k=="State")
		{
			if($v=="Work")
				return "<a href='list?id=".$D->ID."&State=0"."'><img src='/img/light-green-32.png' style='height:16px;' title='در حال کار'/></a>";
			else if($v=="Vacation")
				return "<a href='list?id=".$D->ID."&State=1"."'><img src='/img/light-orange-32.png' style='height:16px;' title='مرخصی'/></a>";
			else if($v=="Retired")
				return "<a href='list?id=".$D->ID."'><img src='/img/light-gray-32.png' style='height:16px;' title='غیرفعال'/></a>";
		}
		else if($k=="Select")
		{
			return "<input type='checkbox' name='Reviewers[]' value='".$D->ID."' class='item' />";
		}
		else
		{
			return $v;
		}
	}
	private function SetReviewersState($State)
	{
		
		$ReviewersID=$_POST['Reviewers'];
		if(count($ReviewersID))
		foreach ($ReviewersID as $rID)
		{
			$rID=$rID*1;
			$my_reviewer=ORM::Query(new MyUser)->find($rID);
			if($my_reviewer)
			{
				$my_reviewer->SetState($State);
				ORM::Persist($my_reviewer);
				ORM::Flush();
			}
		}
	}
	private function MakeUserList()
	{
		$Users=ORM::Query(new MyUser)->GetNotReviewers();
		$al=new AutolistPlugin($Users);
		$al->ObjectAccess=true;
		$al->Width="auto";
		$al->HasTier=true;
		$al->TierLabel="ردیف";
		$al->SetHeader('Firstname', 'نام',true);
		$al->SetHeader('Lastname', 'نام خانوادگی',true);
		$al->SetHeader("GroupRTitle", "بخش");
		return $al;
	}
	
	/**
	 * 
	 * Disable Form
	 */
	private function MakeDisableForm()
	{
		$reviewers = ORM::Query(new MyUser())->Reviewers();
		//ORM::Dump($reviewers);
		foreach($reviewers as $r){
			$x[$r->ID]=$r->getFullName();
		}
		$reviewers=$x;
		$f=new AutoformPlugin("post",SiteRoot."/manager/bazbins/list");
		$f->AddElement(array(
					"Name"=>"DisableID",
					"Type"=>"select",
					"Options"=>$reviewers,
					"Label"=>"کارشناس",
		));
		$f->AddElement(array(
			"Name"=>"Enable",
			"Type"=>"submit",
			"Value"=>"فعال کردن",
		));
		$f->AddElement(array(
			"Name"=>"Disable",
			"Type"=>"submit",
			"Value"=>"غیرفعال کردن",
		));
		
		return $f;
	}
	private function SetReviewersCount()
	{
		ORM::Flush();
		$this->WorkCount=ORM::Query(new MyUser)->ReviewersCount(1);
		$this->VacationCount=ORM::Query(new MyUser)->ReviewersCount(0);
		$this->RetiredCount=ORM::Query(new MyUser)->ReviewersCount(2);
	}
}
