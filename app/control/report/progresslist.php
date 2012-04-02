<?php
class ReportProgresslistController extends JControl
{
	function Start()
	{
		if(j::Check("ProgressList"));
		
		if (count($_POST)){
			$Error=array();
			$Reviewer=ORM::find(new MyUser,j::UserID());
			$Cotag=$_REQUEST['Cotag']*1;
			if ($Cotag<1)
			{
				$Error[]="کوتاژ ناصحیح است.";
			}
			else 
			{
				
				$File=ORM::query(new ReviewFile)->GetRecentFile($Cotag);
				if ($File==null)
				{
					$Error[]="یافت نشد.";
				}else{
					$this->Data=$File->AllProgress();
					$this->Cotag=$Cotag;
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