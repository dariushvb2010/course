<?php
class BarcodeController extends JControl
{
	function Start()
	{
		$B=new BarcodeModel();
		//if (!is_numeric($_GET['number']))
		//	return false;
		header("Content-type: image/jpeg");
		$B->Number2Barcode($_GET['number'],$_GET['height']*1,$_GET['width']*1,$_GET['sub'],$_GET['font']);
		return true;
		
	}
}