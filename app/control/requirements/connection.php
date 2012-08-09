<?php
class RequirementsConnectionController extends JControl
{
	
	function Start()
	{

		//------------------------------------------------------
		echo ''.' GetParvanehFromAsycudaYear ';
		$c=new ConnectionBakedata();
		$c->GetParvanehFromAsycudaYear("4000013", "1391");
		if($c->Validate()){
			//check the data
		}else{
			echo $c->Error[0];
		}
		echo BR;
		//------------------------------------------------------
		echo ''.' GetBijaks_Entrance ';
		$cq=new ConnectionBakedata();
		$cq->GetBijaks_Entrance("12344");
		if($cq->Validate()){
			//check the data
			echo 'ok';
		}else{
			echo $cq->Error[0];
		}
		echo BR;
		//------------------------------------------------------
		echo ''.' GetParvaneVaredati ';
		$cq=new ConnectionBakedata();
		$cq->GetParvanehVaredati("21598");
		if($cq->Validate()){
			//check the data
			echo 'ok';
		}else{
			echo $cq->Error[0];
		}
		echo BR;
		//------------------------------------------------------
		return $this->BarePresent();
	}
	
}