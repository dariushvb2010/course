<?php
class ReviewSelectController extends JControl
{
	function Start()
	{
		j::Enforce("Reviewer");
		if($_REQUEST['Class'])
		{
			
			$Class=$_POST['Classe']*1;
			$File=ORM::Query(new ReviewFile())->GetRecentFileByClasse($Class);
			$Cotag=$File->Cotag();
			$Res=ORM::Query(new ReviewProgressReview())->AddReview($Cotag);
			if(is_string($Res))
				$Error[]=$Res;
			else		
				$this->Redirect("./?Cotag={$Cotag}");	 						
		}
		else if ($_REQUEST['Cotag'])
		{
			$Cotag=$_POST['Cotag']*1;
			$Res=ORM::Query(new ReviewProgressReview())->AddReview($Cotag);
			if(is_string($Res))
				$Error[]=$Res;
			else		
				$this->Redirect("./?Cotag={$Cotag}");	 						
			
		}
		$MyUnreviewedFiles=$this->Count=ORM::Query(new MyUser)->AssignedReviewableFile(j::UserID());
		$al=new AutolistPlugin($MyUnreviewedFiles,null,"Select");
		$al->SetMetadata(array('CreateTimestamp'=>array('CData'=>'?')));
		$al->SetHeader('Select', 'انتخاب',true);
		$al->SetHeader('Cotag', 'کوتاژ');
		$al->HasTier=true;
		$al->TierLabel="ردیف";
		$al->ObjectAccess=true;
		$al->SetHeader('assignCreateTimestamp', 'زمان تخصیص',true);
		$al->SetHeader('CreateTimestamp', 'زمان وصول',true);
		$al->SetFilter(array($this,"myfilter"));
		
		$this->FileAutoList=$al;
		
		$this->Count=count($MyUnreviewedFiles);
		$this->MyUnreviewedFiles=$MyUnreviewedFiles;
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		return $this->Present();
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
		elseif($k=='Select')
		{
				return "<a href='./?Cotag={$D->Cotag()}'>بازبینی</a>";	
		}
		elseif($k=='Cotag')
		{
			return $D->Cotag();
		}
		else
		{
			return $v;
		}
	}
	
}