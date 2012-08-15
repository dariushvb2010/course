<?php
class CotagStartController extends JControl
{
	
	function Start()
	{
		j::Enforce("CotagBook");
		return $this->SemiStart(MyGroup::CotagBook());
		
		
	}
	/**
	 * i write this function to inherite it in other controllers:[ArchiveStart, RakedStart, CorrespondecsStart]
	 * @param $gname string GroupName and also ProgressName like :[Start, Archive, Raked, Correspondence]
	 */
	public function SemiStart( MyGroup $startGroup)
	{
		if (count($_POST))
		{
// 			$startGroup = MyGroup::GetGroup($gname);
// 			if($startGroup==null)
// 				throw new Exception("startGroup cannot be null. we dont have a group name [".$gname."]");
			$IsPrint=isset($_POST['print']) ? true : false;
			$Cotag=$_POST['Cotag'];
			$this->Cotag=$Cotag;
		
			if(empty($Error)){
				$Res=ORM::Query("ReviewProgressStart")->AddToFile($Cotag,$IsPrint,$startGroup);
				if(is_string($Res))
				{
					$Error[]=$Res;
				}
				else
				{
					$this->Result = " اظهارنامه با شماره کوتاژ ".v::bgc($Cotag)." با موفقیت وصول گردید. ";
				}
			}else{
				$Error[]="";
			}
		}else{
			$IsPrint=true;
		}
		
		$this->Error=$Error;
		$this->IsPrint=$IsPrint;
		
		if (count($Error)) $this->Result=false;
		return $this->present("","cotag.start");
	}
	
}