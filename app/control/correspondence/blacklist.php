<?php
class CorrespondenceBlacklistController extends JControl
{
	function Start()
	{
		j::Enforce("Reviewer");
		
		if ($_REQUEST['Cotag'])
		{
			$Cotag=b::CotagFilter($_POST['Cotag']);
			$this->Redirect("./?Cotag={$Cotag}");	 						
			
		}
		
		$MyUnreviewedFiles=$this->Count=ORM::Query(new MyUser)->AssignedReviewableDossier(j::UserID());
		$al=new AutolistPlugin($MyUnreviewedFiles,null,"Select");
		$al->SetMetadata(array('CreateTimestamp'=>array('CData'=>'?')));
		$al->SetHeader('Select', 'انتخاب',true);
		$al->SetHeader('Digital', 'مشاهده پرونده',true);
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
			if($D->LastProgress('Assign'))
				return "<span dir='ltr'>{$c->JalaliFullTime($D->LastProgress('Assign')->CreateTimestamp())}</span>";
			else 	
				return '-';
		}
		elseif($k=='Select')
		{
			return v::link('کارشناسی',"./?Cotag={$D->Cotag()}");	
		}
		elseif($k=='Digital')
		{
			return v::link('مشاهده پرونده دیجیتال',"./digital?Cotag={$D->Cotag()}");
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