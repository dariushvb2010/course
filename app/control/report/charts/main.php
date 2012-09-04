<?php
class ReportChartsMainController extends JControl
{
	function Start()
	{
		$ChartTypeArray=array('daftar_cotag','percentage','karshenas_work_volume','in_vs_out','progress_remove','review_amount','review_amount_karshenas','karshenas_arzesh');
		
		$ChartType=$ChartTypeArray[0];
		if(isset($_GET['charttype'])){
			$ChartTypeGet=$_GET['charttype']*1;
			if($_GET['charttype']>=0 AND $_GET['charttype']<count($ChartTypeArray)){
				
				$ChartType=$ChartTypeArray[$_GET['charttype']];
				
			}
		}
		$this->ChartType=$ChartType;
		$this->ConfigFileName=$ChartType;
		$this->ctype=$ChartType;
		
		//-----------------------date interval -------------------------
		$vi = new ViewIntervalPlugin('month');
		
		$q = $vi->GetRequest();
		$this->VI=$vi;
		
		//$c = new CalendarPlugin();
		$c = new JalaliCalendar();
		$nn = $c->TodayJalaliArray();
		$thisYear = $nn[0];
		$thisMonth = $nn[1];
		
		$vi->DefaultDate['CYear'] = $thisYear-1;
		
		$CYear = $q['CYear'];
		$CMonth = $q['CMonth'];
		$FYear=$q['FYear'];
		$FMonth=$q['FMonth'];
		
		$startTimestamp=$c->Jalali2Timestamp($q['CYear'], $q['CMonth'], $q['CDay']);
		$finishTimestamp=$c->Jalali2Timestamp($q['FYear'], $q['FMonth'], $q['FDay']);
		
		$CdiffMonth = ( $thisYear-$CYear )*12 + $thisMonth - $CMonth;
		$FdiffMonth = ( $thisYear-$FYear )*12 + $thisMonth - $FMonth;
		$startMonth = $FdiffMonth;
		$monthCount = $CdiffMonth - $FdiffMonth + 1;
		
		//--------------------------------------------------------------
		$r = ORM::Query("ReviewProgressSend")->SendCountPerMonth(12);
		switch ($ChartType){
			//////////////////////////////////////////////////////
			case 'daftar_cotag':
				$days=30;
				$r=ORM::Query("ReviewProgressStart")->DailyStart($days);
				$this->firstday=(time()-24*60*60*$days)*1000;
				foreach ($r as $key =>$value){
					$daily1[]=$value['count'];
				}
				$this->daily1=$daily1;
				break;
			/////////////////////////////////////////////////////	
			case 'percentage':
				$r=ORM::Query("ReviewProgressReview")->ReviewPercentage();
				$percentarray=array(
					'خطا' => $r['oked'] ,
					'کسر دریافتی'=> $r['a528'],
					'قطع مرور زمان'=> $r['a109'], 
					'غیره'=> $r['a248']);
				foreach ($percentarray as $key=> $value){
					$out_ar[]="['{$key}' ,{$value}]";
				}
				$this->percentarray=$out_ar;
				break;
			/////////////////////////////////////////////////////	
			case 'karshenas_work_volume':
				$r=ORM::Query("ReviewProgressReview")->karshenas_work($startTimestamp,$finishTimestamp);
				$names=array();
				$values=array();
				foreach ($r as $value){
					$names[]="'".$value['user']->getFullName()."'";
					$values[]=$value['count'];
				}
				$this->values=$values;
				//$this->values=array(5,5);
				$this->names=$names;
				break;
			/////////////////////////////////////////////////////	
			case 'in_vs_out':
				
				
				$r=ORM::Query("ReviewProgress")->ProgressCountPerMonth("Start",$monthCount,$startMonth);
				$this->in=$r;
				
				
				$r=ORM::Query("ReviewProgress")->ProgressCountPerMonth("Review",$monthCount,$startMonth);
				$this->out=$r;
				
				$bb = FPlugin::PersianMonthesInInterval($startMonth, $monthCount);
				
				foreach ($bb as $value){
					$X[]="'".$value['monthName']." ".substr($value['year'],2)."'";
				}
				$this->X=$X;
				
			
				break;
			case 'progress_remove':
				
				$r=ORM::Query("ReviewProgress")->ProgressCountPerMonth("Remove",$monthCount,$startMonth);
				//$r=ORM::Query("ReviewProgressReview")->ReviewAmountPer($monthCount, $startMonth,'month');
				$this->removes=$r;
				
				$bb = FPlugin::PersianMonthesInInterval($startMonth, $monthCount);				
				foreach ($bb as $value){
					$X[]="'".$value['monthName']." ".substr($value['year'],2)."'";
				}
				$this->X=$X;
				break;
			case 'review_amount':
				
				$r=ORM::Query("ReviewProgressReview")->ReviewAmountPer($monthCount, $startMonth,'month');
				$this->st248=$r["248"];
				$this->st528=$r["528"];
				$this->st109=$r["109"];
				$this->stempty   =$r[""];
				
				//----total----
				$this->totempty=$r["total"][""];
				$this->tot109=$r["total"]["109"];
				$this->tot248=$r["total"]["248"];
				$this->tot528=$r["total"]["528"];
				
				$bb = FPlugin::PersianMonthesInInterval($startMonth, $monthCount);
				foreach ($bb as $value){
					$X[]="'".$value['monthName']." ".substr($value['year'],2)."'";
				}
				$this->X=$X;
			break;
			case 'review_amount_karshenas':
				$r=ORM::Query("ReviewProgressReview")->ReviewAmountPer($startTimestamp,$finishTimestamp,'karshenas');				
				$this->st248=$r["248"];
				$this->st528=$r["528"];
				$this->st109=$r["109"];
				$X=$r["per"];
				$this->stempty   =$r[""];
				
				//----total----
				$this->totempty=$r["total"][""];
				$this->tot109=$r["total"]["109"];
				$this->tot248=$r["total"]["248"];
				$this->tot528=$r["total"]["528"];
				
				
				foreach ($X as $k=>$v){
					$u=MyUser::getUser($v);
					$X2[]="'".$u->getFullName()."'";
				}
				$this->X=$X2;
			break;
			case 'karshenas_arzesh':
				$r=ORM::Query("ReviewProgressReview")->ReviewAmountPer($startTimestamp,$finishTimestamp,'karshenas_arzesh');
				$this->st248=$r["248"];
				$this->st528=$r["528"];
				$this->st109=$r["109"];
				$X=$r["per"];
				$this->stempty   =$r[""];
			
				//----total----
				$this->totempty=$r["total"][""];
				$this->tot109=$r["total"]["109"];
				$this->tot248=$r["total"]["248"];
				$this->tot528=$r["total"]["528"];
			
			
				foreach ($X as $k=>$v){
					$u=MyUser::getUser($v);
					$X2[]="'".$u->getFullName()."'";
				}
				$this->X=$X2;
				break;
		}
		
		$this->Error=$Error;
		if(Count($Error))
			$this->Result=false;
		return $this->Present();
	}

}