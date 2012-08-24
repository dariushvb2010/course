<?php
class ImageController extends JControl
{
	function Start()
	{
		j::$App->LoadModule("model.jfilemanager");
		$as=new Jfilemanager();
		$as->Feed(b::upload_folder_root.$_GET["name"],rand(10, 1000)."ee.jpg");
	}
}