<?php
class ReviewSelectController extends JControl
{
	function CheckCotag($Error){
		if ($_REQUEST['Cotag'])
		{
			$Cotag=b::CotagFilter($_POST['Cotag']);
		}
		if($Cotag>0){
			$Res=ORM::Query("ReviewProgressReview")->IsAddable($Cotag);
			if(is_string($Res)){
				$Error[]=$Res;
				return $Error;
			}else{
				$this->Redirect("./?Cotag={$Cotag}");
			}
		}
	}
	function Start()
	{
		//j::Enforce("Reviewer");
		$this->CheckCotag($Error);
		if(isset($_REQUEST['ID'])){
			$CurrentUser=MyUser::getUser($_REQUEST['ID']);
			$this->PageType="OtherView";
			$this->Title="مشاهده کوتاژ های باکس کارشناس";
		}else{
			$CurrentUser=MyUser::CurrentUser();
			$this->PageType="ReviewerView";
			$this->Title="انتخاب اظهارنامه جهت کارشناسی";
		}
		
		if($CurrentUser){
			$MyUnreviewedFiles=$CurrentUser->AssignedReviewableFile();
			$this->FileAutoList=$this->CreateReviewList($MyUnreviewedFiles);
			
			$this->Count=count($MyUnreviewedFiles);
			$this->MyUnreviewedFiles=$MyUnreviewedFiles;
		}else{
			$Error[]="کاربر موجود نیست.";
		}
		
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		return $this->Present();
	}
	
	
	function CreateReviewList($FileArray){
		$al=new AutolistPlugin($FileArray,null,"Select");
		$al->SetMetadata(array('CreateTimestamp'=>array('CData'=>'?')));
		$al->SetHeader('Cotag', 'کوتاژ',true);
		$al->HasTier=true;
		$al->TierLabel="ردیف";
		$al->ObjectAccess=true;
		$al->SetHeader('GateCode', 'کد گمرک',true);
		$al->SetHeader('assignCreateTimestamp', 'زمان تخصیص',true);
		$al->SetHeader('CreateTimestamp', 'زمان وصول',true);
		$al->SetFilter(array($this,"myfilter"));
		return $al;
	}
	
	function myfilter($k,$v,$D)
	{
		if($k=='CreateTimestamp')
		{
			$c=new CalendarPlugin();
			return "<span dir='ltr'>{$D->CreateTime()}</span>";
		}
		elseif($k=='assignCreateTimestamp')
		{
			$c=new CalendarPlugin();
			/*if($D->LLP('Assign'))
				return "<span dir='ltr'>{$c->JalaliFullTime($D->LLP('Assign')->CreateTimestamp())}</span>";
			else*/ 	
				return '-';
		}
		elseif($k=='Cotag')
		{
			if($this->PageType=="ReviewerView"){
			return "<a class='link_but' href='./?Cotag={$D->Gatecode()}-{$D->Cotag()}'>{$D->Cotag()}</a>";
			}else{
				return $D->Cotag();
			}
		}
		elseif($k=='GateCode')
		{
			return $D->GateCode();
		}
		else
		{
			return $v;
		}
	}
	
}