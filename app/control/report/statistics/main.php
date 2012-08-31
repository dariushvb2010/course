<?php
class ReportStatisticsMainController extends JControl
{
	function Start()
	{
		$ChartTypeArray=array('numberResults');
		
		$ChartType=$ChartTypeArray[0];
		if(isset($_GET['charttype'])){
			if($_GET['charttype']>=0 AND $_GET['charttype']<count($ChartTypeArray)){
				$ChartType=$ChartTypeArray[$_GET['charttype']];
			}
		}
		
		$this->ChartType=$ChartType;
		
		switch ($ChartType){
			//////////////////////////////////////////////////////
			case 'numberResults':
				$c=new CalendarPlugin();
				
				if(isset($_POST['Date'])){
					$c=new CalendarPlugin();
					$CYear=$_POST['CYear'];
					$CMonth=$_POST['CMonth'];
					$CDay=$_POST['CDay'];
					$CHour=$_POST['CHour'];
					$CMin=$_POST['CMin'];
					$FYear=$_POST['FYear'];
					$FMonth=$_POST['FMonth'];
					$FDay=$_POST['FDay'];
					$FHour=$_POST['FHour'];
					$FMin=$_POST['FMin'];
					$StartTimestamp=$c->Jalali2Timestamp($CYear,$CMonth, $CDay, $CHour, $CMin);
					$FinishTimestamp=$c->Jalali2Timestamp($FYear, $FMonth, $FDay, $FHour, $FMin);
				}else{					
					$StartTimestamp=strtotime("-30 days");
					$FinishTimestamp=time();
					$this->today=explode("/", $c->JalaliFromTimestamp($StartTimestamp));
					$this->tomorrow=explode("/", $c->JalaliFromTimestamp($FinishTimestamp));
				}
				$data=array();
				$r=ORM::Query("ReviewProgressReview")->ReviewStatistics($StartTimestamp,$FinishTimestamp,'Result');
				$al1=new AutolistPlugin($r,null,"Select");
				//				$al1->SetHeader('Key', 'اطلاعات');
				$al1->SetHeader('Result', 'نتیجه');
				$al1->SetHeader('Count', 'تعداد');
				$al1->SetHeader('Sum', 'جمع اختلاف');
				$al1->InputValues['ColsCount']=1;
				$al1->InputValues['RowsCount']=2;
				$al1->SetFilter(array($this,"myfilter"));
				$this->AutoListResult=$al1;
				
				$r2=ORM::Query("ReviewProgressReview")->ReviewStatistics($StartTimestamp,$FinishTimestamp,'Provision');
				$al2=new AutolistPlugin($r2,null,"Select");
				//				$al1->SetHeader('Key', 'اطلاعات');
				$al2->SetHeader('Provision', 'شماره کلاسه');
				$al2->SetHeader('Count', 'تعداد');
				$al2->SetHeader('Sum', 'جمع اختلاف');
				$al2->InputValues['ColsCount']=1;
				$al2->InputValues['RowsCount']=4;
				$al2->SetFilter(array($this,"myfilter"));
				$this->AutoListProvision=$al2;

				$r3=ORM::Query("ReviewProgressReview")->ReviewStatistics($StartTimestamp,$FinishTimestamp,'Difference');
				$al3=new AutolistPlugin($r3,null,"Select");
				//				$al1->SetHeader('Key', 'اطلاعات');
				$al3->SetHeader('Difference', 'نوع اختلاف');
				$al3->SetHeader('Count', 'تعداد');
				$al3->SetHeader('Sum', 'جمع اختلاف');
				$al3->LeftDataLabel="شماره کلاسه";
				$al3->InputValues['ColsCount']=1;
				$al3->SetFilter(array($this,"myfilter"));
				$this->AutoListDifference=$al3;
				break;
			/////////////////////////////////////////////////////	
			case 'percentage':
				/*$r=ORM::Query("ReviewProgressReview")->ReviewPercentage();
				$percentarray=array(
					'خطا' => $r['oked'] ,
					'کسر دریافتی'=> $r['a528'],
					'قطع مرور زمان'=> $r['a109'], 
					'غیره'=> $r['a248']);
				foreach ($percentarray as $key=> $value){
					$out_ar[]="['{$key}' ,{$value}]";
				}
				$this->percentarray=$out_ar;
				break;*/
			/////////////////////////////////////////////////////	
			case 'karshenas_work_volume':
				/*$r=ORM::Query("ReviewProgressReview")->karshenas_work_lastmounth();
				$names=array();
				$values=array();
				foreach ($r as $value){
					$names[]="'".$value['user']->getFullName()."'";
					$values[]=$value['count'];
				}
				$this->values=$values;
				//$this->values=array(5,5);
				$this->names=$names;
				break;*/
			
		}
		
		if($data)
			$this->data=$data;
		
		$this->Error=$Error;
		if(Count($Error))
			$this->Result=false;
		return $this->Present();
	}
	
	function myfilter($k,$v,$D)
	{
		if($k=='Result')
			return ReviewProgressReview::PersianResult($v);
		elseif(!$v || $v==' ')
			return '-';
		elseif($k=='Difference')
			return ReviewProgressReview::PersianDifference($v);
		elseif($k=='Provision')
			return ReviewProgressReview::PersianProvision($v);
		elseif($k=='Sum')
			return number_format($v);
		else
			return $v;
	}

}