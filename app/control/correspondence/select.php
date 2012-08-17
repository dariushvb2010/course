<?php
class CorrespondenceSelectController extends JControl
{
	function Start()
	{
		j::Enforce("Correspondence");
		
		if ($_REQUEST['Cotag'])
		{
			$Cotag=b::CotagFilter($_POST['Cotag']);
			$Res=ORM::Query("ReviewProgressReview")->AddReview($Cotag);
			if(is_string($Res))
				$Error[]=$Res;
			else		
				$this->Redirect("./new?Cotag={$Cotag}");	 						
		}
		
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		return $this->Present();
	}
}