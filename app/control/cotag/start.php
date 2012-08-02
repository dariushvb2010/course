<?php
class CotagStartController extends JControl
{
	
	function Start()
	{
		j::Enforce("CotagBook");
		
		
		if (count($_POST))
		{
			$IsPrint=isset($_POST['print']) ? true : false;
			$Cotag=$_POST['Cotag'];
			$this->Cotag=$Cotag;

			if(empty($Error)){
				$Res=ORM::Query("ReviewProgressStart")->AddToFile($Cotag,$IsPrint);
				if(is_string($Res))
				{
					$Error[]=$Res;
				}
				else 
				{
					$this->Result = " اظهارنامه با شماره کوتاژ ".v::bgc($Cotag)." با موفقیت وصول گردید. ";
				}
			}else{
				$Error[]="";
			}
		}else{
			$IsPrint=true;
		}
		
		$this->Error=$Error;
		$this->IsPrint=false;//$IsPrint;
		
		if (count($Error)) $this->Result=false;
		return $this->Present();
	}
	
	
}