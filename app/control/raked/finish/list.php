<?php
class RakedFinishListController extends JControl
{
	function Start()
	{
		j::Enforce("Raked");
		
		if (count($_POST['item']))
		{
			$FinishError=false;
			foreach($_POST['item'] as $key=>$value)
			{
				$somefile=b::GetFile($value);
				if($somefile){
					$Res=ORM::Query(new ReviewProgressFinish())->FinishByCotag($somefile->Cotag());
					if(!is_string($Res))
					{
						$myfiles[]=$somefile;
					}else{
						$FinishError=true;
					}
				}
			}
			
			$this->Files=$myfiles;
			if (!$this->Files or count($this->Files)==0)
			{
				if($FinishError){
					$Error[]="کوتاژ های انتخاب شده قابل مختومه شدن نیستند";
				}else{
					$Error[]="هیچ موردی انتخاب نشده!";
				}
			}
			else
			{
				foreach ($this->Files as $F)
				{
					$AssignResult=ORM::Query(new ReviewProgressAssign())->AddToFile($F);
				}
				ORM::Flush();
				$al2=new AutolistPlugin($this->Files,array(
					'ID'=>'ردیف',
					'Cotag'=>'کوتاژ',
					),'finishresult');
					$al2->ObjectAccess=true;
					$this->ResultList=$al2;
			}
		}
		else //listing items
		{
			
			$off=(isset($_REQUEST['off'])?$_REQUEST['off']:0)*1;
			$lim=(isset($_REQUEST['lim'])?$_REQUEST['lim']:100)*1;
			$sort=(isset($_REQUEST['sort'])?$_REQUEST['sort']:null);
			$ord=(isset($_REQUEST['ord'])?$_REQUEST['ord']:null);
			
			$al=new AutolistPlugin(null,null,"Select");
			$al->SetMetadata(array('CreateTimestamp'=>array('CData'=>'?')));
			$al->SetHeader('Select', 'انتخاب',true,true);
			$al->SetHeader('Cotag', 'کوتاژ');
			$al->SetHeader('CreateTimestamp', 'زمان وصول',true);
			$al->ObjectAccess=true;
			$al->SetFilter(array($this,"myfilter"));
			
			
			$al->SetSort($Sort,$ord);
			$sort=$al->Sort;
			$ord=$al->Order;

			$FinishableFiles=ORM::Query(new ReviewFile)->FinishableFiles($off,$lim,$sort,$ord);
			
			if(!count($FinishableFiles))
			{
				$Error[]="اظهارنامه ای قابل مختومه شدن نیست.";	
			}else{
				$this->FinishableFiles=$FinishableFiles;
				$al->SetData($this->FinishableFiles);
				$this->AssignList=$al;
			}
		}
		$this->Error=$Error;
		if (count($Error))
		$this->Result=false;
		return $this->Present();
	}

	function myfilter($k,$v,$D)
	{
		if($k=='Cotag')
		{
			return $D->Cotag();
		}
		elseif($k=='CreateTimestamp')
		{
			$c=new CalendarPlugin();
			return "<span dir='ltr'>".$c->JalaliFullTime($D->CreateTime())."</span>";
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

}