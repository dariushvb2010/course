<?php
class MyserviceFilereceivedController extends JControl
{
	function Start()
	{
            if(!isset($_GET['Cotag'])||!isset($_GET['Year']))
            {
                echo json_encode(array('reuslt'=>null,'isSuccess'=>false,'messages'=>array("خطا در ورودی")));
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
            
            echo ($result);
            echo FPlugin::str2hex('1231231');
            exit();
	}
}
?>
