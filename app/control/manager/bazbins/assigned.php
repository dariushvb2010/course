<?php
class ManagerBazbinsAssignedController extends JControl
{
	function Start()
	{
		j::Enforce("MasterHand");
		
		if (count($_POST['item']))
		{
			foreach($_POST['item'] as $key=>$value)
			{
				$t=b::GetFile($value);
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
					$Reviewer=ORM::Query(new MyUser)->getRandomReviewer();
					$AssignResult=ORM::Query("ReviewProgressAssign")->AddToFile($F,$Reviewer);
					if(is_string($AssignResult))
					{
						$Error[]=$AssignResult;
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
		elseif($_GET['id']) //listing items
		{
			$AUser=MyUser::getUser($_GET['id']);
			$MyUnreviewedFiles=$AUser->AssignedReviewableFile();
			if($MyUnreviewedFiles){
				$al = $this->GetData($MyUnreviewedFiles);
// 				$al=new AutolistPlugin($data,null,"Select");
// 				$al->SetMetadata(array('CreateTimestamp'=>array('CData'=>'?')));
// 				$al->SetHeader('Select', 'انتخاب',true);
// 				$al->SetHeader('Cotag', 'کوتاژ');
// 				$al->HasTier = true;
// 				$al->TierLabel = 'ردیف';
// 				$al->ObjectAccess=true;
// 				$al->SetHeader('CreateTimestamp', 'زمان وصول',true);
// 				$al->SetFilter(array($this,"myfilter"));
				
				$this->FileAutoList=$al;
				
				$this->Count=count($MyUnreviewedFiles);
				$this->MyUnreviewedFiles=$MyUnreviewedFiles;
			}else{
				$Error[]='هیچ اظهارنامه ای نزد کارشناس موجود نیست.';
			}
		}else{
			$Error[]='هیچ کارشناسی انتخاب نشده است.';
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
			return "<span dir='ltr'>".$D->CreateTime()."</span>";
		}
		elseif($k=='Select')
		{
			return "<input type='checkbox' class='item' value='".$D->ID()."' name='item[]' />";
		}
		else
		{
			return $v;
		}
	}
	function GetData($Files){
		$ret = array();
		foreach ($Files as $File)
		{
			if($File->Asy())
				$ret[]=$File->Asy();
		}
		$al = new AutolistPlugin($ret, null, "Select");
				$al->SetMetadata(array('CreateTimestamp'=>array('CData'=>'?')));
				$al->SetHeader('Select', 'انتخاب',true);
				$al->SetHeader('Cotag', 'کوتاژ');
				$al->SetHeader('Masir','مسیر');
				$al->SetHeader('RegTime','تاریخ');
				$al->SetHeader('OwnerName','صاحب کالا');
				$al->SetHeader('OwnerCoding','کدینک صاحب کالا');
				$al->SetHeader('TotalTaxes','جمع عوارض');
				$al->SetHeader('DeclarantCoding','کدینک اظهارکننده');
				$al->SetHeader('Karshenas_salon','کارشناس سالن');
				$al->SetHeader('Arzyab','ارزیاب');
				$al->SetHeader('RialPrice','ارزش کل');
				$al->SetFilter(array($this,"myfilter"));
				$al->HasTier = true;
				$al->TierLabel = 'ردیف';
				$al->ObjectAccess=true;
		
		return $al;
	}

}