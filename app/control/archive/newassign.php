<?php
class ArchiveNewassignController extends JControl
{
	function Start()
	{
		j::Enforce("Archive");
		
		if (count($_POST['item']))
		{
			foreach($_POST['item'] as $key=>$value)
			{
				
				$t=ORM::Find('ReviewFile',"Cotag", $value);
				if($t)
					$myfiles[]=$t[0];
				else
					$Error[]=" اظهارنامه با کوتاژ".$value."یافت نشد !";
			}
			$this->Files=$myfiles;
			if (!$this->Files or count($this->Files)==0)
			{
				$Error[]=" هیچ موردی انتخاب نشده یا کوتاژ ها معتبر نمی باشند !";
			}
			else
			{
				$Reviewer=ORM::Query(new MyUser)->getRandomReviewer();
				$this->ReviewerName= $Reviewer ? $Reviewer->getFullName() : "";
				foreach ($this->Files as $F)
				{
						$RegisterResult=ORM::Query(new ReviewProgressRegisterarchive())->AddByFile($F);
						if(is_string($RegisterResult))
						{
							$Error[]=$RegisterResult;
						}
						else 
						{
							ORM::Flush();
							$AssignResult=ORM::Query(new ReviewProgressAssign())->AddToFile($F,$Reviewer);
							if(is_string($AssignResult))
							{
								$Error[]=$AssignResult;
							}
							else 
							{
								$this->Reviewer=$AssignResult->Reviewer();
								$this->AssignDate=$AssignResult->CreateTimestamp();
								$ala[]=array('ID'=>$F->ID(),
											 'Cotag'=>$F->Cotag(),
												);
							}
						}
						
				}
				ORM::Flush();
				$al2=new AutolistPlugin($ala,array('Cotag'=>'کوتاژ',),null,true,"ردیف");
					$al2->SetFilter(array($this,'myfilter2'));
					$al2->InputValues['ColsCount']=3;
					$al2->InputValues['RowsCount']="auto";
					$al2->HasPageBreak=false;
					$al->HasTier=true;
					$al->TierLabel="ردیف";
					$this->ResultList=$al2;
					$jl=new CalendarPlugin();
					$this->AssignDate=$jl->JalaliFullTime($this->AssignDate);
			}
		}
		else //listing items
		{

			$al=new AutolistPlugin(null,null,"Select");
			$al->SetMetadata(array('CreateTimestamp'=>array('CData'=>'?')));
			$al->SetHeader('Select', 'انتخاب',true);
			$al->SetHeader('Cotag', 'کوتاژ');
			$al->HasTier=true;
			$al->TierLabel="ردیف";
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
		if($k=='CreateTime')
		{
			$c=new CalendarPlugin();
			return "<span dir='ltr'>".$v."</span>";
		}
//		elseif($k=='Reviewer')
//		{
//			if ($o->LastReviewer())
//				return $o->LastReviewer()->getFullName();
//			else
//				return "<span style='color:red;'> خطا تخصیص نیافت</span>";
//		}
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