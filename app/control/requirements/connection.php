<?php
class RequirementsConnectionController extends JControl
{
	
	function Start()
	{
		//------------------------------------------------------
		echo '<div class="box">';
		echo ''.' GetParvanehFromAsycudaYear ';
		$c=new ConnectionBakedata();
		$c->GetParvanehFromAsycudaYear("4032322", "1391");
		if($c->Validate()){
			echo '<p>';
			var_dump($c->GetResult());
			echo '</p>';
		}else{
			//var_dump($c->Error);
			echo $c->Error[0];
		}
		echo '</div>';
		//------------------------------------------------------
		echo '<div class="box">';
		echo ''.' GetParvanehFromAsycudaYear ';
		$c=new ConnectionBakedata();
		$c->GetParvanehFromAsycudaYear("4005903", "1391");
		if($c->Validate()){
			echo '<p>';
			var_dump($c->GetResult());
			echo '</p>';
		}else{
			//var_dump($c->Error);
			echo $c->Error[0];
		}
		echo '</div>';
		//------------------------------------------------------
		echo '<div class="box">';
		
		echo ''.' GetBijaks_Entrance ';
		$cq=new ConnectionBakedata();
		$cq->GetBijaks_Entrance("27386");
		if($cq->Validate()){
			//check the data
			echo 'ok'.BR;
			var_dump($cq->GetResult());
		}else{
			echo $cq->Error[0];
		}
		echo '</div>';
		//------------------------------------------------------
		echo '<div class="box">';
		echo ''.' GetParvaneVaredati ';
		$cq=new ConnectionBakedata();
		$cq->GetParvaneVaredati("1882531");
		if($cq->Validate()){
			//check the data
			echo 'ok';
			var_dump($cq->GetResult());
		}else{
			echo $cq->Error[0];
		}
		echo BR;
		echo '</div>';
		//------------------------------------------------------
		echo '<div class="box">';
		echo ''.' GetMojavezBargiriYear ';
		$cq=new ConnectionBakedata();
		$cq->GetMojavezBargiriYear("4032253", "1391");
		if($cq->Validate()){
			//check the data
			echo 'ok';
			var_dump($cq->GetResult());
		}else{
			echo $cq->Error[0];
		}
		echo BR;
		echo '</div>';
		//------------------------------------------------------
		echo '<div class="box">';
		echo ''.' GetMojavezBargiri ';
		$cq=new ConnectionBakedata();
		$cq->GetMojavezBargiri("4030937");
		if($cq->Validate()){
			//check the data
			echo 'ok';
			var_dump($cq->GetResult());
		}else{
			echo $cq->Error[0];
		}
		echo BR;
		echo '</div>';
		//------------------------------------------------------
		echo '<div class="box">';
		echo ''.' GetYear ';
		$cq=new ConnectionBakedata();
		$year=$cq->GetYear("6121317");
		if($cq->Validate()){
			//check the data
			echo 'ok';
			echo $year;
		}else{
			echo $cq->Error[0];
		}
		echo BR;
		echo '</div>';
		//------------------------------------------------------
		return $this->BarePresent();
	}
	
}