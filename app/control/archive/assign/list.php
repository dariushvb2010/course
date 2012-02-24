<?php
class ArchiveAssignListController extends JControl
{
	function Start()
	{
		j::Enforce("Archive");
		
		if (count($_POST['item']))
		{
			foreach($_POST['item'] as $key=>$value)
			{
				$t=ORM::Find('ReviewFile', $value);
				if($t)
				$myfiles[]=$t;
			}
			$this->Files=$myfiles;
			if (!$this->Files or count($this->Files)==0)
			{
				$Error[]="هیچ موردی انتخاب نشده!";
			}
			else
			{
				foreach ($this->Files as $F)
				{
						$AssignResult=ORM::Query(new ReviewProgressAssign())->AddToFile($F);
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
				$al2=new AutolistPlugin($this->Files,array(
					'ID'=>'ردیف',
					'Cotag'=>'کوتاژ',
					'Reviewer'=>'کارشناس انتخاب شده'
					));
					$al2->SetHeader('CreateTime', 'زمان وصول',true);
					$al2->ObjectAccess=true;
					$al2->SetFilter(array($this,'myfilter2'));
					$this->ResultList=$al2;
			}
		}
		else //listing items
		{

			$UnassignedFiles=ORM::Query(new ReviewFile)->UnassignedFiles($_POST['off'],$_POST['lim']);
			if(count($UnassignedFiles))
			{
				foreach($UnassignedFiles as $key=>$value)
				{
					$UnassignedFiles[$key]['Select']=$UnassignedFiles[$key]['ID'];
				}
				$this->UnassignedFiles=$UnassignedFiles;
			}
			else
			{
				$Error[]="اظهارنامه قابل تخصیصی وجود ندارد.";
			}
				
			$al=new AutolistPlugin($this->UnassignedFiles,null,"Select");
			$al->SetMetadata(array('CreateTimestamp'=>array('CData'=>'?')));
			$al->SetHeader('Select', 'انتخاب',true);
			$al->SetHeader('Cotag', 'کوتاژ');
			$al->SetHeader('CreateTimestamp', 'زمان وصول',true);
			$al->SetFilter(array($this,"myfilter"));
			$this->AssignList=$al;
		}
		$this->Error=$Error;
		if (count($Error))
		$this->Result=false;
		return $this->Present();
	}
	function myfilter2($k,$v,$o)
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
	function myfilter($k,$v,$D)
	{
		if($k=='CreateTimestamp')
		{
			$c=new CalendarPlugin();
			return "<span dir='ltr'>".$c->JalaliFullTime($v)."</span>";
		}
		elseif($k=='Select')
		{
			return "<input type='checkbox' class='item' value='".$D['ID']."' name='item[]' />";
		}
		else
		{
			return $v;
		}
	}

}