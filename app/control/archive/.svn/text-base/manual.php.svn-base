<?php
class ArchiveManualController extends JControl
{
	function Start()
	{
		j::Enforce("Archive");
		
		if (count($_POST))
		{
			$c=new CalendarPlugin();
			$CYear=$_POST['CYear'];
			$CMonth=$_POST['CMonth'];
			$CDay=$_POST['CDay'];
			$Cotag=$_POST['Cotag']*1;
			$CDate=$c->JalaliToGregorian($CYear,$CMonth, $CDay);
			$CreateTimestamp=strtotime($CDate[0]."/".$CDate[1]."/".$CDate[2]);
			if(!$this->validateDate($CYear,$CMonth,$CDay))
			{
				$Error[]="زمان وصول ناصحیح است.";
			}
			else
			{
				$File=new ReviewFile($Cotag);
				ORM::Write($File);
				$Res=ORM::Query(new ReviewProgressRegisterArchive())->AddToFile($Cotag,$CreateTimestamp);
				if(is_string($Res))
				{
					$Error[]=$Res;
				}
				else if($Res)
					$this->Result="اظهارنامه با شماره کوتاژ ".$Cotag."با موفقیت ثبت گردید.";
			}
		}
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		return $this->Present();
	}
	function validateDate($y,$m,$d)
	{
		if( $y<1357)
			return false;
		else if($m<1 || $m>12)
			return false;
		else if($d>31||$d<1)
			return false;
		return true;
		
			
	}
}