<?php
class ArchiveRegisterController extends JControl
{
	function Start()
	{
		j::Enforce("Archive");
		if(isset($_POST["register"]))
		{
			$Cotag = $_POST["Cotag"]*1;
			
			$File = ReviewFile::GetRecentFile($Cotag);
			if($File)
			{
				$st = $File->LastProgress();
				if($st instanceof ReviewProgressStart and $st->MailNum() != null and $File->State()==3)
				{
					$File->SetState(4);
					$Result="وصول شد.";
				}
				else
				{
					$Error[]="امکان وصول اظهارنامه وجود ندارد.";
				}
			}
			else
			{
				$Error[]="اظهارنامه یافت نشد.";
			}
			
			
		}
		$this->Error=$Error;
		if (count($Error))
			$this->Result=false;
		return $this->Present();
	}
	

}