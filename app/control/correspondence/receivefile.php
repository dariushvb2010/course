<?php
class CorrespondenceReceivefileController extends JControl

{
	function Start()
	{
		j::Enforce("Correspondence");
		
		if (count($_POST))
		{
			$Cotag=$_POST['Cotag']*1;
			$MailNum=$_POST['MailNum'];
			$Sender=$_POST['Sender'];
			$Comment=$_POST['Comment'];
			$Res=ORM::Query(new ReviewProgressReceivefile())->AddToFile($Cotag,$Sender,$MailNum,$Comment);
			if(is_string($Res))
			{
				$Error[]=$Res;
			}
			else 
				$this->Result="مکاتبه دریافت شد.";
		}
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		return $this->Present();
	}
}