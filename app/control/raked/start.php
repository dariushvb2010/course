<?php
/**
 * starts the perilous journey of the cotag in the bazbini
 * @author dariush
 *
 */
class RakedStartController extends JControl
{

	function Start()
	{
		//j::Enforce("Archive");
		//ORM::Dump(MyGroup::Archive());
		//////////////////////////////////////

		if (count($_POST))
		{
			$Cotag=$_POST['Cotag'];
			$IsPrint=isset($_POST['print']) ? true : false;
			$Res=ORM::Query("ReviewProgressStart")->AddToFile($Cotag,$IsPrint,MyGroup::Raked());
			if(is_string($Res))
			{
				$Error[]=$Res;
			}
			else
			{
				$this->Result = " اظهارنامه با شماره کوتاژ ".v::bgc($Cotag)." با موفقیت وصول گردید. ";
			}
		}
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;

		//return $this->Present();
		return $this->Present("",'cotag/new');
		//$this->Prese
	}
}
