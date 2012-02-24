<?php
class ManagerConfirmSelectController extends JControl
{
	function Start()
	{
		//j::Enforce("Reviewer");
		
		if ($_REQUEST['Cotag'])
		{
			$Cotag=$_POST['Cotag']*1;
			$Res=ORM::Query(new ReviewProgressReview())->AddReview($Cotag);
			if(is_string($Res))
				$Error[]=$Res;
			else		
				$this->Redirect("./?Cotag={$Cotag}");	 						
			
		}
		
		$MyUnreviewedFiles=ORM::Query(new ReviewFile)->WaitingForConfirmFilesInRange();
		$al=new AutolistPlugin($MyUnreviewedFiles,null,"Select");
		$al->SetMetadata(array('CreateTimestamp'=>array('CData'=>'?')));
		$al->SetHeader('Select', 'انتخاب',true);
		$al->SetHeader('Cotag', 'کوتاژ');
		$al->HasTier=true;
		$al->TierLabel="ردیف";
		$al->ObjectAccess=true;
		$al->SetHeader('assignCreateTimestamp', 'زمان تخصیص',true);
		$al->SetHeader('reviewCreateTimestamp', 'زمان آخرین کارشناسی',true);
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
			$dd=$D->LastProgress('Assign');
			if($dd)
				return "<span dir='ltr'>{$c->JalaliFullTime($dd->CreateTimestamp())}</span>";
			else 	
				return '-';
		}
		elseif($k=='reviewCreateTimestamp')
		{
			$c=new CalendarPlugin();
			$dd=$D->LastProgress('Review');
			if($dd)
				return "<span dir='ltr'>{$c->JalaliFullTime($dd->CreateTimestamp())}</span>";
			else 	
				return '-';
		}
		elseif($k=='Select')
		{
				if($D->LastProgress() instanceof ReviewProgressClasseconfirm){
					return "<a href='./?Cotag={$D->Cotag()}'>تغییر نظر</a>";
				}else{
					return "<a href='./?Cotag={$D->Cotag()}'>اعمال نظر</a>";
				}	
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