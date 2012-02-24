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
				
			elseif (strlen($_POST['RangeStart'])!= CotagLength ||strlen($_POST['RangeEnd'])!= CotagLength)
			{
				$Error[]="کوتاژ باید  ".CotagLength." رقمی باشد . ";
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
					$UnRecievedFile[$i]=array('Cotag'=>$i);
				}
			}
			$tableparamlist=array(
					'Cotag'=>'کوتاژ'
				);
			$this->al=new AutolistPlugin($UnRecievedFile,$tableparamlist,'UnRecievedTable');
			$this->Count=count($UnRecievedFile);
			$this->Error=$Error;
			if (count($Error))
			$this->Result=false;
		}
		return $this->Present();
	}
	
	
}
