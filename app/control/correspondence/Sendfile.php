<?php
class CorrespondenceSendfileController extends JControl

{
	function Start()
	{
		j::Enforce("Correspondence");
		
		if (count($_POST))
		{
			$Cotag=$_POST['Cotag']*1;
			$Mailnum=$_POST['Mailnum'];
			$Requester=$_POST['Requester'];
			$Comment=$_POST['Comment'];
			$Res=ORM::Query(new ReviewProgressSendfile)->AddToFile($Cotag,$Requester,$Mailnum,$Comment);
			if(is_string($Res))
			{
				$Error[]=$Res;
			}
			else 
				$this->Result="مکاتبه با موفقیت ثبت شد.";
		}
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		return $this->Present();
	}
}