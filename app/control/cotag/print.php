<?php
class CotagPrintController extends JControl
{
	function Start()
	{
		j::Enforce("CotagBook");
		$c=new CalendarPlugin();
		$this->today=explode("/", $c->JalaliFromTimestamp(time()));
		if (count($_POST))
		{
			$CYear=$_POST['CYear'];
			$CMonth=$_POST['CMonth'];
			$CDay=$_POST['CDay'];
			$FYear=$_POST['FYear'];
			$FMonth=$_POST['FMonth'];
			$FDay=$_POST['FDay'];
			$Cotag=b::CotagFilter($_POST['Cotag']);
			$StartTimestamp=$c->Jalali2Timestamp($CYear,$CMonth, $CDay);
			$FinishTimestamp=$c->Jalali2Timestamp($FYear, $FMonth, $FDay);
			
			if(!$this->validateDate($CYear,$CMonth,$CDay))
			{
				$Error[]="تاریخ آغازی ناصحیح است.";
			}
			elseif(!$this->validateDate($FYear,$FMonth,$FDay))
			{
				$Error[]="تاریخ پایانی ناصحیح است.";
			}
			else
			{
				
//				$res=ORM::Query(new ReviewProgressStart())->Prints($StartTimestamp,$FinishTimestamp);
				$this->Redirect("./barcodes?st=".$StartTimestamp."&ft=".$FinishTimestamp);
			}
		}
		
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		return $this->Present();
	}
	
	function myfilter($k,$v,$D)
	{
		if($k=='CreateTimestamp')
		{
			return "<span dir='ltr'>{$D->CreateTime()}</span>";
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
	function validateDate($y,$m,$d)
	{
		if($y<1357)
			return false;
		else if($m<1 || $m>12)
			return false;
		else if($d>31||$d<1)
			return false;
		return true;
		
			
	}
}