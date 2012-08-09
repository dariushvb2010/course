<?php 
class TestPlugin extends JPlugin
{
	public static function Random_NewCotag()
	{
		$File=self::Random_NewFile();
		return $File->Cotag();
	}
	/**
	* generate a new random file that there not exists before
	* Enter description here ...
	*/
	public static function Random_NewFile()
	{
		$File=null;
		while(!$File)
		{
			for($i=0; $i<b::CotagLength; $i++)
			{
				$a=rand(0, 9);
				$Cotag.=strval($a);
			}
			if(b::CotagValidation($Cotag)==false)
			continue;
			$Cotag*=1;
			$F=ORM::Find1("ReviewFile", "Cotag",$Cotag);
			if(!$F)
			$File=new ReviewFile($Cotag);
		}
		return $File;
	}
	public static function NewFile_Start($Cotag=null)
	{
		$Result=true;
		$Cotag=self::Random_NewCotag();
		$s=ORM::Query("ReviewProgressStart")->AddToFile($Cotag);
		if(is_string($s))
			$Error[]=$s;
		
		if(count($Error))
		{
			$Result=false;
			ViewResultPlugin::Show($Result, $Error);
			return;
		}
		return $s->File();
	}
	/**
	 * Has to pick a random cotag from the database
	 */
	static function Pick_Cotag()
	{
		$File=self::Pick_File();
		return $File->Cotag();
	}
	/**
	 * Has to raturn a random file from the database
	 */
	static function Pick_File()
	{
		$count=ORM::Query("ReviewFile")->GetCount();
		while(!$File)
		{
			$randID=rand(1,$count);
			$File=b::GetFile($randID);
		}
		return $File;
	}
	
	
}