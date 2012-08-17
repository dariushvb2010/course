<?php
class ManagerRemoveController extends JControl
{
	function Start()
	{
		j::Enforce("MasterHand");

		$Cotag=b::CotagFilter($_POST['Cotag']);
		$File=b::GetFile($Cotag);
		$LLP=ORM::Query("ReviewFile")->LastLiveProgress($Cotag);
		
		if (isset($_POST['Cotag']) && !isset($_POST['confirm']))
		{
			$this->LLP=$LLP;
		}
		else if(isset($_POST['confirm']))
		{
			$res=$File->killLLP($_POST['Comment']);
			if(is_string($res))
			{
				$Error[]=$res;
				
			}
			else 
			{
				$Result="با موفقیت حذف شد";
				$this->Result=$Result;
			}
			
		}
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;

		return $this->Present();
	}
}