<?php
class ReviewEditselectController extends JControl
{
	function Start()
	{
		j::Enforce("Reviewer");
		
		if ($_REQUEST['Cotag'])
		{
			$Cotag=b::CotagFilter($_POST['Cotag']);
			$Res=ORM::Query("ReviewProgressReview")->IsEditable($Cotag);
			if(is_string($Res))
				$Error[]=$Res;
			else		
				$this->Redirect("./edit?Cotag={$Cotag}");	 						
			
		}
		
		$CurrentUser=MyUser::CurrentUser();
		$MyUnreviewedFiles=$CurrentUser->AssignedReviewableFile();
		
		
		$this->Count=count($MyUnreviewedFiles);
		$this->MyUnreviewedFiles=$MyUnreviewedFiles;
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		return $this->Present();
	}
	
	
}