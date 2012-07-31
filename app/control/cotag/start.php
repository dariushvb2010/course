<?php
class CotagStartController extends JControl
{
	
	function Start()
	{
		j::Enforce("CotagBook");
		
		if (count($_POST))
		{
			$Cotag=$_POST['Cotag'];
			$IsPrint=isset($_POST['print']) ? true : false;
			//////////////////////////////////////
			/*$c=new ConnectionBakedata();
			$c->GetMojavezBargiri($Cotag);
			if($c->Validate()){
				$serial=$c->GetResult();
				$c=new ConnectionBakedata();
				$c->GetParvaneyeVaredat($serial);
				if($c->Validate())
					$AsyData=$c->GetResult();
				else
					$Error[]="اطلاعات اطهارنامه یافت نشد.";
			}else{
				$Error[]="اطهارنامه یافت نشد.";
			}*/
			///////////////////////////////////////////
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
		}
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		return $this->Present();
	}
	
	
}