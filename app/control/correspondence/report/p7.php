<?php
class CorrespondenceReportP7Controller extends JControl

{
	/**
	 * لیست اصلی مشمولین ماده ۷
	 * @see BaseControllerClass::Start()
	 */
	function Start()
	{

		j::Enforce("Correspondence");
		$this->ParamFilter();
		$Pagination=array('Sort'=>$this->Sort,'Order'=>$this->Order,'Offset'=>$this->Offset,'Limit'=>$this->Limit);
		$Files = ORM::Query('ReviewFile')->FilesByCondition(array('State'=>'P7'), $Pagination);
		$this->Count = count($Files);
		$this->makeList($Files);
		
		$this->Result = $Result;
		$this->Error = $Error;
		if (count($this->Error)) $this->Result=false;
		return $this->Present();
	}
	/**
	 * لیست ماده ۷ موقت
	 */
	function ProvisoryAction(){
		j::Enforce("Correspondence");
		$this->ParamFilter();
		
		$files = ORM::Query('ReviewFile')->FilesByCondition(array('State'=>'P7'));
		ORM::Dump($files);
		
		
		$this->Result = $Result;
		$this->Error = $Error;
		if (count($this->Error)) $this->Result=false;
		return $this->Present();
	}
	function makeList($data){
		$al=new AutolistPlugin($data,null,"Select");
		$al->SetHeader('Classe', p::Classe,true);
		$al->SetHeader("GateCode", "کد گمرک",true);
		$al->SetHeader('DifferenceAmountHezar', p::DifferenceAmount);
		$al->SetHeader('Cat', p::Cat);
		$al->SetHeader('ReviewerName', p::Karshenas);
		$al->SetHeader('OwnerCoding', p::OwnerCoding);
		$al->SetHeader('Karshenas_salon', p::Karshenas_salon);
		$al->SetHeader('Karshenas_arzesh', p::Karshenas_arzesh);
		$al->HasTier=true;
		$al->TierLabel="ردیف";
		$al->ObjectAccess=true;
		$al->InputValues["RowsCount"]=100;
		$al->InputValues["Sort"]="CreateTimestamp";
		$al->InputValues["Order"]="ASC";
		$al->_LimitAttr=array("title"=>"اگر قسمت تعداد را صفر قرار دهید تمام کوتاژ ها را می توانید ببینید");
		$this->AutoList=$al;
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
	
	
	
}