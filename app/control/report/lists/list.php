<?php
class ReportListsListController extends JControl
{
	function Start()
	{
		if(j::Check("CotagList"));

		$this->ParamFilter();

		$this->HeadTitle="لیست کوتاژهای گمرک ".GateName;
		$Pagination=array('Sort'=>$this->Sort,'Order'=>$this->Order,'Offset'=>$this->Offset,'Limit'=>$this->Limit);
		$CotList=ORM::Query(new ReviewFile)->CotagList($Pagination,GateCode);
		$Count=ORM::Query(new ReviewFile)->CotagList('CountAll',GateCode);
		
		$this->PrepareToShow($CotList,$Count);

	}

	function OtherAction()
	{
		if(j::Check("CotagList"));

		$this->ParamFilter();

		$this->HeadTitle="لیست کوتاژهای گمرکات دیگر";
		$Pagination=array('Sort'=>$this->Sort,'Order'=>$this->Order,'Offset'=>$this->Offset,'Limit'=>$this->Limit);
		$CotList=ORM::Query(new ReviewFile)->CotagList($Pagination,GateCode,'!=');
		$Count=ORM::Query(new ReviewFile)->CotagList('CountAll',GateCode,'!=');

		$this->PrepareToShow($CotList,$Count);

	}

	function NotArchivedAction()
	{
		j::Enforce("NotArchivedList");
		
		$this->ParamFilter();

		$this->HeadTitle="بایگانی: لیست کوتاژهای وصول نشده از دفتر کوتاژ";
		$Pagination=array('Sort'=>$this->Sort,'Order'=>$this->Order,'Offset'=>$this->Offset,'Limit'=>$this->Limit);
		$CotList=ORM::Query(new ReviewFile)->FilesByStateName('Cotag','all',$Pagination);		
		$Count=ORM::Query(new ReviewFile)->FilesByStateName('Cotag','all','CountAll');		
		
		$this->PrepareToShow($CotList,$Count);

	}

	function AssignableAction()
	{
		j::Enforce("AssignableList");
		
		$this->ParamFilter();

		$this->HeadTitle="لیست اظهارنامه های تخصیص نیافته";
		$Pagination=array('Sort'=>$this->Sort,'Order'=>$this->Order,'Offset'=>$this->Offset,'Limit'=>$this->Limit);
		$CotList=ORM::Query(new ReviewFile)->FilesByStateName('Assignable','all',$Pagination);
		$Count=ORM::Query(new ReviewFile)->FilesByStateName('Assignable','all','CountAll');
		
		$this->PrepareToShow($CotList,$Count);

	}

	function MoshkeldarAction()
	{
		//j::Enforce("AssignableList");
		
		$this->ParamFilter();

		$this->HeadTitle="لیست اظهارنامه های مکاتباتی بدون کلاسه";
		$Pagination=array('Sort'=>$this->Sort,'Order'=>$this->Order,'Offset'=>$this->Offset,'Limit'=>$this->Limit);
		$CotList=ORM::Query("ReviewFile")->FilesByStateName('review_notok','all',$Pagination);
		$Count=ORM::Query(new ReviewFile)->FilesByStateName('review_notok','all','CountAll');
		
		$this->PrepareToShow($CotList,$Count);

	}
	
	function ParamFilter(){
		if(count($_GET))
		{
			$this->Offset=$_GET['off'];
			$this->Limit=$_GET['lim'];
			$this->Sort=$_GET['sort'];
			$this->Order=$_GET['ord'];
			//echo "<div>off:".$this->Offset."  lim:".$this->Limit."</div>";
		}
		else
		{
			$this->Offset=0;
			$this->Limit=100;
			$this->Sort="Cotag";
			$this->Order="ASC";
		}
	}
	
	/**
	 * 
	 * @param ReviewFile $CotList
	 * @return boolean
	 */
	function PrepareToShow($CotList,$countAll=0){
		
		if(count($CotList))
		{
			$this->CotList=$CotList;
			if($countAll==0)
				$this->RecordCount=count($CotList);
			else
				$this->RecordCount=$countAll;
		}
		else
		{
			$Error[]="اظهارنامه ای موجود نیست.";
		}
			
		$al=new AutolistPlugin($this->CotList,null,"Select");
		$al->SetMetadata(array('CreateTimestamp'=>array('CData'=>'?')));
		$al->SetHeader('Cotag', 'کوتاژ',true);
		$al->SetHeader("GateCode", "کد گمرک",true);
		$al->SetHeader('CreateTimestamp', "زمان وصول دفترکوتاژ",true);
		$al->SetHeader("AsyState", "صحت",true);
		$al->SetHeader("LastProgress", "آخرین فرایند",true);
		$al->SetFilter(array($this,"myfilter"));
		$al->Width="80%";
		$al->HasTier=true;
		$al->TierLabel="ردیف";
		$al->ObjectAccess=true;
		$al->InputValues["RowsCount"]=100;
		$al->InputValues["Sort"]="CreateTimestamp";
		$al->InputValues["Order"]="ASC";
		$al->_LimitAttr=array("title"=>"اگر قسمت تعداد را صفر قرار دهید تمام کوتاژ ها را می توانید ببینید");
		$this->AutoList=$al;
		
		$this->Error=$Error;
		if (count($Error))
			$this->Result=false;
		
		return $this->Present('','report/lists/listview');
	}
	
	function myfilter($k,$v,$D)
	{
	
		if ($k=='Cotag'){
			return v::CotagLink($D->Cotag(),$D->Gatecode().'-'.$D->Cotag());
		}elseif ($k=="CreateTimestamp"){
			if ($D->CreateTime())
				return $D->CreateTime();
			else 
				return "-";
		}elseif ($k=="LastProgress"){
			if ($P=$D->LLP())
				return $P->Title();
		}elseif ($k=="GateCode"){
				return $D->Gatecode();
		}elseif ($k=="AsyState"){
			//to complete remote data of files
			//$r=$D->CheckUp();
			//if(!$r){
			//	return 'خطا';
			//}
		}else{
			return $v;
		}
		
	}
	
}