<?php 
class AutosoundPlugin
{
	public static function EchoError($Name)
	{
		echo "<embed src='/file/sound/".$Name.".swf' class='sound'/>";
	}
	
}