<?php
class CotagNewController extends JControl
{
	
	function Start()
	{
		j::Enforce("CotagBook");
		
		//////////////////////////////////////
		
		$c=new ConnectionBakedata();
		$c->GetMojavezBargiriYear("4000013", "1391");
		$f=$c->GetResult();
		var_dump($f);

		$c=new ConnectionBakedata();
		$c->GetParvaneyeVaredat("21598");
		$f=$c->GetResult();
		var_dump($f);
		
		///////////////////////////////////////////
		if (count($_POST))
		{
			$Cotag=$_POST['Cotag'];
			$IsPrint=isset($_POST['print']) ? true : false;
			$Res=ORM::Query("ReviewProgressStart")->AddToFile($Cotag,$IsPrint);
			if(is_string($Res))
			{
				$Error[]=$Res;
			}
			else 
			{

				$this->Result=true;
				$this->Result="اظهارنامه با شماره کوتاژ  ";
				$this->Result.=" <span style='font-size:20px; color:black; font-weight:bold;'>";
				$this->Result.=$Cotag."</span> "."با موفقیت وصول گردید.";
				
			}
		}
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		return $this->Present();
	}
	
	
}
