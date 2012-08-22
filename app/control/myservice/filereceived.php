<?php
class MyserviceFilereceivedController extends JControl
{
	function Start()
	{
            if(!isset($_GET['Cotag'])||!isset($_GET['Year']))
            {
               return $this->wrongInput();
            }
            $Cotag=  FPlugin::hex2str($_GET['Cotag']);
            
            $File=b::GetFile($Cotag);
            if(!$File)
            {
                $res=false;
            }
            else
            {
                $res=true;
            }
            $result=json_encode(array('reuslt'=>$res,'isSuccess'=>true,'messages'=>null));
            
            if(isset($_REQUEST['test']))
            	echo $result;
            else
            	echo FPlugin::strToHex($result);
            //echo FPlugin::strToHex($File->Cotag());
            exit();
	}
	
	function wrongInput(){
		$r=json_encode(array('reuslt'=>null,'isSuccess'=>false,'messages'=>array('خطا در ورودی')));

		if(isset($_REQUEST['test']))
			echo $r;
		else
			echo FPlugin::strToHex($r);
		exit();
	}
}
?>
