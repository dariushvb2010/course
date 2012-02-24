<?php
class ProgrammerStateController extends JControl
{
	function Start()
	{
		j::Enforce("MasterHand");
		print_r($_POST);
		if (count($_POST))
		{
			$Cotag=$_POST['Cotag'];
			$Num=$_POST['Num'];
			
			$File=ORM::Find("ReviewFile",'Cotag',$Cotag);
			$File=$File[0];
			if($File)
			{
				$LastNum=$File->State();
				$File->SetState($Num);
				
			}
			else
			{
				$Error[]="اظهارنامه با شماره کوتاژ".$Cotag." یافت نشد.";
			}
			if(is_string($Res))
			{
				$Error[]=$Res;
			}
			else
			{
				
				
				$this->Result="موفق  ";
				$this->Result.=$Num;
				$this->Result.="شماره وضعیت قبلی:".$LastNum;
			}
		}
		
		
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
			
		return $this->Present();
	}
}
?>
