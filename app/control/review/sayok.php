<?php
class ReviewSayokController extends JControl
{
	function Start()
	{
		j::Enforce("Reviewer");
		
		$ok_count=0;
		if(isset($_POST['item'])){
			foreach ($_POST['item'] as $item){
				echo $item;
				$Res=ORM::Query("ReviewProgressReview")->AddReviewOked($item);
				if(is_string($Res)){
					$item_err_list[$item]='خطا';
				}else{
					$ok_count++;
				}
			}
		}
		
		ORM::Flush();
		
		$CurrentUser=MyUser::CurrentUser();
		$MyUnreviewedFiles=$CurrentUser->AssignedReviewableFile();
		$al=new AutolistPlugin($MyUnreviewedFiles,null,"Select");
		$al->SetMetadata(array('CreateTimestamp'=>array('CData'=>'?')));
		$al->SetHeader('Select', 'انتخاب',true);
		$al->SetHeader('Cotag', 'کوتاژ',true);
		$al->HasTier=true;
		$al->TierLabel="ردیف";
		$al->ObjectAccess=true;
		$al->SetHeader('GateCode', 'کد گمرک',true);
		$al->SetHeader('assignCreateTimestamp', 'زمان تخصیص',true);
		$al->SetHeader('CreateTimestamp', 'زمان وصول',true);
		$al->SetHeader('error', '',true);
		$al->SetFilter(array($this,"myfilter"));
		$this->FileAutoList=$al;
		
		$this->Count=count($MyUnreviewedFiles);
		$this->OKCount=$ok_count;
		$this->MyUnreviewedFiles=$MyUnreviewedFiles;
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		return $this->Present();
	}
	
	function myfilter($k,$v,$D)
	{
		if($k=='CreateTimestamp')
		{
			$c=new CalendarPlugin();
			return "<span dir='ltr'>{$D->CreateTime()}</span>";
		}
		elseif($k=='assignCreateTimestamp')
		{
			$c=new CalendarPlugin();
			if($D->LLP('Assign'))
				return "<span dir='ltr'>{$c->JalaliFullTime($D->LLP('Assign')->CreateTimestamp())}</span>";
			else 	
				return '-';
		}
		elseif($k=='Select')
		{
			return "<input type='checkbox' class='item' value='{$D->Gatecode()}-{$D->Cotag()}' name='item[]' />";
		}
		elseif($k=='Cotag')
		{
			return "<a class='link_but' href='./?Cotag={$D->Gatecode()}-{$D->Cotag()}'>{$D->Cotag()}</a>";
		}
		elseif($k=='GateCode')
		{
			return $D->GateCode();
		}
		elseif($k=='error')
		{
			if(isset($item_err_list[$D->Cotag()])){
				return $item_err_list[$D->Cotag()];
			}else{
				return "-";
			}
		}
		else
		{
			return $v;
		}
	}
	
}