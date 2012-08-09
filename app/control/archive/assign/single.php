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
				$File=b::GetFile($Cotag);
				$AssignResult=ORM::Query("ReviewProgressAssign")->AddToFile($File);
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