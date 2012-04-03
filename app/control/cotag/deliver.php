<?php
class CotagDeliverController extends JControl
{
	function Start()
	{
		j::Enforce("CotagBook");
		$c=new CalendarPlugin();
		$this->today=explode("/", $c->JalaliFromTimestamp(time()));
		$this->tomorrow=explode("/", $c->JalaliFromTimestamp(strtotime("tomorrow")));
		if (count($_POST))
		{
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
			$Cotag=$_POST['Cotag']*1;
			$CDate=$c->JalaliToGregorian($CYear,$CMonth, $CDay);
			var_dump($c->JalaliToGregorian(1391,2,12));
			$FDate=$c->JalaliToGregorian($FYear, $FMonth, $FDay);
			$StartTimestamp=strtotime($CDate[0]."/".$CDate[1]."/".$CDate[2]." ".$CHour.":".$CMin);
			$FinishTimestamp=strtotime($FDate[0]."/".$FDate[1]."/".$FDate[2]." ".$FHour.":".$FMin);
			$f=$FDate[0]."/".$FDate[1]."/".$FDate[2]." ".$FHour.":".$FMin; echo $f."<br/>";
			echo "s:".$StartTimestamp." f:".$FinishTimestamp;
				
			
			if(!$this->validateDate($CYear,$CMonth,$CDay))
			{
				$Error[]="تاریخ آغازی ناصحیح است.";
			}
			if(!$this->validateDate($FYear,$FMonth,$FDay))
			{
				$Error[]="تاریخ پایانی ناصحیح است.";
			}
			else
			{
				$res=ORM::Query(new ReviewProgressDeliver())->AddToFiles($StartTimestamp,$FinishTimestamp,$_POST['mailnum']);
				if(is_string($res))
					$Error[]=$res;
				else 
					$Files=$res;
			}
		}
		
 		$this->Count=count($Files);
		$this->Files=$Files;
		
		$al=new AutolistPlugin($Files,null,"Select",true,"ردیف");
		$al->SetMetadata(array('CreateTimestamp'=>array('CData'=>'?')));
		$al->SetHeader('Cotag', 'کوتاژ');
		$al->Width="80%";
		$al->ObjectAccess=true;
		$al->InputValues['ColsCount']=5;
		$al->InputValues['RowsCount']=30;
		$al->_LimitAttr=array("title"=>"اگر قسمت تعداد را صفر قرار دهید تمام کوتاژ ها را می توانید ببینید");
		$al->_TierAttr=array("style"=>"font-size:11pt;");//ردیف
		$this->DeliverList=$al;
		
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		return $this->Present();
	}
	

	private function validateDate($y,$m,$d)
	{
		if($y<1357)
			return false;
		else if($m<1 || $m>12)
			return false;
		else if($d>31||$d<1)
			return false;
		return true;
		
			
	}
}