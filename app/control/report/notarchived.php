<?php
class ReportNotarchivedController extends JControl
{
	function Start()
	{
		j::Enforce("NotArchivedList");
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
		//NotArchivedFiles=NAF
		$NAF=ORM::Query(new ReviewFile)->CotagBookNotSentFiles($this->Offset,$this->Limit,$this->Sort,$this->Order);
		//ORM::Dump($NAF);
		if(count($NAF))
		{
			$this->NAF=$NAF;
		}
		else
		{
			$Error[]="اظهارنامه ای در این بازه موجود نیست.";
		}
			
		$al=new AutolistPlugin($this->NAF,null,"Select");
		$al->SetMetadata(array('CreateTimestamp'=>array('CData'=>'?')));
		$al->SetHeader('Cotag', 'کوتاژ');
		$al->SetHeader('CreateTimestamp', 'زمان وصول',true);
		$al->SetFilter(array($this,"myfilter"));
		$al->Width="80%";
		$al->HasTier=true;
		$al->TierLabel="ردیف";
		$al->InputValues["RowsCount"]=100;
		$al->InputValues["Sort"]="CreateTimestamp";
		$al->InputValues["Order"]="ASC";
		$al->_LimitAttr=array("title"=>"اگر قسمت تعداد را صفر قرار دهید تمام کوتاژ ها را می توانید ببینید");
		$this->NotArchivedList=$al;
		
		$this->Error=$Error;
		if (count($Error))
			$this->Result=false;
		return $this->Present();
	}
	
	function myfilter($k,$v,$D)
	{
		if($k=='CreateTimestamp')
		{
			$c=new CalendarPlugin();
			$str=$c->JalaliFromTimestamp($v);
			return "<span dir='ltr'>".$str."</span>";
		}
		else
		{
			return $v;
		}
	}
	

}