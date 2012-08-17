<?php
class ReportProgresslistController extends JControl
{
	function Start()
	{
		if(j::Check("ProgressList"));
		
		if (count($_REQUEST['Cotag'])){
			$Error=array();
			$Reviewer=ORM::find(new MyUser,j::UserID());
			$Cotag=$_REQUEST['Cotag'];
			if (!b::CotagValidation($Cotag))
			{
				$Error[]=v::Ecnv($Cotag);
			}
			else 
			{
				
				$File=b::GetFile($Cotag);
				if ($File==null)
				{
					$Error[]=v::Ecnf($Cotag);
					$this->Cotag=$Cotag;
				}else{
					$this->Data=$File->AllProgress();
					$this->File=$File;
					if(count($this->Data)==0)
						$Error[]='هیچ فرایندی در پرونده ی این کوتاژ موجود نیست.';
				}
				//TODO $this->Count=ORM::Query(new ReviewFile)->CotagCount();
			}	
			
		}
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		return $this->Present();
	}
}