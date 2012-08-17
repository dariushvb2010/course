<?php
/**
 * 
 * @author mohammadt
 *
 */
class ScanResponderController extends JControl
{
	
	function Start()
	{
		
		//must be relative
		$folder=b::upload_folder_relative_from_japp();
		$result = array();
		$res=true;
		foreach ($_FILES as $file)
		{

			if ($file['size'] > 5000000 )
			{
// 				die ("ERROR: Large File Size");
				$result[]=array("isSuccess"=>false,
								"Error"=>"اندازه ی فایل بزرگ است.");
				$res&=false;
				continue;

			}
			//check if its image file
			if (!getimagesize($file['tmp_name']))
			{
// 				echo "Invalid Image File...";
// 				exit();
				$result[]=array("isSuccess"=>false,
								"Error"=>"فرمت عکس اشتباه است");
				$res&=false;
				continue;
			}
			$blacklist = array(".php", ".phtml", ".php3", ".php4", ".js", ".shtml", ".pl" ,".py");
			foreach ($blacklist as $f)
			{
				if(preg_match("/$f\$/i", $file['name']))
				{
// 					echo "ERROR: Uploading executable files Not Allowed\n";
// 					exit;
					$result[]=array("isSuccess"=>false,
								"Error"=>"فرمت عکس اشتباه است");
					$res&=false;
					continue;
				}
			}
			if ($_FILES["file"]["error"] > 0)
			{
// 				echo "Return Code: " . $file["error"] . "<br />";
// 				exit;
				$result[]=array("isSuccess"=>false,
								"Error"=>"خطا در شبکه");
				$res&=false;
				continue;
			}
			//making directories in recersive mode
			if(!is_dir($folder))
			{	
				$old_umask = umask(0);
				if(!mkdir($folder,0777,true))
				{
					$result[]=array("isSuccess"=>false,
									"Error"=>"خطا1");
					$res&=false;
					continue;
				}
				umask($old_umask);
			}
			if (!is_writable($folder)){
				// 			var_dump(realpath(("../../upload/")));
// 				echo  "Server error. Upload directory isn't writable.";
				$result[]=array("isSuccess"=>false,
								"Error"=>"خطا2");
				$res&=false;
				continue;
			}
			if (file_exists($folder . $file["name"]))
			{
// 				echo $file["name"] . " already exists. ";
// 				exit;
				$result[]=array("isSuccess"=>false,
								"Error"=>"فایل تکراری است.");
				$res&=false;
				continue;
			}
			else
			{
				if(move_uploaded_file($file["tmp_name"],$folder . $file["name"]))
				{	
					$result[]=array("isSuccess"=>true,
								"Error"=>"");
					
					
					continue;
				}
				else 
				{
					$result[]=array("isSuccess"=>false,
								"Error"=>"خطا3");
					$res&=false;
					continue;
				}
					
			}
		}
		if(!$res)
		{
			$resultMsg="خطا در بارگذاری عکس ";
			$this->deleteMovedFile($folder,$result);
			$finalRes=false;
			
		}
		else
		{
			$Cotag=b::CotagFilter($_POST['Cotag']);
                        $action=$_POST['action'];
                        if($action == 'create')
                        {
                            $createFile=true;
                        }
                        else
                        {
                            $createFile=false;
                        }
			$AddRes=ORM::Query("ReviewProgressScan")->AddToFile($Cotag,$createFile);
			if(is_string($AddRes))
			{
				$resultMsg=$AddRes;
				$this->deleteMovedFile($folder,$result);
				$finalRes=false;
			}
			else
			{
				$finalRes=true;
				$this->renameMovedFile($folder,$AddRes);
				$resultMsg="اظهارنامه با شماره کوتاژ  ";
				$resultMsg.=" <span style='font-size:20px; color:black; font-weight:bold;'>";
				$resultMsg.=$Cotag."</span> "."با موفقیت وصول گردید.";
			
			}
		}
		
		echo json_encode(array("result"=>$finalRes,"resultMsg"=>$resultMsg,"units"=>$result));
		exit();
	}
	function deleteMovedFile($folder,$result)
	{
		$i=0;
		foreach ($_FILES as $file)
		{
	
			if(file_exists($folder.$file["name"]) && $result[$i]["isSuccess"])
			{
				unlink($folder.$file["name"]);
			}
			$i++;
		}
	}
	function renameMovedFile($folder,$id)
	{
		$i=0;
		foreach ($_FILES as $file)
		{
			if(file_exists($folder.$file["name"]))
			{
				rename($folder.$file["name"], $folder.$id->ID()."_".$i.'.jpg');
				ReviewImages::Add($id, $id->ID()."_".$i);
			}
			$i++;
		}
	}
	
}
