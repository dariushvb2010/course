<?php
class ManagerCorrectionController extends JControl
{
	function Start()
	{
		j::Enforce("MasterHand");
		if (count($_POST))
		{
			
			$NewCotag=$_POST['NewCotag']*1;
			$OldCotag=$_POST['OldCotag']*1;
			$Comment=$_POST['Comment'];
			
			$Res=ORM::Query("ReviewProgressCorrection")->AddToFile($OldCotag,$NewCotag,$Comment);
			
			if(is_string($Res))
			{
				$Error[]=$Res;
				$this->NewCotag=$NewCotag;
				$this->OldCotag=$OldCotag;
				$this->Comment=$Comment;
			}
			else 
			{
				$this->Result=true;
				$this->Result="کوتاژ".$OldCotag." به شماره کوتاژ ".$NewCotag." تغییر یافت.";
			}
		}
		
		
		$this->Error=$Error;
		
		if (count($Error)) $this->Result=false;

		return $this->Present();
	}
}