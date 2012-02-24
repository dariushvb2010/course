<?php
class ReportAssignedrangeController extends JControl
{
	function Start()
	{
		if(j::Check("AssignedList"));
		
		if (count($_POST))
		{
			$RangeStart=$_POST['RangeStart']*1;
			$RangeEnd=$_POST['RangeEnd']*1;
			if ($RangeEnd-$RangeStart<=0)
				$Error[]="بازه تعریف شده ناصحیح است.";
			else
			{
				$this->Files=ORM::Query(new ReviewFile)->AssignedFilesInRange($RangeStart,$RangeEnd);
				if (!$this->Files or count($this->Files)==0)
				{
					$Error[]="هیچ اظهارنامه ای در بازه ی مشخص شده دارای کارشناس نیست!";
				}else{
					$this->tableparamlist=array(
								'Cotag'=>'کوتاژ',
								'Reviewer'=>'کارشناس انتخاب شده'
					);
					$al=new AutolistPlugin($this->Files,$this->tableparamlist,'assignresult');
					$al->HasTier=true;
					$al->TierLabel="ردیف";
					$al->SetHeader('CreateTime', 'زمان وصول',true);
					$al->ObjectAccess=true;
					$al->SetFilter(array($this,'myfilter'));
					$this->AssignedList=$al;
				}
			}
		}
		$this->Error=$Error;
		if (count($Error))
		$this->Result=false;
		return $this->Present();
	}
	function myfilter($k,$v,$o){
		if($k=='CreateTimestamp' && $o->LastReview()){
			$c=new CalendarPlugin();
			return "<span dir='ltr'>".$o->LastReview()->CreateTimestamp()."</span>";
		}elseif($k=='Reviewer' && $o->LastReviewer()){
			return $o->LastReviewer()->getFullName();
		}else{
			return $v;
		}
	}
}