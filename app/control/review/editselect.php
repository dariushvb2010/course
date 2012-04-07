<?php
class ReviewEditselectController extends JControl
{
	function Start()
	{
		j::Enforce("Reviewer");
		
		if ($_REQUEST['Cotag'])
		{
			$Cotag=$_POST['Cotag']*1;
			$Res=ORM::Query("ReviewProgressReview")->EditReview($Cotag);
			if(is_string($Res))
				$Error[]=$Res;
			else		
				$this->Redirect("./edit?Cotag={$Cotag}");	 						
			
		}
		
		$MyUnreviewedFiles=$this->Count=ORM::Query(new MyUser)->AssignedReviewableFile(j::UserID());
		
		
		$this->Count=count($MyUnreviewedFiles);
		$this->MyUnreviewedFiles=$MyUnreviewedFiles;
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		return $this->Present();
	}
	
	
}