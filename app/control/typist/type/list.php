<?php
class TypistTypeListController extends JControl
{
	function Start()
	{
		$AllFiles= ORM::Query(new ReviewFile())->FilesByStateName('PrintForDemand');
		$al=new AutolistPlugin($AllFiles,null,"Select");
		$al->SetMetadata(array('CreateTimestamp'=>array('CData'=>'?')));
		$al->HasTier=true;
		$al->TierLabel="ردیف";
		$al->ObjectAccess=true;
		$al->SetHeader('CreateTimestamp', 'تاریخ ایجاد',true);
		$al->SetHeader('Cotag', 'کوتاژ',true);
		$al->SetHeader('PrintButton', 'ایجاد نامه',true);
		$al->SetFilter(array($this,"myfilter"));
		$this->FileAutoList=$al;
		
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		return $this->Present();
	}
	
	function myfilter($k,$v,$D)
	{
		if($k=='CreateTimestamp')
		{
			$c=new CalendarPlugin();
			$p=$c->JalaliFullTime($D->LastProgress()->CreateTimestamp());
			return "<span dir='ltr'>{$p}</span>";
		}
		elseif($k=='PrintButton')
		{
			return "<a class='link_but' href='./edit?Cotag={$D->Cotag()}'>چاپ</a>";
		}
		else
		{
			return $v;
		}
	}
	
}