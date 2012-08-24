<?php
class JchatIframeController extends JControl
{
	function Start()
	{
		$this->App->LoadModule("plugin.jchat.interface.iframe");
		$this->i=new jChat_Interface_Iframe();
			
		return $this->BarePresent();
	}
}
?>
