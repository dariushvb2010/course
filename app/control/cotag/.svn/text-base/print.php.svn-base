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
			$Cotag=$_POST['Cotag']*1;
			$CDate=$c->JalaliToGregorian($CYear,$CMonth, $CDay);
			$FDate=$c->JalaliToGregorian($FYear, $FMonth, $FDay);
			$StartTimestamp=strtotime($CDate[0]."/".$CDate[1]."/".$CDate[2]);
			$FinishTimestamp=strtotime($FDate[0]."/".$FDate[1]."/".$FDate[2]);
			
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
//				var_dump($StartTimestamp,$FinishTimestamp);
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