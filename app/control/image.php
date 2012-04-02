<?php
class ImageController extends JControl
{
	function Start()
	{
		j::$App->LoadModule("model.jfilemanager");
		$as=new Jfilemanager();
		$as->Feed(j::RootDir()."/app/control/servic/uploads/".$_GET["name"],rand(10, 1000)."ee.jpg");
	}
}