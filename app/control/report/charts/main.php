<?php
class ReportChartsMainController extends JControl
{
	function Start()
	{
		$ChartTypeArray=array('daftar_cotag','percentage','yy');
		
		$ChartType=$ChartTypeArray[0];
		if(isset($_GET['charttype'])){
			if($_GET['charttype']>=0 AND $_GET['charttype']<count($ChartTypeArray)){
				$ChartType=$ChartTypeArray[$_GET['charttype']];
			}
		}
		
		$this->ChartType=$ChartType;
		$this->ConfigFileName=$ChartType;
		
		switch ($ChartType){
			case 'daftar_cotag':
				$days=30;
				$r=ORM::Query(new ReviewProgressStart)->DailyStart($days);
				$this->firstday=(time()-24*60*60*$days)*1000;
				foreach ($r as $key =>$value){
					$daily1[]=$value['count'];
				}
				$this->daily1=$daily1;
				break;
			case 'percentage':
				$percentarray=array(
					'خطا' =>50 ,
					'کسر دریافتی'=>26,
					'قطع مرور زمان'=>8,
					'غیره'=>2);
				foreach ($percentarray as $key=> $value){
					$out_ar[]="['{$key}' ,{$value}]";
				}
				$this->percentarray=$out_ar;
				
				break;
			
		}
		
		
		$this->Error=$Error;
		if(Count($Error))
			$this->Result=false;
		return $this->Present();
	}

}