<?php
class ManagerRemoveController extends JControl
{
	function Start()
	{
		j::Enforce("MasterHand");
		if (isset($_POST['Cotag']) && !isset($_POST['confirm']))
		{
			
			$Cotag=$_POST['Cotag']*1;
			$LLP=ORM::Query(new ReviewFile())->LastLiveProgress($Cotag);
			$this->LLP=$LLP;
		}
		else if(isset($_POST['confirm']))
		{
			$Cotag=$_POST['Cotag']*1;
			$File=ORM::Query(new ReviewFile())->GetRecentFile($Cotag);
			$res=ORM::Query(new ReviewProgressRemove())->AddToFile($File,$_POST['Comment']);
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