<?php
class MyserviceFilereceivedController extends JControl
{
	function Start()
	{
            if(!isset($_GET['Cotag'])||!isset($_GET['Year']))
            {
               $this->wrongInput();
            }
            $Cotag=  FPlugin::hex2str($_GET['Cotag']);
            
            $File=b::GetFile($Cotag);
            if(!$File)
            {
               $this->wrongInput();
            }
            else
            {
                $res=true;
            }
            $result=json_encode(array('reuslt'=>$res,'isSuccess'=>true,'messages'=>null));
            
            echo FPlugin::strToHex($result);
            //echo FPlugin::strToHex($File->Cotag());
            exit();
	}
	
	function wrongInput(){
		$r=json_encode(array('reuslt'=>null,'isSuccess'=>false,'messages'=>array("خطا در ورودی")));
		return FPlugin::strToHex($r);
		exit();
	}
}
?>
