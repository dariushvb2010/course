<?php
class ReportNotrecievedController extends JControl
{
	function Start()
	{
		if(j::Check("NotReceivedInCotagBook"));
		
		if(isset($_POST['RangeStart']) && isset($_POST['RangeEnd']))
		{
			$start=$_REQUEST['RangeStart']*1;
			$end=$_REQUEST['RangeEnd']*1; 
			if ($end-$start<=0 || $start<1)
			{
				$Error[]="بازه تعریف شده ناصحیح است.";
				
			}
				
			elseif (strlen($_POST['RangeStart'])!= b::CotagLength ||strlen($_POST['RangeEnd'])!= b::CotagLength)
			{
				$Error[]="کوتاژ باید  ".b::CotagLength." رقمی باشد . ";
			}	
			else
			{
				
				$x=ORM::Query(new ReviewFile())->RecievedInRange($start,$end);
				$UnRecievedFile=array();
				
				for($i=$start;$i<= $end;$i++)
				{
					$flag=true;
					if($x)
					foreach ($x as $v)
					{
						if($v['Cotag']==$i)
						{
							
							$flag=false;
							break;
						}
					}
					if($flag)
					$UnRecievedFile[]=array('Cotag'=>$i);
				}
			}
			$tableparamlist=array(
					'Cotag'=>'کوتاژ'
				);
			$al=new AutolistPlugin($UnRecievedFile,$tableparamlist,'UnRecievedTable');
			$al->SetHeader("Cotag", "کوتاژ");
			$al->HasTier=true;
			$al->TierLabel="ردیف";
			$al->InputValues['ColsCount']=5;
			$al->InputValues['RowsCount']=30;
			$this->al=$al;
			$this->Count=count($UnRecievedFile);
			$this->Error=$Error;
			if (count($Error))
			$this->Result=false;
		}
		return $this->Present();
	}
	
	
}
