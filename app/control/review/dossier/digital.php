<?php
class ReviewDossierDigitalController extends JControl
{
	function Start()
	{
		//j::Enforce("Reviewer");
		
		$Cotag=$_REQUEST['Cotag']*1;
		if (isset($Cotag))
		{
			$r=ORM::Query(new ReviewImages)->AllDossierImages($Cotag);
			$this->Images=$r;
		}
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		$this->Cotag=$Cotag;
		return $this->Present();
	}
	
}