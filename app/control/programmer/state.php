<?php
class ProgrammerStateController extends JControl
{
	function Start()
	{
		j::Enforce("MasterHand");
		if (count($_POST))
		{
			$Cotages=$_POST['Cotag'];
			$Num=$_POST['Num'];
			foreach ($Cotages as $c)
				if($c)
					$New[]=$c;
			$Files=ReviewFile::RegulateWithError($New);
			if($Files)
			foreach ($Files as $File)
			{
				if($File instanceof ReviewFile)
				{
					$LastNum=$File->State();
					$File->SetState($Num);
					$this->Result.="موفق  ";
					$this->Result.=$Num;
					$this->Result.=" شماره وضعیت قبلی:".$LastNum." کوتاژ ".$File->Cotag()."<br/>";
				}
				else
				{
					$Error[]=strval($File);
				}
			}
		}
		
		$this->Error=$Error;
			
		return $this->Present();
	}
}
?>
