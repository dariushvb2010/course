<?php
class ScanResponderController extends JControl
{
	function Start()
	{
		ob_start();
		var_dump($_FILES);
		var_dump($_POST);
		$result = ob_get_clean();
		$fh = fopen("testFile.txt", 'w') or die("can't open file");
		fwrite($fh, $result);
		fclose($fh);
		
		$folder="../../../upload/";
		
		$result = array();
		foreach ($_FILES as $file)
		{

			if ($file['size'] > 5000000 )
			{
// 				die ("ERROR: Large File Size");
				$result[]=false;
				continue;

			}
			//check if its image file
			if (!getimagesize($file['tmp_name']))
			{
// 				echo "Invalid Image File...";
// 				exit();
				$result[]=false;
				continue;
			}
			$blacklist = array(".php", ".phtml", ".php3", ".php4", ".js", ".shtml", ".pl" ,".py");
			foreach ($blacklist as $f)
			{
				if(preg_match("/$f\$/i", $file['name']))
				{
// 					echo "ERROR: Uploading executable files Not Allowed\n";
// 					exit;
					$result[]=false;
					continue;
				}
			}
			if ($_FILES["file"]["error"] > 0)
			{
// 				echo "Return Code: " . $file["error"] . "<br />";
// 				exit;
				$result[]=false;
				continue;
			}
			//making directories in recersive mode
			if(!mkdir($folder,true))
			{
				$result[]=false;
				continue;
			}
			if (!is_writable($folder)){
				// 			var_dump(realpath(("../../upload/")));
// 				echo  "Server error. Upload directory isn't writable.";
				$result[]=false;
				continue;
			}
			if (file_exists($folder . $file["name"]))
			{
// 				echo $file["name"] . " already exists. ";
// 				exit;
				$result[]=false;
				continue;
			}
			else
			{
				if(move_uploaded_file($file["tmp_name"],$folder . $file["name"]))
				{	
					$result[]=true;
					continue;
				}
				else 
				{
					$result[]=false;
					continue;
				}
					
			}
		}
		echo json_encode($result);
	}
}