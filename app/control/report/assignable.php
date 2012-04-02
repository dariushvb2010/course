<?php
class ReportAssignableController extends JControl
{
	function Start()
	{
		j::Enforce("AssignableList");
		
		if(!isset($_GET['lim']))
			$_GET['lim']=100;
		$UnassignedFiles=ORM::Query(new ReviewFile)->UnassignedFiles($_GET['off'],$_GET['lim']);
		if(count($UnassignedFiles))
		{
			$this->UnassignedFiles=$UnassignedFiles;
		}
		else
		{
			$Error[]="اظهارنامه قابل تخصیصی وجود ندارد.";
		}
			
		$al=new AutolistPlugin($this->UnassignedFiles,null,"Select");
		$al->SetMetadata(array('CreateTimestamp'=>array('CData'=>'?')));
		$al->HasTier=true;
		$al->TierLabel="ردیف";
		$al->SetHeader('Cotag', 'کوتاژ');
		$al->SetHeader('CreateTimestamp', 'زمان وصول',true);
		$al->SetFilter(array($this,"myfilter"));
		$this->AssignList=$al;
		
		$this->Error=$Error;
		if (count($Error))
			$this->Result=false;
		return $this->Present();
	}
	function myfilter($k,$v,$D)
	{
		if($k=='CreateTimestamp')
		{
			$c=new CalendarPlugin();
			return "<span dir='ltr'>".$c->JalaliFullTime($v)."</span>";
		}
		else
		{
			return $v;
		}
	}

}