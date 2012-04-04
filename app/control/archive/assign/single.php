<?php
class ArchiveAssignSingleController extends JControl
{
	function Start()
	{
		j::Enforce("Archive");
		
		if (count($_POST))
		{
			if (isset($_POST['Cotag']))
			{
				$Cotag=$_POST['Cotag'];
				$File=ORM::Query(new ReviewFile)->GetRecentFile($Cotag);
				$AssignResult=ORM::Query(new ReviewProgressAssign())->AddToFile($File);
				if(is_string($AssignResult))
				{
					$Error[]=$AssignResult;
				}
				else 
				{
					$this->Reviewer=$AssignResult->Reviewer();
				}
				
			} 
				
			$this->Cotag=$_POST['Cotag'];
		}
		$this->Error=$Error;
		if (count($Error))
		$this->Result=false;
		return $this->Present();
	}
}