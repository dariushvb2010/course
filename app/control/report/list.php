<?php
class ReportListController extends JControl
{
	function Start()
	{
		if(j::Check("CotagList"));
		
		$Limit=$_GET['Limit']*1;
		$Offset=$_GET['Offset']*1;
		$Sort=$_GET['Sort'];
		$SortOrder=$_GET['SortOrder'];
		$Condition=$_GET['Condition'];
		if (!$Limit)
			$Limit=100;
		
		$this->makeMapArray();
		$flag=false;
		foreach ($this->mapArray as $k=>$m)
		{
			if ($Sort==$k)
				$flag=true;
		}
		if (!$flag) $Sort='Cotag';
		if ($SortOrder!='ASC' && $SortOrder!='DESC')
			$SortOrder='DESC';
		

//		$this->Data=$m->CotagList($Condition,$Offset,$Limit,$Sort,$SortOrder);
		
		$this->Data=ORM::Query(new ReviewFile)->CotagList($Offset,$Limit,$Sort,$SortOrder);
		if(isset($_POST['Cotag']))
		{
			$this->Data=ORM::Query(new ReviewFile)->FileWithLastProg($_POST['Cotag']);
			if(!$this->Data)
				$Error[]="اطلاعاتی برای کوتاژ وارد شده وجود ندارد.";
		}
		$this->Count=ORM::Query(new ReviewFile)->CotagCount();
		$this->Limit=$Limit;
		$this->Offset=$Offset;
		$this->Sort=$Sort;
		$this->SortOrder=$SortOrder;
		$this->Condition=$Condition;
		$this->Error=$Error;
		if(Count($Error))
			$this->Result=false;
		return $this->Present();
	}
	function makeMapArray()
	{
		$mapArray=array(
	"ID"=>"ردیف",
	"Cotag"=>"کوتاژ",
	"CreateTimestamp"=>"زمان وصول دفترکوتاژ",
	"FinishTimestamp"=>"زمان اختتام",
	"LastProgress"=>"فرآیند جاری",

		);
		$this->mapArray=$mapArray;
	}
}