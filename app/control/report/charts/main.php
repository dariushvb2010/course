<?php
class ReportChartsMainController extends JControl
{
	function Start()
	{
		$ChartTypeArray=array('daftar_cotag','percentage','karshenas_work_volume','bazbini_speed');
		
		$ChartType=$ChartTypeArray[0];
		if(isset($_GET['charttype'])){
			if($_GET['charttype']>=0 AND $_GET['charttype']<count($ChartTypeArray)){
				$ChartType=$ChartTypeArray[$_GET['charttype']];
			}
		}
		
		$this->ChartType=$ChartType;
		$this->ConfigFileName=$ChartType;
		
		switch ($ChartType){
			//////////////////////////////////////////////////////
			case 'daftar_cotag':
				$days=30;
				$r=ORM::Query(new ReviewProgressStart)->DailyStart($days);
				$this->firstday=(time()-24*60*60*$days)*1000;
				foreach ($r as $key =>$value){
					$daily1[]=$value['count'];
				}
				$this->daily1=$daily1;
				break;
			/////////////////////////////////////////////////////	
			case 'percentage':
				$r=ORM::Query(new ReviewProgressReview)->ReviewPercentage();
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
				$r=ORM::Query(new ReviewProgressReview)->karshenas_work_lastmounth();
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
			case 'bazbini_speed':
				$r=ORM::Query(new ReviewProgressReview)->BazbiniPerMonth();
				$names=array();
				$values=array();
				foreach ($r as $value){
					$X[]="'".$value['monthname']." ".$value['year']."'";
					$values[]=$value['count'];
				}
				$this->values=$values;
				$this->X=$X;
				break;
			
		}
		
		
		$this->Error=$Error;
		if(Count($Error))
			$this->Result=false;
		return $this->Present();
	}

}