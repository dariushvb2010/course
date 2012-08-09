<?php
class ReviewSelectController extends JControl
{
	function Start()
	{
		j::Enforce("Reviewer");
		if($_REQUEST['Class'])
		{
			$Class=$_POST['Classe']*1;
			$File=ORM::Query("ReviewFile")->GetRecentFileByClasse($Class);
			$Cotag=$File->Cotag();	
		}
		else if ($_REQUEST['Cotag'])
		{
			$Cotag=$_POST['Cotag']*1;
		}
		if($Cotag>0){
			$Res=ORM::Query("ReviewProgressReview")->IsAddable($Cotag);
			if(is_string($Res))
				$Error[]=$Res;
			else
				$this->Redirect("./?Cotag={$Cotag}");
		}
		$MyUnreviewedFiles=$this->Count=ORM::Query(new MyUser)->AssignedReviewableFile(j::UserID());
		$al=new AutolistPlugin($MyUnreviewedFiles,null,"Select");
		$al->SetMetadata(array('CreateTimestamp'=>array('CData'=>'?')));
		$al->SetHeader('Cotag', 'کوتاژ',true);
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
		elseif($k=='Cotag')
		{
			return "<a class='link_but' href='./?Cotag={$D->Cotag()}'>{$D->Cotag()}</a>";
		}
		else
		{
			return $v;
		}
	}
	
}