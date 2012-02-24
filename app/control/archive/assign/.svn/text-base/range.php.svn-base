<?php
class ArchiveAssignRangeController extends JControl
{
	function Start()
	{
		j::Enforce("Archive");
		
		if (count($_POST))
		{
			$RangeStart=$_POST['RangeStart']*1;
			$RangeEnd=$_POST['RangeEnd']*1;
			if ($RangeEnd-$RangeStart<=0)
				$Error[]="بازه تعریف شده ناصحیح است.";
			
			else
			{
				$this->Files=ORM::Query(new ReviewFile)->UnassignedFilesInRange($RangeStart,$RangeEnd);
				if (!$this->Files or count($this->Files)==0)
				{
					$Error[]="اظهارنامه بازبینی نشده‌ای در بازه ذکر شده یافت نشد!";
				}
				else if(count($this->Files)>100)
				{
					
					$Error[]="تعداد اظهار نامه های موجود در بازه بیش از ۱۰۰ اظهار نامه می باشد !";
				}
				else
				{
					
					$Reviewer=ORM::Query(new MyUser)->getRandomReviewer();	
					foreach ($this->Files as $F)
					{
						$AssignResult=ORM::Query(new ReviewProgressAssign())->AddToFile($F,$Reviewer);
						if(is_string($AssignResult))
						{
							$Error[]=$AssignResult;
						}
						else 
						{
							$this->Reviewer=$AssignResult->Reviewer();
						}
					}
					
					ORM::Flush();
		
					$al=new AutolistPlugin($this->Files,array(
						'ID'=>'ردیف',
						'Cotag'=>'کوتاژ',
						'Reviewer'=>'کارشناس انتخاب شده'
				));
					$al->SetHeader('CreateTime', 'زمان وصول',true);
					$al->ObjectAccess=true;
					$al->SetFilter(array($this,'myfilter'));
					$this->ResultList=$al;
					
				}
			}
		}
		$this->Error=$Error;
		if (count($Error))
		$this->Result=false;
		return $this->Present();
	}
	
	function myfilter($k,$v,$o)
	{
		if($k=='CreateTimestamp')
		{
			$c=new CalendarPlugin();
			return "<span dir='ltr'>".$v."</span>";
		}
		elseif($k=='Reviewer')
		{
			if ($o->LastReviewer())
				return $o->LastReviewer()->getFullName();
			else
				return "";
		}
		else
		{
			return $v;
		}
	}
}